<?php

use App\Models\Student;
use App\Models\User;

beforeEach(function () {
    // Create a test user for authentication
    $this->user = User::factory()->create([
        'email_verified_at' => now(),
    ]);
    
    $this->token = $this->user->createToken('test-token')->plainTextToken;
});

it('can list all students', function () {
    // Create some test students
    Student::factory()->count(3)->create();
    
    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $this->token,
    ])->getJson('/api/students');
    
    $response->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'email',
                    'phone',
                    'date_of_birth',
                    'enrollment_date',
                    'student_id',
                    'status',
                    'created_at',
                    'updated_at'
                ]
            ],
            'message'
        ])
        ->assertJson([
            'success' => true,
            'message' => 'Students retrieved successfully'
        ]);
    
    expect($response->json('data'))->toHaveCount(3);
});

it('can create a new student', function () {
    $studentData = [
        'name' => 'John Doe',
        'email' => 'john.doe@example.com',
        'phone' => '+1234567890',
        'date_of_birth' => '2000-01-15',
        'enrollment_date' => '2023-09-01',
        'student_id' => 'STU001',
        'status' => 'active'
    ];
    
    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $this->token,
    ])->postJson('/api/students', $studentData);
    
    $response->assertStatus(201)
        ->assertJsonStructure([
            'success',
            'data' => [
                'id',
                'name',
                'email',
                'phone',
                'date_of_birth',
                'enrollment_date',
                'student_id',
                'status',
                'created_at',
                'updated_at'
            ],
            'message'
        ])
        ->assertJson([
            'success' => true,
            'message' => 'Student created successfully',
            'data' => [
                'name' => 'John Doe',
                'email' => 'john.doe@example.com',
                'student_id' => 'STU001',
                'status' => 'active'
            ]
        ]);
    
    $this->assertDatabaseHas('students', [
        'email' => 'john.doe@example.com',
        'student_id' => 'STU001'
    ]);
});

it('validates required fields when creating a student', function () {
    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $this->token,
    ])->postJson('/api/students', []);
    
    $response->assertStatus(422)
        ->assertJson([
            'success' => false,
            'message' => 'Validation errors'
        ])
        ->assertJsonValidationErrors(['name', 'email', 'enrollment_date', 'student_id']);
});

it('validates email uniqueness when creating a student', function () {
    $existingStudent = Student::factory()->create(['email' => 'test@example.com']);
    
    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $this->token,
    ])->postJson('/api/students', [
        'name' => 'Jane Doe',
        'email' => 'test@example.com',
        'enrollment_date' => '2023-09-01',
        'student_id' => 'STU002'
    ]);
    
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});

it('can show a specific student', function () {
    $student = Student::factory()->create();
    
    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $this->token,
    ])->getJson("/api/students/{$student->id}");
    
    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'message' => 'Student retrieved successfully',
            'data' => [
                'id' => $student->id,
                'name' => $student->name,
                'email' => $student->email,
                'student_id' => $student->student_id
            ]
        ]);
});

it('returns 404 when showing non-existent student', function () {
    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $this->token,
    ])->getJson('/api/students/999');
    
    $response->assertStatus(404)
        ->assertJson([
            'success' => false,
            'message' => 'Student not found'
        ]);
});

it('can update a student', function () {
    $student = Student::factory()->create();
    
    $updateData = [
        'name' => 'Updated Name',
        'status' => 'inactive'
    ];
    
    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $this->token,
    ])->putJson("/api/students/{$student->id}", $updateData);
    
    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'message' => 'Student updated successfully',
            'data' => [
                'id' => $student->id,
                'name' => 'Updated Name',
                'status' => 'inactive'
            ]
        ]);
    
    $this->assertDatabaseHas('students', [
        'id' => $student->id,
        'name' => 'Updated Name',
        'status' => 'inactive'
    ]);
});

it('validates email uniqueness when updating a student', function () {
    $student1 = Student::factory()->create(['email' => 'student1@example.com']);
    $student2 = Student::factory()->create(['email' => 'student2@example.com']);
    
    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $this->token,
    ])->putJson("/api/students/{$student2->id}", [
        'email' => 'student1@example.com'
    ]);
    
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});

it('can delete a student', function () {
    $student = Student::factory()->create();
    
    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $this->token,
    ])->deleteJson("/api/students/{$student->id}");
    
    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'message' => 'Student deleted successfully',
            'data' => null
        ]);
    
    $this->assertDatabaseMissing('students', [
        'id' => $student->id
    ]);
});

it('returns 404 when deleting non-existent student', function () {
    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $this->token,
    ])->deleteJson('/api/students/999');
    
    $response->assertStatus(404)
        ->assertJson([
            'success' => false,
            'message' => 'Student not found'
        ]);
});

it('requires authentication for all student endpoints', function () {
    $student = Student::factory()->create();
    
    // Test without authentication
    $this->getJson('/api/students')->assertStatus(401);
    $this->postJson('/api/students', [])->assertStatus(401);
    $this->getJson("/api/students/{$student->id}")->assertStatus(401);
    $this->putJson("/api/students/{$student->id}", [])->assertStatus(401);
    $this->deleteJson("/api/students/{$student->id}")->assertStatus(401);
});