<?php

namespace App\Livewire\Implementors;

use App\Models\Assignment;
use App\Models\Course;
use App\Models\CourseEnrollee;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\QuizResult;
use App\Models\Submission;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CourseGrades extends Component
{
    public Course $course;

    /**
     * @var array<int, array<string, mixed>>
     */
    public array $students = [];

    /**
     * @var array<int, array<string, mixed>>
     */
    public array $activities = [];

    public function mount(Course $course): void
    {
        $this->authorizeCourse($course);
        $this->course = $course;
        $this->refreshData();
    }



public function downloadGradesCsv(): StreamedResponse
{
    $activities = collect($this->activities);
    $students = collect($this->students);

    $filename = 'course-grades-' . now()->format('Y-m-d') . '.csv';

    return response()->streamDownload(function () use ($activities, $students) {

        $handle = fopen('php://output', 'w');

        // Header row
        $headers = ['Student Name', 'Email'];

        foreach ($activities as $activity) {
            $headers[] = $activity['label'];
        }

        $headers[] = 'Final Grade';

        fputcsv($handle, $headers);

        // Student rows
        foreach ($students as $student) {
            $row = [
                $student['name'],
                $student['email'],
            ];

            foreach ($activities as $activity) {
                $cell = $student['activities'][$activity['key']] ?? null;
                $row[] = $cell && $cell['grade'] !== null ? $cell['grade'] : '';
            }

            $row[] = $student['final_grade'] !== null
                ? $student['final_grade']
                : '';

            fputcsv($handle, $row);
        }

        fclose($handle);

    }, $filename, [
        'Content-Type' => 'text/csv',
    ]);
}


    public function refreshData(): void
    {
        $students = CourseEnrollee::with([
                'user:id,profile_id,email,username',
                'user.profile:id,first_name,last_name'
            ])
            ->where('course_id', $this->course->id)
            ->get();

        $assignments = Assignment::where('course_id', $this->course->id)
            ->orderBy('created_at')
            ->get(['id', 'title']);

        $quizzes = Quiz::where('course_id', $this->course->id)
            ->orderBy('created_at')
            ->get(['id', 'quiz_title', 'status']);

        $this->activities = $this->buildActivities($assignments, $quizzes);
        $this->students = $this->buildStudentRows($students, $assignments, $quizzes, $this->activities);
    }

    public function render()
    {
        return view('livewire.implementors.course-grades', [
            'course' => $this->course,
            'students' => $this->students,
            'activities' => $this->activities,
        ])->extends('layouts.layout')->section('content');
    }

    /**
     * @param  EloquentCollection<int, Assignment>  $assignments
     * @param  EloquentCollection<int, Quiz>  $quizzes
     * @return array<int, array<string, mixed>>
     */
    protected function buildActivities(EloquentCollection $assignments, EloquentCollection $quizzes): array
    {
        $assignmentActivities = $assignments->map(function (Assignment $assignment, int $index) {
            return [
                'id' => $assignment->id,
                'label' => $assignment->title ?: 'Assignment ' . ($index + 1),
                'type' => 'assignment',
                'key' => 'assignment-' . $assignment->id,
            ];
        });

        $quizActivities = $quizzes->map(function (Quiz $quiz, int $index) {
            return [
                'id' => $quiz->id,
                'label' => $quiz->quiz_title ?: 'Quiz ' . ($index + 1),
                'type' => 'quiz',
                'key' => 'quiz-' . $quiz->id,
            ];
        });

        return $assignmentActivities
            ->concat($quizActivities)
            ->values()
            ->toArray();
    }

    /**
     * @param  Collection<int, CourseEnrollee>  $students
     * @param  EloquentCollection<int, Assignment>  $assignments
     * @param  EloquentCollection<int, Quiz>  $quizzes
     * @param  array<int, array<string, mixed>>  $activities
     * @return array<int, array<string, mixed>>
     */
    protected function buildStudentRows(
        Collection $students,
        EloquentCollection $assignments,
        EloquentCollection $quizzes,
        array $activities
    ): array {
        if ($students->isEmpty()) {
            return [];
        }

        $assignmentIds = $assignments->pluck('id');
        $quizIds = $quizzes->pluck('id');
        $studentUserIds = $students->pluck('enrollee_id');
        $courseEnrolleeIds = $students->pluck('id');

        $assignmentSubmissions = $assignmentIds->isNotEmpty()
            ? Submission::whereIn('assignment_id', $assignmentIds)
                ->whereIn('enrollee_id', $courseEnrolleeIds)
                ->get()
                ->groupBy(fn(Submission $submission) => $submission->enrollee_id . '-' . $submission->assignment_id)
            : collect();

        $quizResults = $quizIds->isNotEmpty()
            ? QuizResult::whereIn('quiz_id', $quizIds)
                ->whereIn('course_enrollee_id', $courseEnrolleeIds)
                ->get()
                ->groupBy(fn(QuizResult $result) => $result->course_enrollee_id . '-' . $result->quiz_id)
            : collect();

        $quizTotals = $quizIds->isNotEmpty()
            ? Question::whereIn('quiz_id', $quizIds)
                ->select('quiz_id', DB::raw('SUM(points) as total_points'))
                ->groupBy('quiz_id')
                ->pluck('total_points', 'quiz_id')
            : collect();

        return $students
            ->sortBy(fn($student) => strtolower($student->user->name ?? ''))
            ->values()
            ->map(function (CourseEnrollee $student) use ($activities, $assignmentSubmissions, $quizResults, $quizTotals) {
                $displayName = $this->resolveStudentName($student);

                $row = [
                    'id' => $student->id,
                    'enrollee_id' => $student->enrollee_id,
                    'name' => $displayName,
                    'email' => $student->user->email ?? '—',
                    'avatar' => $this->makeAvatarUrl($displayName ?: ($student->user->email ?? 'Student ' . $student->id)),
                    'activities' => [],
                    'final_grade' => null,
                ];

                $percentages = [];

                foreach ($activities as $activity) {
                    $key = $activity['key'];

                    if ($activity['type'] === 'assignment') {
                        $submissionKey = $student->id . '-' . $activity['id'];
                        $submission = $assignmentSubmissions->get($submissionKey)?->first();
                        $assignmentStatus = $submission
                            ? ($submission->grade !== null ? 'Graded' : 'Not Graded')
                            : 'Not Submitted';
                        $row['activities'][$key] = [
                            'submitted' => (bool) $submission,
                            'status' => $assignmentStatus,
                            'grade' => $this->formatAssignmentGrade($submission),
                        ];

                        // Always include assignment percentage (no submission = 0)
                        $percentages[] = $this->assignmentPercentage($submission);
                    } else {
                        $resultKey = $student->id . '-' . $activity['id'];
                        $result = $quizResults->get($resultKey)?->first();
                        $totalPoints = $quizTotals[$activity['id']] ?? null;
                        $quizPercent = $this->quizPercentage($result, $totalPoints);

                        if ($quizPercent !== null) {
                            $percentages[] = $quizPercent;
                        }

                        $row['activities'][$key] = [
                            'submitted' => (bool) $result,
                            'status' => $result ? ($result->status === 'Checked' ? 'Graded' : 'Submitted') : 'Not Submitted',
                            'grade' => $this->formatQuizGrade($result, $totalPoints),
                        ];
                    }
                }

                $row['final_grade'] = !empty($percentages)
                    ? round(array_sum($percentages) / count($percentages), 1)
                    : null;

                return $row;
            })
            ->values()
            ->toArray();
    }

    protected function formatAssignmentGrade(?Submission $submission): string
    {
        if (!$submission) {
            // No submission = 0%
            return '0%';
        }
        
        if ($submission->grade === null) {
            // Submitted but not graded yet
            return '—';
        }

        // Grade is stored as 0-100
        return $this->formatScore($submission->grade) . '%';
    }

    protected function assignmentPercentage(?Submission $submission): float
    {
        if (!$submission) {
            // No submission = 0%
            return 0.0;
        }
        
        if ($submission->grade === null) {
            // Submitted but not graded yet - don't count in average
            return 0.0;
        }

        // Grade is already a percentage (0-100)
        return (float) $submission->grade;
    }

    protected function formatQuizGrade(?QuizResult $result, ?float $totalPoints): ?string
    {
        if (!$result || $result->score === null) {
            return null;
        }

        if ($totalPoints && $totalPoints > 0) {
            $percentage = $this->quizPercentage($result, $totalPoints);
            return $percentage !== null ? $this->formatScore($percentage) . '%' : $this->formatScore($result->score);
        }

        return $this->formatScore($result->score);
    }

    protected function quizPercentage(?QuizResult $result, ?float $totalPoints): ?float
    {
        if (!$result || $result->score === null || !$totalPoints || $totalPoints <= 0) {
            return null;
        }

        return round(($result->score / $totalPoints) * 100, 1);
    }

    protected function formatScore(float $score, bool $trimTrailingZeros = true): string
    {
        $formatted = number_format($score, 1, '.', '');

        if ($trimTrailingZeros) {
            $formatted = rtrim(rtrim($formatted, '0'), '.');
        }

        return $formatted;
    }

    protected function makeAvatarUrl(string $name): string
    {
        $encoded = urlencode($name);

        return "https://ui-avatars.com/api/?name={$encoded}&background=0D8ABC&color=fff";
    }

    protected function resolveStudentName(CourseEnrollee $student): string
    {
        $profile = $student->user?->profile;
        if ($profile) {
            return trim($profile->first_name . ' ' . $profile->last_name);
        }

        return $student->user->username ?? $student->user->email ?? 'Student';
    }

    protected function authorizeCourse(Course $course): void
    {
        $implementor = auth()->user();

        if (!$implementor || (int) $implementor->role_id !== 2) {
            abort(403, 'Unauthorized. You must be an implementor.');
        }

        if ($course->implementer_id !== $implementor->id) {
            abort(403, 'Unauthorized access to this course.');
        }
    }
}

