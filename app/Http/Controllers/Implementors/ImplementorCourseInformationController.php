<?php

namespace App\Http\Controllers\Implementors;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Module;
use App\Models\Assignment;
use App\Models\Resource;
use App\Models\User;
use App\Models\ProgramEvaluation;
use App\Models\Quiz;
use App\Models\Announcement;
use App\Models\CourseEnrollee;
use App\Models\CourseFeedback;
use App\Models\ImplementorFeedback;
use App\Models\SectionHeader;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;

class ImplementorCourseInformationController extends Controller
{
    public function show(Course $course)
    {
        // Get logged-in user
        $implementor = auth()->user();

        // Ensure the user is an implementor
        if (!$implementor || $implementor->role_id != 2) {
            abort(403, 'Unauthorized: Only implementors can access this page.');
        }

        // Ensure the implementor OWNS this course
        if ($course->implementer_id !== $implementor->id) {
            abort(403, 'Unauthorized: You do not own this course.');
        }

        // One-time initialization: Fix order values for items that have order = 0
        $this->initializeOrderValues($course->id);

        // Fetch related data
        $announcements = Announcement::where('course_id', $course->id)
    ->with(['user.profile.photo']) // ✅ eager-load user -> profile -> photo
    ->orderBy('order', 'asc')
    ->get();


        // Fetch assignments for this course
        $assignments = Assignment::where('course_id', $course->id)
            ->withCount('submissions')
            ->orderBy('order', 'asc')
            ->get();

        $evaluations = ProgramEvaluation::where('course_id', $course->id)
            ->orderBy('order', 'asc')
            ->get()
            ->map(function ($evaluation) {
                // Default due date: 7 days after creation
                $evaluation->due_date = $evaluation->created_at
                    ? $evaluation->created_at->copy()->addDays(7)
                    : null;
                return $evaluation;
            });

        // Feedback stats for implementor view
        $enrolledCount = CourseEnrollee::where('course_id', $course->id)
            ->whereIn('status', ['Active', 'Completed'])
            ->count();

        $courseFeedbacks = CourseFeedback::where('course_id', $course->id)->get();
        $implementorFeedbacks = ImplementorFeedback::where('course_id', $course->id)->get();

        $courseFeedbackStats = [
            'total_responses' => $courseFeedbacks->count(),
            'completion_rate' => $enrolledCount > 0 ? round(($courseFeedbacks->count() / $enrolledCount) * 100, 1) : null,
            'averages' => [
                'overall' => $courseFeedbacks->avg('overall_rating'),
                'materials' => $courseFeedbacks->avg('materials_rating'),
                'structure' => $courseFeedbacks->avg('structure_rating'),
                'engagement' => $courseFeedbacks->avg('engagement_rating'),
            ],
            'distribution' => [
                'overall' => $courseFeedbacks->groupBy('overall_rating')->map->count(),
                'materials' => $courseFeedbacks->groupBy('materials_rating')->map->count(),
                'structure' => $courseFeedbacks->groupBy('structure_rating')->map->count(),
                'engagement' => $courseFeedbacks->groupBy('engagement_rating')->map->count(),
            ],
        ];

        $implementorFeedbackStats = [
            'total_responses' => $implementorFeedbacks->count(),
            'completion_rate' => $enrolledCount > 0 ? round(($implementorFeedbacks->count() / $enrolledCount) * 100, 1) : null,
            'averages' => [
                'teaching_effectiveness' => $implementorFeedbacks->avg('teaching_effectiveness_rating'),
                'responsiveness' => $implementorFeedbacks->avg('responsiveness_rating'),
                'explanation_clarity' => $implementorFeedbacks->avg('explanation_clarity_rating'),
                'recommendation' => $implementorFeedbacks->avg('recommendation_rating'),
            ],
            'distribution' => [
                'teaching_effectiveness' => $implementorFeedbacks->groupBy('teaching_effectiveness_rating')->map->count(),
                'responsiveness' => $implementorFeedbacks->groupBy('responsiveness_rating')->map->count(),
                'explanation_clarity' => $implementorFeedbacks->groupBy('explanation_clarity_rating')->map->count(),
                'recommendation' => $implementorFeedbacks->groupBy('recommendation_rating')->map->count(),
            ],
        ];

        $feedbackComments = [
            'course' => $courseFeedbacks->whereNotNull('comment')->map(function ($item) {
                return [
                    'comment' => $item->comment,
                    'created_at' => $item->created_at?->format('M d, Y'),
                ];
            }),
            'implementor' => $implementorFeedbacks->whereNotNull('comment')->map(function ($item) {
                return [
                    'comment' => $item->comment,
                    'created_at' => $item->created_at?->format('M d, Y'),
                ];
            }),
        ];
        $quiz = Quiz::where('course_id', $course->id)
            ->withCount('results')
            ->orderBy('order', 'asc')
            ->get();

        // Fetch modules
        $modules = Module::where('course_id', $course->id)
            ->orderBy('order', 'asc')
            ->with('lessons')
            ->get();

        // Fetch section headers
        $sectionHeaders = SectionHeader::where('course_id', $course->id)
            ->orderBy('order', 'asc')
            ->get();

            
$timeline = collect()

    ->merge($modules->map(fn ($m) => [
        'type' => 'module',
        'model' => $m,
        'order' => $m->order ?? 0,
    ]))

    ->merge($assignments->map(fn ($a) => [
        'type' => 'assignment',
        'model' => $a,
        'order' => $a->order ?? 0,
    ]))

    ->merge($quiz->map(fn ($q) => [
        'type' => 'quiz',
        'model' => $q,
        'order' => $q->order ?? 0,
    ]))

    ->merge($evaluations->map(fn ($e) => [
        'type' => 'evaluation',
        'model' => $e,
        'order' => $e->order ?? 0,
    ]))

    ->merge($announcements->map(fn ($n) => [
        'type' => 'announcement',
        'model' => $n,
        'order' => $n->order ?? 0,
    ]))

    ->merge($sectionHeaders->map(fn ($s) => [
        'type' => 'section_header',
        'model' => $s,
        'order' => $s->order ?? 0,
    ]))

    ->sortBy('order')   // ✅ Sort by order column
    ->values();

        return view('livewire.implementors.implementor-course-details', [
    'course'        => $course,
    'courseId'      => $course->id,
    'modules'       => $modules,
    'assignments'   => $assignments,
    'evaluations'   => $evaluations,
    'quiz'          => $quiz,
    'announcements' => $announcements,
    'timeline'      => $timeline, // ✅ ADD THIS
    'courseFeedbackStats' => $courseFeedbackStats,
    'implementorFeedbackStats' => $implementorFeedbackStats,
    'feedbackComments' => $feedbackComments,
    'enrolledCount' => $enrolledCount,
]);

    }

    public function destroy(Module $module)
    {
        $module->delete();

    return redirect()->back()->with('success', 'Module deleted successfully.');
}

    public function deleteAssignment(Course $course, Assignment $assignment)
    {
        $implementor = auth()->user();
        if (!$implementor || $implementor->role_id !== 2 || $course->implementer_id !== $implementor->id || $assignment->course_id !== $course->id) {
            abort(403, 'Unauthorized.');
        }

        // Delete related submissions first to avoid foreign key constraint error
        $assignment->submissions()->delete();

        $assignment->delete();

        return redirect()->back()->with('success', 'Assignment deleted successfully.');
    }

    public function reorderTimeline(Course $course, Request $request)
    {
        $implementor = auth()->user();
        
        // Authorization check
        if (!$implementor || $implementor->role_id !== 2 || $course->implementer_id !== $implementor->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $orderedItems = $request->input('items', []);
            
            // Update order for each item type
            foreach ($orderedItems as $index => $item) {
                // Get last part as ID, everything before as type
                $lastUnderscore = strrpos($item, '_');
                if ($lastUnderscore === false) continue;
                
                $type = substr($item, 0, $lastUnderscore);
                $id = substr($item, $lastUnderscore + 1);
                
                if (!$id || !is_numeric($id)) continue;
                
                $order = $index + 1; // 1-based ordering
                
                switch ($type) {
                    case 'section_header':
                        SectionHeader::where('id', $id)
                            ->where('course_id', $course->id)
                            ->update(['order' => $order]);
                        break;
                        
                    case 'module':
                        Module::where('id', $id)
                            ->where('course_id', $course->id)
                            ->update(['order' => $order]);
                        break;
                        
                    case 'assignment':
                        Assignment::where('id', $id)
                            ->where('course_id', $course->id)
                            ->update(['order' => $order]);
                        break;
                        
                    case 'quiz':
                        Quiz::where('id', $id)
                            ->where('course_id', $course->id)
                            ->update(['order' => $order]);
                        break;
                        
                    case 'evaluation':
                        ProgramEvaluation::where('id', $id)
                            ->where('course_id', $course->id)
                            ->update(['order' => $order]);
                        break;
                        
                    case 'announcement':
                        Announcement::where('id', $id)
                            ->where('course_id', $course->id)
                            ->update(['order' => $order]);
                        break;
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Timeline order updated successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to update order',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Initialize order values for items that have order = 0
     * This runs once to fix existing data
     */
    private function initializeOrderValues($courseId)
    {
        // Collect all timeline items
        $items = collect();
        
        // Add all items with their created_at timestamp
        Module::where('course_id', $courseId)->where('order', 0)->get()->each(function($item) use ($items) {
            $items->push(['type' => 'module', 'id' => $item->id, 'created_at' => $item->created_at]);
        });
        
        Assignment::where('course_id', $courseId)->where('order', 0)->get()->each(function($item) use ($items) {
            $items->push(['type' => 'assignment', 'id' => $item->id, 'created_at' => $item->created_at]);
        });
        
        Quiz::where('course_id', $courseId)->where('order', 0)->get()->each(function($item) use ($items) {
            $items->push(['type' => 'quiz', 'id' => $item->id, 'created_at' => $item->created_at]);
        });
        
        ProgramEvaluation::where('course_id', $courseId)->where('order', 0)->get()->each(function($item) use ($items) {
            $items->push(['type' => 'evaluation', 'id' => $item->id, 'created_at' => $item->created_at]);
        });
        
        Announcement::where('course_id', $courseId)->where('order', 0)->get()->each(function($item) use ($items) {
            $items->push(['type' => 'announcement', 'id' => $item->id, 'created_at' => $item->created_at]);
        });
        
        SectionHeader::where('course_id', $courseId)->where('order', 0)->get()->each(function($item) use ($items) {
            $items->push(['type' => 'section_header', 'id' => $item->id, 'created_at' => $item->created_at]);
        });
        
        // If no items need initialization, return early
        if ($items->isEmpty()) {
            return;
        }
        
        // Sort by created_at and assign sequential order numbers
        $items = $items->sortBy('created_at')->values();
        
        // Get the current max order to start from there
        $maxOrder = max(
            Module::where('course_id', $courseId)->max('order') ?? 0,
            Assignment::where('course_id', $courseId)->max('order') ?? 0,
            Quiz::where('course_id', $courseId)->max('order') ?? 0,
            ProgramEvaluation::where('course_id', $courseId)->max('order') ?? 0,
            Announcement::where('course_id', $courseId)->max('order') ?? 0,
            SectionHeader::where('course_id', $courseId)->max('order') ?? 0
        );
        
        // Update each item with its new order
        $items->each(function($item, $index) use ($courseId, $maxOrder) {
            $order = $maxOrder + $index + 1;
            
            switch($item['type']) {
                case 'module':
                    Module::where('id', $item['id'])->where('course_id', $courseId)->update(['order' => $order]);
                    break;
                case 'assignment':
                    Assignment::where('id', $item['id'])->where('course_id', $courseId)->update(['order' => $order]);
                    break;
                case 'quiz':
                    Quiz::where('id', $item['id'])->where('course_id', $courseId)->update(['order' => $order]);
                    break;
                case 'evaluation':
                    ProgramEvaluation::where('id', $item['id'])->where('course_id', $courseId)->update(['order' => $order]);
                    break;
                case 'announcement':
                    Announcement::where('id', $item['id'])->where('course_id', $courseId)->update(['order' => $order]);
                    break;
                case 'section_header':
                    SectionHeader::where('id', $item['id'])->where('course_id', $courseId)->update(['order' => $order]);
                    break;
            }
        });
    }

}
