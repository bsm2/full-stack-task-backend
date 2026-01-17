<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Services\TaskService;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

/**
 * @OA\Info(
 *     title="Task Manager API",
 *     version="1.0.0",
 *     description="API documentation for the Task Management system"
 * )
 *
 * @OA\Server(
 *     url="http://localhost:8000/api",
 *     description="Local API server"
 * )
 *
 * @OA\Tag(
 *     name="Tasks",
 *     description="Operations related to managing tasks"
 * )
 */
class TaskController extends Controller
{
    use ApiResponse;

    public function __construct(protected TaskService $taskService) {}

    /**
     * @OA\Get(
     *     path="/tasks",
     *     summary="Get all tasks",
     *     tags={"Tasks"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Tasks retrieved successfully",
     *         @OA\JsonContent(
     *             example={
     *                 "success": true,
     *                 "message": "Tasks retrieved successfully",
     *                 "data": {
     *                     {
     *                         "id": 1,
     *                         "title": "Write API docs",
     *                         "description": "Document endpoints using Swagger",
     *                         "status": "pending",
     *                         "priority": "high",
     *                         "due_date": "2025-11-10"
     *                     },
     *                     {
     *                         "id": 2,
     *                         "title": "Fix task update bug",
     *                         "description": "Resolve issue in update API",
     *                         "status": "done",
     *                         "priority": "medium",
     *                         "due_date": "2025-10-30"
     *                     }
     *                 }
     *             }
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function index(Request $request)
    {
        $tasks = $this->taskService->list($request);
        return $this->success($tasks, 'Tasks retrieved successfully');
    }

    /**
     * @OA\Post(
     *     path="/tasks",
     *     summary="Create a new task",
     *     tags={"Tasks"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "description", "status", "due_date", "priority"},
     *             @OA\Property(property="title", type="string", example="Write API docs"),
     *             @OA\Property(property="description", type="string", example="Complete the API documentation using Swagger"),
     *             @OA\Property(property="status", type="string", example="pending"),
     *             @OA\Property(property="due_date", type="string", format="date", example="2025-11-10"),
     *             @OA\Property(property="priority", type="string", example="high")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Task created successfully",
     *         @OA\JsonContent(
     *             example={
     *                 "success": true,
     *                 "message": "Task created successfully",
     *                 "data": {
     *                     "id": 5,
     *                     "title": "Write API docs",
     *                     "description": "Complete the API documentation using Swagger",
     *                     "status": "pending",
     *                     "priority": "high",
     *                     "due_date": "2025-11-10"
     *                 }
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation failed",
     *         @OA\JsonContent(
     *             example={
     *                 "success": false,
     *                 "message": "Validation failed.",
     *                 "data": {
     *                     "title": {"The title field is required."}
     *                 }
     *             }
     *         )
     *     )
     * )
     */
    public function store(StoreTaskRequest $request)
    {
        $validated = $request->validated();
        $task = $this->taskService->create($validated);
        return $this->success($task, 'Task created successfully', 201);
    }

    /**
     * @OA\Get(
     *     path="/tasks/{id}",
     *     summary="Get a specific task",
     *     tags={"Tasks"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="Task ID"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Task retrieved successfully",
     *         @OA\JsonContent(
     *             example={
     *                 "success": true,
     *                 "message": "Task retrieved successfully",
     *                 "data": {
     *                     "id": 1,
     *                     "title": "Write API docs",
     *                     "description": "Document endpoints using Swagger",
     *                     "status": "pending",
     *                     "priority": "high",
     *                     "due_date": "2025-11-10"
     *                 }
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Task not found",
     *         @OA\JsonContent(
     *             example={
     *                 "success": false,
     *                 "message": "The requested page was not found.",
     *                 "data": {"error": "No query results for model [Task] 999"}
     *             }
     *         )
     *     )
     * )
     */
    public function show(Task $task)
    {
        $task = $this->taskService->show($task);
        return $this->success($task, 'Task retrieved successfully');
    }

    /**
     * @OA\Put(
     *     path="/tasks/{id}",
     *     summary="Update an existing task",
     *     tags={"Tasks"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="Task ID"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", example="Updated Task Title"),
     *             @OA\Property(property="description", type="string", example="Updated description"),
     *             @OA\Property(property="status", type="string", example="done"),
     *             @OA\Property(property="due_date", type="string", format="date", example="2025-11-12"),
     *             @OA\Property(property="priority", type="string", example="medium")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Task updated successfully",
     *         @OA\JsonContent(
     *             example={
     *                 "success": true,
     *                 "message": "Task updated successfully",
     *                 "data": {
     *                     "id": 1,
     *                     "title": "Updated Task Title",
     *                     "description": "Updated description",
     *                     "status": "done",
     *                     "priority": "medium",
     *                     "due_date": "2025-11-12"
     *                 }
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation failed",
     *         @OA\JsonContent(
     *             example={
     *                 "success": false,
     *                 "message": "Validation failed.",
     *                 "data": {
     *                     "status": {"The status field must be one of: pending, done, in_progress."}
     *                 }
     *             }
     *         )
     *     )
     * )
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        Gate::authorize('update', $task);
        $validated = $request->validated();
        $task = $this->taskService->update($task, $validated);
        return $this->success($task, 'Task updated successfully');
    }

    /**
     * @OA\Delete(
     *     path="/tasks/{id}",
     *     summary="Delete a task",
     *     tags={"Tasks"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="Task ID"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Task deleted successfully",
     *         @OA\JsonContent(
     *             example={
     *                 "success": true,
     *                 "message": "Task deleted successfully",
     *                 "data": {}
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Task not found",
     *         @OA\JsonContent(
     *             example={
     *                 "success": false,
     *                 "message": "The requested page was not found.",
     *                 "data": {"error": "No query results for model [Task] 99"}
     *             }
     *         )
     *     )
     * )
     */
    public function destroy(Task $task)
    {
        Gate::authorize('delete', $task);
        $this->taskService->delete($task);
        return $this->success(null, 'Task deleted successfully');
    }
}
