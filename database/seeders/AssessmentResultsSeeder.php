<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Profile;
use App\Models\Role;
use App\Models\Category;
use App\Models\Course;
use App\Models\CourseEnrollee;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Choice;
use App\Models\Answer;
use App\Models\QuizResult;
use Illuminate\Support\Facades\Hash;

class AssessmentResultsSeeder extends Seeder
{
    /**
     * Seed sample assessment results for testing
     */
    public function run(): void
    {
        // Ensure we have roles
        $learnerRole = Role::firstOrCreate(
            ['role_name' => 'learner'],
            ['role_name' => 'learner', 'role_description' => 'Student/Learner role']
        );
        
        $implementerRole = Role::firstOrCreate(
            ['role_name' => 'implementer'],
            ['role_name' => 'implementer', 'role_description' => 'Tutor/Implementer role']
        );

        // Ensure we have an implementer user
        $implementerProfile = Profile::firstOrCreate(
            ['first_name' => 'Sample', 'last_name' => 'Implementer'],
            ['first_name' => 'Sample', 'last_name' => 'Implementer']
        );
        
        $implementer = User::firstOrCreate(
            ['email' => 'implementer@example.com'],
            [
                'profile_id' => $implementerProfile->id,
                'role_id' => $implementerRole->id,
                'username' => 'sampleimplementer',
                'email' => 'implementer@example.com',
                'password' => Hash::make('password'),
                'phonenum' => '09170000000',
            ]
        );

        // Ensure we have a category
        $category = Category::firstOrCreate(
            ['category_name' => 'General Education'],
            ['category_name' => 'General Education']
        );

        // Ensure we have a course
        $course = Course::first();
        if (!$course) {
            $course = Course::create([
                'implementer_id' => $implementer->id,
                'category_id' => $category->id,
                'course_title' => 'Sample Course for Testing',
                'name' => 'sample-course',
                'background' => 'A sample course to test assessment results functionality.',
                'status' => 'Active',
                'visibility' => 'Visible',
                'start_date' => now()->subDays(30),
                'end_date' => now()->addDays(60),
            ]);
        }

        // Create sample learners with profiles
        $learners = [];
        $sampleNames = [
            ['Maria', 'Santos'],
            ['Juan', 'Dela Cruz'],
            ['Ana', 'Reyes'],
            ['Pedro', 'Garcia'],
            ['Rosa', 'Mendoza'],
        ];

        foreach ($sampleNames as $index => $name) {
            $email = strtolower($name[0]) . '.' . strtolower($name[1]) . '@example.com';
            
            // Check if user already exists
            $user = User::where('email', $email)->first();
            
            if (!$user) {
                $profile = Profile::create([
                    'first_name' => $name[0],
                    'last_name' => $name[1],
                ]);

                $user = User::create([
                    'profile_id' => $profile->id,
                    'role_id' => $learnerRole->id,
                    'username' => strtolower($name[0] . $name[1]),
                    'email' => $email,
                    'password' => Hash::make('password'),
                    'phonenum' => '0917' . str_pad($index + 1, 7, '0', STR_PAD_LEFT),
                ]);
            }

            // Check if already enrolled
            $enrollee = CourseEnrollee::where('enrollee_id', $user->id)
                ->where('course_id', $course->id)
                ->first();
                
            if (!$enrollee) {
                $enrollee = CourseEnrollee::create([
                    'enrollee_id' => $user->id,
                    'course_id' => $course->id,
                    'enrollment_date' => now()->subDays(rand(1, 30)),
                    'status' => 'Active',
                ]);
            }

            $learners[] = $enrollee;
        }

        // Create a sample quiz if none exists
        $quiz = Quiz::first();
        if (!$quiz) {
            $quiz = Quiz::create([
                'course_id' => $course->id,
                'quiz_title' => 'Sample Assessment Quiz',
                'description' => 'A sample quiz with mixed question types for testing the results page.',
                'status' => 'Published',
                'start_date' => now()->subDays(7),
                'end_date' => now()->addDays(7),
                'visibility' => true,
            ]);
        } else {
            // Clean up existing questions/choices if the seeder is rerun without dropping tables
            foreach ($quiz->questions as $existingQuestion) {
                $existingQuestion->choices()->delete();
                $existingQuestion->delete();
            }
        }

        // Create 50 questions (30 MC, 10 TF, 5 short answer, 5 long answer)
        $questions = collect();

        // Multiple choice questions
        for ($i = 1; $i <= 30; $i++) {
            $question = Question::create([
                'quiz_id' => $quiz->id,
                'question_text' => "Multiple Choice Question {$i}: Which option best describes concept {$i}?",
                'type' => 'multiple_choice',
                'points' => 2,
            ]);

            $correctLabel = "Correct answer {$i}";
            $options = [
                $correctLabel,
                "Distractor {$i}A",
                "Distractor {$i}B",
                "Distractor {$i}C",
            ];

            foreach ($options as $optionIndex => $text) {
                Choice::create([
                    'question_id' => $question->id,
                    'choice_text' => $text,
                    'is_correct' => $optionIndex === 0,
                ]);
            }

            $questions->push($question);
        }

        // True/False questions
        for ($i = 1; $i <= 10; $i++) {
            $question = Question::create([
                'quiz_id' => $quiz->id,
                'question_text' => "True or False Question {$i}: Statement about topic {$i}.",
                'type' => 'true_false',
                'points' => 1,
            ]);

            Choice::create(['question_id' => $question->id, 'choice_text' => 'True', 'is_correct' => $i % 2 === 0]);
            Choice::create(['question_id' => $question->id, 'choice_text' => 'False', 'is_correct' => $i % 2 !== 0]);

            $questions->push($question);
        }

        // Short answer questions
        for ($i = 1; $i <= 5; $i++) {
            $question = Question::create([
                'quiz_id' => $quiz->id,
                'question_text' => "Short Answer Question {$i}: Briefly explain concept {$i}.",
                'type' => 'short_answer',
                'points' => 3,
                'model_answer' => "Model short answer explanation for concept {$i}.",
            ]);

            $questions->push($question);
        }

        // Long answer questions
        for ($i = 1; $i <= 5; $i++) {
            $question = Question::create([
                'quiz_id' => $quiz->id,
                'question_text' => "Long Answer Question {$i}: Discuss the impact of initiative {$i} in detail.",
                'type' => 'long_answer',
                'points' => 5,
                'model_answer' => "Comprehensive model response for initiative {$i}, covering key points and examples.",
            ]);

            $questions->push($question);
        }

        // Reload quiz with the fresh set of questions
        $quiz->load('questions.choices');

        // Performance profiles for varied results
        $performanceProfiles = [
            ['mc' => 0.95, 'tf' => 0.95], // Maria - top performer
            ['mc' => 0.8, 'tf' => 0.75],  // Juan - solid
            ['mc' => 0.85, 'tf' => 0.85], // Ana - strong
            ['mc' => 0.55, 'tf' => 0.5],  // Pedro - struggling
            ['mc' => 0.8, 'tf' => 0.8],   // Rosa - consistent
        ];

        foreach ($learners as $index => $enrollee) {
            // Skip if this learner already has a quiz result
            $existingResult = QuizResult::where('quiz_id', $quiz->id)
                ->where('course_enrollee_id', $enrollee->id)
                ->first();
                
            if ($existingResult) {
                continue;
            }
            
            $profileAccuracy = $performanceProfiles[$index] ?? ['mc' => 0.7, 'tf' => 0.7];
            $totalScore = 0;
            $hasUngraded = false;

            foreach ($quiz->questions as $question) {
                $answerData = [
                    'question_id' => $question->id,
                    'quiz_id' => $quiz->id,
                    'course_enrollee_id' => $enrollee->id,
                ];

                switch ($question->type) {
                    case 'multiple_choice':
                        $correctChoice = $question->choices->where('is_correct', true)->first();
                        $wrongChoices = $question->choices->where('is_correct', false);

                        $isCorrect = mt_rand(0, 100) / 100 <= $profileAccuracy['mc'];
                        if ($isCorrect || $wrongChoices->isEmpty()) {
                            $selectedChoice = $correctChoice;
                            $answerData['is_correct'] = true;
                            $answerData['points'] = $question->points;
                            $totalScore += $question->points;
                        } else {
                            $selectedChoice = $wrongChoices->random();
                            $answerData['is_correct'] = false;
                            $answerData['points'] = 0;
                        }

                        $answerData['option_id'] = $selectedChoice->id ?? null;
                        $answerData['answer_text'] = $selectedChoice->choice_text ?? '';
                        break;

                    case 'true_false':
                        $choices = $question->choices;
                        $trueChoice = $choices->firstWhere('choice_text', 'True');
                        $falseChoice = $choices->firstWhere('choice_text', 'False');
                        $correctChoice = $choices->firstWhere('is_correct', true);

                        $isCorrect = mt_rand(0, 100) / 100 <= $profileAccuracy['tf'];
                        if ($isCorrect || !$falseChoice) {
                            $selectedChoice = $correctChoice ?? $trueChoice;
                            $answerData['is_correct'] = true;
                            $answerData['points'] = $question->points;
                            $totalScore += $question->points;
                        } else {
                            // Pick the opposite of the correct answer when possible
                            $selectedChoice = $correctChoice && $correctChoice->choice_text === 'True'
                                ? ($falseChoice ?? $correctChoice)
                                : ($trueChoice ?? $correctChoice);
                            $answerData['is_correct'] = false;
                            $answerData['points'] = 0;
                        }

                        $answerData['option_id'] = $selectedChoice->id ?? null;
                        $answerData['answer_text'] = $selectedChoice->choice_text ?? '';
                        break;

                    case 'short_answer':
                        $answerData['answer_text'] = "Learner response {$index}-{$question->id} explaining concept.";
                        if ($index < 2) {
                            $awarded = $index === 0 ? $question->points : max($question->points - 1, 1);
                            $answerData['points'] = $awarded;
                            $answerData['is_correct'] = $awarded >= ($question->points * 0.6);
                            $totalScore += $awarded;
                        } else {
                            $answerData['points'] = -1;
                            $answerData['is_correct'] = false;
                            $hasUngraded = true;
                        }
                        break;

                    case 'long_answer':
                        $answerData['answer_text'] = "Learner {$index} detailed essay for initiative question {$question->id}.";
                        if ($index === 0) {
                            $answerData['points'] = $question->points;
                            $answerData['is_correct'] = true;
                            $totalScore += $question->points;
                        } elseif ($index === 1) {
                            $awarded = $question->points - 2;
                            $answerData['points'] = $awarded;
                            $answerData['is_correct'] = false;
                            $totalScore += $awarded;
                        } else {
                            $answerData['points'] = -1;
                            $answerData['is_correct'] = false;
                            $hasUngraded = true;
                        }
                        break;
                }

                Answer::create($answerData);
            }

            // Create quiz result
            QuizResult::create([
                'quiz_id' => $quiz->id,
                'course_enrollee_id' => $enrollee->id,
                'score' => $totalScore,
                'remarks' => $hasUngraded ? 'Partially graded' : 'Grading complete',
                'status' => $hasUngraded ? 'Pending' : 'Checked',
                'checked_at' => $hasUngraded ? null : now(),
            ]);
        }

        $this->command->info('Assessment results seeder completed!');
        $this->command->info('Created 5 sample learners with quiz submissions.');
        $this->command->info('Some essays are graded, others need grading - perfect for testing!');
    }
}

