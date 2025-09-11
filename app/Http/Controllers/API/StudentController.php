<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\StudentResource;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class StudentController extends Controller
{
    public function index()
    {
        try {
            $students = Student::all();
            
            return response()->json([
                'success' => true,
                'data' => StudentResource::collection($students),
                'message' => 'Students retrieved successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving students',
                'errors' => ['server' => ['Internal server error']]
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:students',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date|before:today',
            'enrollment_date' => 'required|date',
            'student_id' => 'required|string|max:50|unique:students',
            'status' => 'nullable|in:active,inactive,graduated,suspended'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $student = Student::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'date_of_birth' => $request->date_of_birth,
                'enrollment_date' => $request->enrollment_date,
                'student_id' => $request->student_id,
                'status' => $request->status ?? 'active'
            ]);

            return response()->json([
                'success' => true,
                'data' => new StudentResource($student),
                'message' => 'Student created successfully'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating student',
                'errors' => ['server' => ['Internal server error']]
            ], 500);
        }
    }

    public function show(string $id)
    {
        try {
            $student = Student::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => new StudentResource($student),
                'message' => 'Student retrieved successfully'
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Student not found',
                'errors' => ['student' => ['Student with ID ' . $id . ' not found']]
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving student',
                'errors' => ['server' => ['Internal server error']]
            ], 500);
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $student = Student::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Student not found',
                'errors' => ['student' => ['Student with ID ' . $id . ' not found']]
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:students,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date|before:today',
            'enrollment_date' => 'sometimes|required|date',
            'student_id' => 'sometimes|required|string|max:50|unique:students,student_id,' . $id,
            'status' => 'sometimes|required|in:active,inactive,graduated,suspended'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $student->update($request->only([
                'name', 'email', 'phone', 'date_of_birth', 
                'enrollment_date', 'student_id', 'status'
            ]));

            return response()->json([
                'success' => true,
                'data' => new StudentResource($student),
                'message' => 'Student updated successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating student',
                'errors' => ['server' => ['Internal server error']]
            ], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            $student = Student::findOrFail($id);
            $student->delete();
            
            return response()->json([
                'success' => true,
                'data' => null,
                'message' => 'Student deleted successfully'
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Student not found',
                'errors' => ['student' => ['Student with ID ' . $id . ' not found']]
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting student',
                'errors' => ['server' => ['Internal server error']]
            ], 500);
        }
    }
}