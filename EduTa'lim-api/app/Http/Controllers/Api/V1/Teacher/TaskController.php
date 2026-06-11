<?php

namespace App\Http\Controllers\Api\V1\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskSubmission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Task::with(['course:id,title', 'lesson:id,title'])
            ->withCount('submissions');

        if (! $request->user()->isAdmin()) {
            $query->where('teacher_id', $request->user()->id);
        }

        return response()->json([
            'success' => true,
            'data'    => $query->latest()->get(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'course_id'   => ['required', 'exists:courses,id'],
            'lesson_id'   => ['nullable', 'exists:lessons,id'],
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'deadline'    => ['required', 'date', 'after:now'],
        ]);

        $validated['teacher_id'] = $request->user()->id;

        $task = Task::create($validated);
        $task->load(['course:id,title', 'lesson:id,title']);

        return response()->json([
            'success' => true,
            'data'    => $task,
            'message' => 'Topshiriq yaratildi.',
        ], 201);
    }

    public function update(Request $request, Task $task): JsonResponse
    {
        $this->authorizeTask($request, $task);

        $validated = $request->validate([
            'title'       => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'deadline'    => ['sometimes', 'date'],
            'lesson_id'   => ['nullable', 'exists:lessons,id'],
        ]);

        $task->update($validated);

        return response()->json([
            'success' => true,
            'data'    => $task,
        ]);
    }

    public function destroy(Request $request, Task $task): JsonResponse
    {
        $this->authorizeTask($request, $task);
        $task->delete();

        return response()->json(['success' => true, 'message' => 'Topshiriq o\'chirildi.']);
    }

    public function submissions(Request $request, Task $task): JsonResponse
    {
        $this->authorizeTask($request, $task);

        $submissions = $task->submissions()
            ->with('student:id,name,email')
            ->latest('submitted_at')
            ->get();

        return response()->json(['success' => true, 'data' => $submissions]);
    }

    public function review(Request $request, Task $task, TaskSubmission $submission): JsonResponse
    {
        $this->authorizeTask($request, $task);
        abort_if($submission->task_id !== $task->id, 404);

        $validated = $request->validate([
            'status'   => ['required', 'in:approved,rejected'],
            'feedback' => ['nullable', 'string'],
        ]);

        $submission->update([
            'status'      => $validated['status'],
            'feedback'    => $validated['feedback'] ?? null,
            'reviewed_at' => now(),
        ]);

        return response()->json(['success' => true, 'data' => $submission]);
    }

    private function authorizeTask(Request $request, Task $task): void
    {
        if ($request->user()->isAdmin()) return;
        abort_if($task->teacher_id !== $request->user()->id, 403, 'Forbidden.');
    }
}
