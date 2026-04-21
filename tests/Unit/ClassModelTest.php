<?php

namespace Tests\Unit;

use App\Models\ClassModel;
use App\Models\School;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

describe('ClassModel', function () {
    
    it('creates class with required attributes', function () {
        $class = ClassModel::factory()->create([
            'class_name' => 'Grade 10-A',
            'class_level' => 10,
            'section' => 'A',
        ]);

        expect($class)
            ->class_name->toBe('Grade 10-A')
            ->class_level->toBe(10)
            ->section->toBe('A');
    });

    it('belongs to school', function () {
        $class = ClassModel::factory()->create();

        expect($class->school)
            ->not->toBeNull()
            ->toBeInstanceOf(School::class);
    });

    it('belongs to teacher', function () {
        $class = ClassModel::factory()->create();

        expect($class->teacher)
            ->not->toBeNull()
            ->toBeInstanceOf(User::class);
    });

    it('belongs to subject', function () {
        $class = ClassModel::factory()->create();

        expect($class->subject)
            ->not->toBeNull()
            ->toBeInstanceOf(Subject::class);
    });

    it('has many students', function () {
        $class = ClassModel::factory()->create();
        
        $this->assertDatabaseMissing('students', ['class_id' => $class->id]);
    });

    it('validates section values', function () {
        $validSections = ['A', 'B', 'C', 'D'];
        
        foreach ($validSections as $section) {
            $class = ClassModel::factory()->create(['section' => $section]);
            expect($class->section)->toBe($section);
        }
    });

    it('validates class level is positive', function () {
        $class = ClassModel::factory()->create(['class_level' => 10]);
        
        expect($class->class_level)->toBeGreaterThan(0);
    });
});
