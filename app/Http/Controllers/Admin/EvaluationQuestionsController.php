<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EvaluationQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EvaluationQuestionsController extends Controller
{
    /**
     * Show the evaluation questions management page
     */
    public function index()
    {
        // Transform data to include 'statement' and 'description' fields for frontend compatibility
        $programRatings = EvaluationQuestion::programRatings()->get()->map(function ($q) {
            $q->statement = $q->text;
            return $q;
        });
        
        $programComments = EvaluationQuestion::programComments()->get()->map(function ($q) {
            $q->description = $q->text;
            return $q;
        });
        
        $implementerRatings = EvaluationQuestion::implementerRatings()->get()->map(function ($q) {
            $q->statement = $q->text;
            return $q;
        });
        
        $implementerComments = EvaluationQuestion::implementerComments()->get()->map(function ($q) {
            $q->description = $q->text;
            return $q;
        });

        return view('admin.evaluation-questions', compact(
            'programRatings',
            'programComments',
            'implementerRatings',
            'implementerComments'
        ));
    }

    /**
     * Store a new evaluation question
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:program_rating,program_comment,implementer_rating,implementer_comment',
            'text' => 'required|string|max:500',
            'order' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->only(['type', 'text', 'order']);
        $data['status'] = 'active';
        
        // Auto-assign order if not provided
        if (!isset($data['order'])) {
            $data['order'] = EvaluationQuestion::where('type', $request->type)->max('order') + 1;
        }

        $question = EvaluationQuestion::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Question created successfully',
            'data' => $question
        ]);
    }

    /**
     * Update an evaluation question
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'text' => 'required|string|max:500',
            'order' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $question = EvaluationQuestion::findOrFail($id);
        $question->update($request->only(['text', 'order']));

        return response()->json([
            'success' => true,
            'message' => 'Question updated successfully',
            'data' => $question
        ]);
    }

    /**
     * Delete an evaluation question
     */
    public function destroy($id)
    {
        $question = EvaluationQuestion::findOrFail($id);
        $question->delete();

        return response()->json([
            'success' => true,
            'message' => 'Question deleted successfully'
        ]);
    }

    /**
     * Publish all evaluation questions (batch update)
     */
    public function publish(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'questions' => 'required|array',
            'questions.*.type' => 'required|in:program_rating,program_comment,implementer_rating,implementer_comment',
            'questions.*.text' => 'required|string|max:500',
            'questions.*.order' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            \DB::beginTransaction();

            // Delete all existing questions (use delete instead of truncate for transactions)
            EvaluationQuestion::query()->delete();

            // Insert all new questions
            foreach ($request->questions as $questionData) {
                EvaluationQuestion::create([
                    'type' => $questionData['type'],
                    'text' => $questionData['text'],
                    'order' => $questionData['order'],
                    'status' => 'active'
                ]);
            }

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Questions published successfully'
            ]);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to publish questions: ' . $e->getMessage()
            ], 500);
        }
    }
}
