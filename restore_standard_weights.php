<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\AssessmentComponent;

echo "=== RESTORING STANDARD KSA WEIGHTS ===\n\n";

// Knowledge: Exam 60%, Quizzes 40%
echo "--- Knowledge ---\n";
$exam = AssessmentComponent::where('category', 'Knowledge')
    ->where('subcategory', 'Exam')
    ->where('is_active', true)
    ->first();
if ($exam) {
    $exam->update(['weight' => 60]);
    echo "Exam: 60%\n";
}

$quizzes = AssessmentComponent::where('category', 'Knowledge')
    ->where('subcategory', 'Quiz')
    ->where('is_active', true)
    ->get();
if ($quizzes->count() > 0) {
    $weightPerQuiz = round(40 / $quizzes->count(), 2);
    $remainder = 40 - ($weightPerQuiz * $quizzes->count());
    
    foreach ($quizzes as $index => $quiz) {
        $weight = $weightPerQuiz;
        if ($index == $quizzes->count() - 1) {
            $weight += $remainder; // Add remainder to last quiz
        }
        $quiz->update(['weight' => $weight]);
        echo "{$quiz->name}: {$weight}%\n";
    }
}

// Skills: Outputs 40%, Participation 30%, Activities 15%, Assignments 15%
echo "\n--- Skills ---\n";

$outputs = AssessmentComponent::where('category', 'Skills')
    ->where('subcategory', 'Output')
    ->where('is_active', true)
    ->get();
if ($outputs->count() > 0) {
    $weightPerOutput = round(40 / $outputs->count(), 2);
    $remainder = 40 - ($weightPerOutput * $outputs->count());
    
    foreach ($outputs as $index => $output) {
        $weight = $weightPerOutput;
        if ($index == $outputs->count() - 1) {
            $weight += $remainder;
        }
        $output->update(['weight' => $weight]);
        echo "{$output->name}: {$weight}%\n";
    }
}

$participations = AssessmentComponent::where('category', 'Skills')
    ->where('subcategory', 'Participation')
    ->where('is_active', true)
    ->get();
if ($participations->count() > 0) {
    $weightPerCP = round(30 / $participations->count(), 2);
    $remainder = 30 - ($weightPerCP * $participations->count());
    
    foreach ($participations as $index => $cp) {
        $weight = $weightPerCP;
        if ($index == $participations->count() - 1) {
            $weight += $remainder;
        }
        $cp->update(['weight' => $weight]);
        echo "{$cp->name}: {$weight}%\n";
    }
}

$activities = AssessmentComponent::where('category', 'Skills')
    ->where('subcategory', 'Activity')
    ->where('is_active', true)
    ->get();
if ($activities->count() > 0) {
    $weightPerActivity = round(15 / $activities->count(), 2);
    $remainder = 15 - ($weightPerActivity * $activities->count());
    
    foreach ($activities as $index => $activity) {
        $weight = $weightPerActivity;
        if ($index == $activities->count() - 1) {
            $weight += $remainder;
        }
        $activity->update(['weight' => $weight]);
        echo "{$activity->name}: {$weight}%\n";
    }
}

$assignments = AssessmentComponent::where('category', 'Skills')
    ->where('subcategory', 'Assignment')
    ->where('is_active', true)
    ->get();
if ($assignments->count() > 0) {
    $weightPerAssignment = round(15 / $assignments->count(), 2);
    $remainder = 15 - ($weightPerAssignment * $assignments->count());
    
    foreach ($assignments as $index => $assignment) {
        $weight = $weightPerAssignment;
        if ($index == $assignments->count() - 1) {
            $weight += $remainder;
        }
        $assignment->update(['weight' => $weight]);
        echo "{$assignment->name}: {$weight}%\n";
    }
}

// Attitude: Behavior 50%, Awareness 50%
echo "\n--- Attitude ---\n";

$behaviors = AssessmentComponent::where('category', 'Attitude')
    ->where('subcategory', 'Behavior')
    ->where('is_active', true)
    ->get();
if ($behaviors->count() > 0) {
    $weightPerBehavior = round(50 / $behaviors->count(), 2);
    $remainder = 50 - ($weightPerBehavior * $behaviors->count());
    
    foreach ($behaviors as $index => $behavior) {
        $weight = $weightPerBehavior;
        if ($index == $behaviors->count() - 1) {
            $weight += $remainder;
        }
        $behavior->update(['weight' => $weight]);
        echo "{$behavior->name}: {$weight}%\n";
    }
}

$awarenesses = AssessmentComponent::where('category', 'Attitude')
    ->where('subcategory', 'Awareness')
    ->where('is_active', true)
    ->get();
if ($awarenesses->count() > 0) {
    $weightPerAwareness = round(50 / $awarenesses->count(), 2);
    $remainder = 50 - ($weightPerAwareness * $awarenesses->count());
    
    foreach ($awarenesses as $index => $awareness) {
        $weight = $weightPerAwareness;
        if ($index == $awarenesses->count() - 1) {
            $weight += $remainder;
        }
        $awareness->update(['weight' => $weight]);
        echo "{$awareness->name}: {$weight}%\n";
    }
}

echo "\n=== VERIFICATION ===\n\n";

$categories = ['Knowledge', 'Skills', 'Attitude'];
foreach ($categories as $category) {
    $total = AssessmentComponent::where('category', $category)
        ->where('is_active', true)
        ->sum('weight');
    
    $status = ($total == 100) ? '✅' : '❌';
    echo "{$category}: {$total}% {$status}\n";
}

echo "\n✅ Standard weights restored!\n";
