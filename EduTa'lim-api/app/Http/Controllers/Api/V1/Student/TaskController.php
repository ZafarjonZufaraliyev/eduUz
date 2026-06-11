<?php

namespace App\Http\Controllers\Api\V1\Student;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskSubmission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $courseIds = $request->user()->enrollments()->pluck('course_id');

        $tasks = Task::with(['course:id,title', 'lesson:id,title'])
            ->whereIn('course_id', $courseIds)
            ->with(['submissions' => fn($q) => $q->where('student_id', $request->user()->id)])
            ->latest('deadline')
            ->get()
            ->map(function ($task) {
                $task->my_submission = $task->submissions->first();
                unset($task->submissions);
                return $task;
            });

        return response()->json(['success' => true, 'data' => $tasks]);
    }

    public function submit(Request $request, Task $task): JsonResponse
    {
        $courseIds = $request->user()->enrollments()->pluck('course_id');
        abort_unless($courseIds->contains($task->course_id), 403, 'Siz bu kursga yozilmagansiz.');

        $validated = $request->validate([
            'answer' => ['nullable', 'string'],
            'file'   => ['nullable', 'file', 'max:10240', 'mimes:pdf,doc,docx,jpg,jpeg,png,zip,txt'],
        ]);

        $filePath = null;
        $fileName = null;
        if ($request->hasFile('file')) {
            $file     = $request->file('file');
            $fileName = $file->getClientOriginalName();
            $filePath = $file->store('submissions', 'public');
        }

        $submission = TaskSubmission::updateOrCreate(
            ['task_id' => $task->id, 'student_id' => $request->user()->id],
            [
                'answer'       => $validated['answer'] ?? null,
                'file_path'    => $filePath,
                'file_name'    => $fileName,
                'status'       => 'pending',
                'feedback'     => null,
                'submitted_at' => now(),
                'reviewed_at'  => null,
            ]
        );

        return response()->json([
            'success' => true,
            'data'    => $submission,
            'message' => 'Topshiriq yuborildi.',
        ]);
    }
}
