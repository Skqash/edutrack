<?php

/**
 * Demonstration: How Attendance Affects Final Grades
 * 
 * This script demonstrates the attendance integration with sample calculations
 */

echo "===========================================\n";
echo "ATTENDANCE IMPACT DEMONSTRATION\n";
echo "===========================================\n\n";

// Sample settings
$knowledgeWeight = 40; // 40%
$skillsWeight = 50;    // 50%
$attitudeWeight = 10;  // 10%
$attendanceWeight = 10; // 10%
$attendanceCategory = 'skills'; // Affects Skills category
$totalMeetings = 18;

echo "📊 Grade Calculation Settings:\n";
echo "   - Knowledge Weight: {$knowledgeWeight}%\n";
echo "   - Skills Weight: {$skillsWeight}%\n";
echo "   - Attitude Weight: {$attitudeWeight}%\n";
echo "   - Attendance Weight: {$attendanceWeight}%\n";
echo "   - Attendance Affects: " . ucfirst($attendanceCategory) . "\n";
echo "   - Total Meetings: {$totalMeetings}\n\n";

// Sample students with different attendance patterns
$students = [
    [
        'name' => 'Excellent Student (Perfect Attendance)',
        'knowledge' => 90,
        'skills' => 88,
        'attitude' => 92,
        'present' => 18,
        'late' => 0,
        'absent' => 0,
    ],
    [
        'name' => 'Good Student (Good Attendance)',
        'knowledge' => 85,
        'skills' => 87,
        'attitude' => 90,
        'present' => 16,
        'late' => 1,
        'absent' => 1,
    ],
    [
        'name' => 'Average Student (Average Attendance)',
        'knowledge' => 80,
        'skills' => 82,
        'attitude' => 85,
        'present' => 13,
        'late' => 2,
        'absent' => 3,
    ],
    [
        'name' => 'Struggling Student (Poor Attendance)',
        'knowledge' => 75,
        'skills' => 78,
        'attitude' => 80,
        'present' => 10,
        'late' => 1,
        'absent' => 7,
    ],
    [
        'name' => 'High Grades, Low Attendance',
        'knowledge' => 95,
        'skills' => 93,
        'attitude' => 94,
        'present' => 8,
        'late' => 2,
        'absent' => 8,
    ],
];

foreach ($students as $index => $student) {
    echo "═══════════════════════════════════════════\n";
    echo "STUDENT " . ($index + 1) . ": {$student['name']}\n";
    echo "═══════════════════════════════════════════\n\n";
    
    // Calculate attendance
    $attendanceCount = $student['present'] + $student['late'];
    $attendancePercentage = ($attendanceCount / $totalMeetings) * 100;
    $attendanceScore = ($attendanceCount / $totalMeetings) * 50 + 50;
    
    echo "📅 Attendance Record:\n";
    echo "   - Present: {$student['present']}\n";
    echo "   - Late: {$student['late']}\n";
    echo "   - Absent: {$student['absent']}\n";
    echo "   - Attendance Count: {$attendanceCount}/{$totalMeetings}\n";
    echo "   - Attendance Percentage: " . number_format($attendancePercentage, 2) . "%\n";
    echo "   - Attendance Score (x50+50): " . number_format($attendanceScore, 2) . "\n\n";
    
    // Original category averages
    $knowledgeAvg = $student['knowledge'];
    $skillsAvg = $student['skills'];
    $attitudeAvg = $student['attitude'];
    
    echo "📚 Original Category Averages:\n";
    echo "   - Knowledge: {$knowledgeAvg}\n";
    echo "   - Skills: {$skillsAvg}\n";
    echo "   - Attitude: {$attitudeAvg}\n\n";
    
    // Calculate WITHOUT attendance
    $finalGradeWithout = ($knowledgeAvg * $knowledgeWeight / 100) + 
                         ($skillsAvg * $skillsWeight / 100) + 
                         ($attitudeAvg * $attitudeWeight / 100);
    
    echo "🧮 Final Grade WITHOUT Attendance:\n";
    echo "   = (K:{$knowledgeAvg} × {$knowledgeWeight}%) + (S:{$skillsAvg} × {$skillsWeight}%) + (A:{$attitudeAvg} × {$attitudeWeight}%)\n";
    echo "   = " . number_format($finalGradeWithout, 2) . "\n\n";
    
    // Apply attendance to the specified category
    $knowledgeWithAtt = $knowledgeAvg;
    $skillsWithAtt = $skillsAvg;
    $attitudeWithAtt = $attitudeAvg;
    
    if ($attendanceCategory === 'knowledge') {
        $knowledgeWithAtt = ($knowledgeAvg * (1 - $attendanceWeight / 100)) + 
                           ($attendanceScore * ($attendanceWeight / 100));
    } elseif ($attendanceCategory === 'skills') {
        $skillsWithAtt = ($skillsAvg * (1 - $attendanceWeight / 100)) + 
                        ($attendanceScore * ($attendanceWeight / 100));
    } elseif ($attendanceCategory === 'attitude') {
        $attitudeWithAtt = ($attitudeAvg * (1 - $attendanceWeight / 100)) + 
                          ($attendanceScore * ($attendanceWeight / 100));
    }
    
    echo "📊 Category Averages WITH Attendance:\n";
    echo "   - Knowledge: " . number_format($knowledgeWithAtt, 2);
    if ($attendanceCategory === 'knowledge') {
        echo " (← attendance applied)";
    }
    echo "\n";
    
    echo "   - Skills: " . number_format($skillsWithAtt, 2);
    if ($attendanceCategory === 'skills') {
        echo " (← attendance applied)";
    }
    echo "\n";
    
    echo "   - Attitude: " . number_format($attitudeWithAtt, 2);
    if ($attendanceCategory === 'attitude') {
        echo " (← attendance applied)";
    }
    echo "\n\n";
    
    // Calculate WITH attendance
    $finalGradeWith = ($knowledgeWithAtt * $knowledgeWeight / 100) + 
                      ($skillsWithAtt * $skillsWeight / 100) + 
                      ($attitudeWithAtt * $attitudeWeight / 100);
    
    echo "🎯 Final Grade WITH Attendance:\n";
    echo "   = (K:" . number_format($knowledgeWithAtt, 2) . " × {$knowledgeWeight}%) + ";
    echo "(S:" . number_format($skillsWithAtt, 2) . " × {$skillsWeight}%) + ";
    echo "(A:" . number_format($attitudeWithAtt, 2) . " × {$attitudeWeight}%)\n";
    echo "   = " . number_format($finalGradeWith, 2) . "\n\n";
    
    // Impact analysis
    $difference = $finalGradeWith - $finalGradeWithout;
    $percentChange = ($finalGradeWithout > 0) ? ($difference / $finalGradeWithout) * 100 : 0;
    
    echo "📈 Attendance Impact:\n";
    echo "   - Grade Change: " . ($difference >= 0 ? '+' : '') . number_format($difference, 2) . " points\n";
    echo "   - Percent Change: " . ($difference >= 0 ? '+' : '') . number_format($percentChange, 2) . "%\n";
    
    if ($difference > 0) {
        echo "   - Effect: ✅ POSITIVE (attendance helped)\n";
    } elseif ($difference < 0) {
        echo "   - Effect: ⚠️  NEGATIVE (attendance hurt)\n";
    } else {
        echo "   - Effect: ➖ NEUTRAL (no change)\n";
    }
    
    echo "\n";
}

echo "═══════════════════════════════════════════\n";
echo "KEY INSIGHTS\n";
echo "═══════════════════════════════════════════\n\n";

echo "1. 📊 Attendance Formula:\n";
echo "   Attendance Score = (Attendance Count / Total Meetings) × 50 + 50\n";
echo "   - Minimum score: 50 (even with 0% attendance)\n";
echo "   - Maximum score: 100 (with 100% attendance)\n\n";

echo "2. 🎯 Category Application:\n";
echo "   New Average = (Original × 90%) + (Attendance × 10%)\n";
echo "   - Only affects the selected category ({$attendanceCategory})\n";
echo "   - Other categories remain unchanged\n\n";

echo "3. 💡 Impact Patterns:\n";
echo "   - Good attendance (>80%): Usually helps (+0.5 to +1.5 points)\n";
echo "   - Average attendance (60-80%): Minimal impact (±0.5 points)\n";
echo "   - Poor attendance (<60%): Usually hurts (-0.5 to -2.0 points)\n\n";

echo "4. ⚖️  Balancing Effect:\n";
echo "   - High grades + low attendance: Attendance pulls grade down\n";
echo "   - Low grades + high attendance: Attendance pulls grade up\n";
echo "   - Encourages both academic performance AND class participation\n\n";

echo "═══════════════════════════════════════════\n";
echo "DEMONSTRATION COMPLETE\n";
echo "═══════════════════════════════════════════\n";
