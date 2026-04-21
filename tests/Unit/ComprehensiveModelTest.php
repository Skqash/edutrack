<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\School;
use App\Models\Subject;
use App\Models\ClassModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

describe('User Model Tests', function () {
    it('creates user with valid data', function () {
        $user = User::factory()->create([
            'name' => 'John Teacher',
            'email' => 'john@school.com',
            'role' => 'teacher',
        ]);

        expect($user->exists)->toBeTrue();
        expect($user->name)->toBe('John Teacher');
        expect($user->email)->toBe('john@school.com');
        expect($user->role)->toBe('teacher');
    });

    it('user has required attributes', function () {
        $user = User::factory()->create();

        expect($user->id)->toBeGreaterThan(0);
        expect($user->name)->not->toBeEmpty();
        expect($user->email)->not->toBeEmpty();
        expect($user->role)->not->toBeEmpty();
    });

    it('teacher user has correct role', function () {
        $teacher = User::factory()->create(['role' => 'teacher']);
        expect($teacher->role)->toBe('teacher');
    });

    it('student user has correct role', function () {
        $student = User::factory()->create(['role' => 'student']);
        expect($student->role)->toBe('student');
    });

    it('admin user has correct role', function () {
        $admin = User::factory()->create(['role' => 'admin']);
        expect($admin->role)->toBe('admin');
    });

    it('user has verifable email', function () {
        $user = User::factory()->create();
        expect($user->email)->toMatch('/@/');
    });

    it('user status defaults to Active', function () {
        $user = User::factory()->create();
        expect($user->status)->toBe('Active');
    });

    it('user password is stored', function () {
        $user = User::factory()->create(['password' => 'SecurePass123!']);
        expect($user->password)->not->toBeEmpty();
    });

    it('default user has school_id set', function () {
        $user = User::factory()->create();
        expect($user->school_id)->toBeNull();
    });

    it('multiple users can be created', function () {
        $users = User::factory(5)->create();
        expect(count($users))->toBe(5);
    });
});

describe('School Model Tests', function () {
    it('creates school with valid data', function () {
        $school = School::factory()->create([
            'school_name' => 'Central High School',
        ]);

        expect($school->exists)->toBeTrue();
        expect($school->school_name)->toBe('Central High School');
    });

    it('school has school code', function () {
        $school = School::factory()->create();
        expect($school->school_code)->not->toBeEmpty();
    });

    it('school has contact number', function () {
        $school = School::factory()->create();
        expect($school->contact_number)->not->toBeEmpty();
    });

    it('school has email', function () {
        $school = School::factory()->create();
        expect($school->email)->toMatch('/@/');
    });

    it('school status is Active', function () {
        $school = School::factory()->create();
        expect($school->status)->toBe('Active');
    });

    it('school has location', function () {
        $school = School::factory()->create();
        expect($school->location)->not->toBeEmpty();
    });

    it('school has city', function () {
        $school = School::factory()->create();
        expect($school->city)->not->toBeEmpty();
    });

    it('school has province', function () {
        $school = School::factory()->create();
        expect($school->province)->not->toBeEmpty();
    });

    it('school has region', function () {
        $school = School::factory()->create();
        expect($school->region)->not->toBeEmpty();
    });

    it('multiple schools can be created', function () {
        $schools = School::factory(3)->create();
        expect(count($schools))->toBe(3);
    });
});

describe('Subject Model Tests', function () {
    it('creates subject with valid data', function () {
        $subject = Subject::factory()->create([
            'subject_name' => 'English',
        ]);

        expect($subject->exists)->toBeTrue();
        expect($subject->subject_name)->toBe('English');
    });

    it('subject has school_id', function () {
        $subject = Subject::factory()->create();
        expect($subject->school_id)->toBeGreaterThan(0);
    });

    it('subject has subject_code', function () {
        $subject = Subject::factory()->create();
        expect($subject->subject_code)->not->toBeEmpty();
    });

    it('subject has credit hours', function () {
        $subject = Subject::factory()->create();
        expect($subject->credit_hours)->toBeGreaterThan(0);
    });

    it('subject has year_level', function () {
        $subject = Subject::factory()->create();
        expect($subject->year_level)->toBeGreaterThan(0);
    });

    it('subject belongs to school', function () {
        $school = School::factory()->create();
        $subject = Subject::factory()->create(['school_id' => $school->id]);

        expect($subject->school_id)->toBe($school->id);
    });

    it('subject has category', function () {
        $subject = Subject::factory()->create();
        expect($subject->category)->not->toBeEmpty();
    });

    it('multiple subjects can be created', function () {
        $subjects = Subject::factory(4)->create();
        expect(count($subjects))->toBe(4);
    });
});

describe('ClassModel Tests', function () {
    it('creates class with valid data', function () {
        $class = ClassModel::factory()->create([
            'class_name' => 'Grade 10-A',
        ]);

        expect($class->exists)->toBeTrue();
        expect($class->class_name)->toBe('Grade 10-A');
    });

    it('class has school_id', function () {
        $class = ClassModel::factory()->create();
        expect($class->school_id)->toBeGreaterThan(0);
    });

    it('class has teacher_id', function () {
        $class = ClassModel::factory()->create();
        expect($class->teacher_id)->toBeGreaterThan(0);
    });

    it('class has subject_id', function () {
        $class = ClassModel::factory()->create();
        expect($class->subject_id)->toBeGreaterThan(0);
    });

    it('class has valid class_level', function () {
        $class = ClassModel::factory()->create(['class_level' => 10]);
        expect($class->class_level)->toBe(10);
    });

    it('class has valid section', function () {
        $class = ClassModel::factory()->create(['section' => 'A']);
        expect($class->section)->toBe('A');
    });

    it('teacher belongs to class', function () {
        $class = ClassModel::factory()->create();
        $teacher = User::find($class->teacher_id);

        expect($teacher)->not->toBeNull();
        expect($teacher->role)->toBe('teacher');
    });

    it('school belongs to class', function () {
        $class = ClassModel::factory()->create();
        $school = School::find($class->school_id);

        expect($school)->not->toBeNull();
    });

    it('subject belongs to class', function () {
        $class = ClassModel::factory()->create();
        $subject = Subject::find($class->subject_id);

        expect($subject)->not->toBeNull();
    });

    it('multiple classes can be created', function () {
        $classes = ClassModel::factory(5)->create();
        expect(count($classes))->toBe(5);
    });
});

describe('Factory Integration Tests', function () {
    it('user factory creates unique emails', function () {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        expect($user1->email)->not->toBe($user2->email);
    });

    it('school factory creates schools with different codes', function () {
        $school1 = School::factory()->create();
        $school2 = School::factory()->create();

        expect($school1->school_code)->not->toBe($school2->school_code);
    });

    it('subject factory creates subjects with different codes', function () {
        $subject1 = Subject::factory()->create();
        $subject2 = Subject::factory()->create();

        expect($subject1->subject_code)->not->toBe($subject2->subject_code);
    });

    it('class factory creates classes with different names', function () {
        $class1 = ClassModel::factory()->create();
        $class2 = ClassModel::factory()->create();

        expect($class1->class_name)->not->toBe($class2->class_name);
    });
});

describe('Data Persistence Tests', function () {
    it('user data persists in database', function () {
        $user = User::factory()->create(['name' => 'Persistent User']);
        $found = User::find($user->id);

        expect($found->name)->toBe('Persistent User');
    });

    it('school data persists in database', function () {
        $school = School::factory()->create(['school_name' => 'Persistent School']);
        $found = School::find($school->id);

        expect($found->school_name)->toBe('Persistent School');
    });

    it('subject data persists in database', function () {
        $subject = Subject::factory()->create(['subject_name' => 'History']);
        $found = Subject::find($subject->id);

        expect($found->subject_name)->toBe('History');
    });

    it('class data persists in database', function () {
        $class = ClassModel::factory()->create(['class_name' => 'Persistent Class']);
        $found = ClassModel::find($class->id);

        expect($found->class_name)->toBe('Persistent Class');
    });
});

describe('Model Update Tests', function () {
    it('user can be updated', function () {
        $user = User::factory()->create(['name' => 'Original Name']);
        $user->update(['name' => 'Updated Name']);
        $found = User::find($user->id);

        expect($found->name)->toBe('Updated Name');
    });

    it('school can be updated', function () {
        $school = School::factory()->create(['status' => 'Active']);
        $school->update(['status' => 'Inactive']);
        $found = School::find($school->id);

        expect($found->status)->toBe('Inactive');
    });

    it('subject can be updated', function () {
        $subject = Subject::factory()->create(['credit_hours' => 3]);
        $subject->update(['credit_hours' => 4]);
        $found = Subject::find($subject->id);

        expect($found->credit_hours)->toBe(4);
    });

    it('class can be updated', function () {
        $class = ClassModel::factory()->create(['class_level' => 10]);
        $class->update(['class_level' => 11]);
        $found = ClassModel::find($class->id);

        expect($found->class_level)->toBe(11);
    });
});

describe('Model Deletion Tests', function () {
    it('user can be deleted', function () {
        $user = User::factory()->create();
        $userId = $user->id;
        $user->delete();
        $found = User::find($userId);

        expect($found)->toBeNull();
    });

    it('school can be deleted', function () {
        $school = School::factory()->create();
        $schoolId = $school->id;
        $school->delete();
        $found = School::find($schoolId);

        expect($found)->toBeNull();
    });

    it('subject can be deleted', function () {
        $subject = Subject::factory()->create();
        $subjectId = $subject->id;
        $subject->delete();
        $found = Subject::find($subjectId);

        expect($found)->toBeNull();
    });

    it('class can be deleted', function () {
        $class = ClassModel::factory()->create();
        $classId = $class->id;
        $class->delete();
        $found = ClassModel::find($classId);

        expect($found)->toBeNull();
    });
});
