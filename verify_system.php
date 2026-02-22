<?php
function checkGradeSystem() {
    // Check migrations
    echo "=== MIGRATION STATUS CHECK ===\n";
    if (file_exists('database/migrations/2026_02_11_000002_add_component_totals_to_grades.php')) {
        echo "[✓] Component totals migration file exists\n";
    }
    
    // Check Model
    echo "\n=== MODEL CHECK ===\n";
    if (file_exists('app/Models/Grade.php')) {
        $content = file_get_contents('app/Models/Grade.php');
        if (strpos($content, 'output_total') !== false) {
            echo "[✓] Grade model has output_total casting\n";
        }
        if (strpos($content, 'knowledge_average') !== false) {
            echo "[✓] Grade model has knowledge_average casting\n";
        }
        if (strpos($content, 'calculateComponents') !== false) {
            echo "[✓] Grade model has calculation methods\n";
        }
    }
    
    // Check Controller
    echo "\n=== CONTROLLER CHECK ===\n";
    if (file_exists('app/Http/Controllers/TeacherController.php')) {
        $content = file_get_contents('app/Http/Controllers/TeacherController.php');
        if (strpos($content, 'showGradeEntryNew') !== false) {
            echo "[✓] TeacherController has showGradeEntryNew method\n";
        }
        if (strpos($content, 'storeGradesNew') !== false) {
            echo "[✓] TeacherController has storeGradesNew method\n";
        }
        if (strpos($content, 'recalculateNewGradeScores') !== false) {
            echo "[✓] TeacherController has recalculateNewGradeScores method\n";
        }
    }
    
    // Check Routes
    echo "\n=== ROUTES CHECK ===\n";
    if (file_exists('routes/web.php')) {
        $content = file_get_contents('routes/web.php');
        if (preg_match("/'grades\/entry\/\{classId\}/", $content)) {
            echo "[✓] Grade entry route is defined\n";
        }
        if (strpos($content, 'showGradeEntryNew') !== false) {
            echo "[✓] Grade entry route mapped to showGradeEntryNew\n";
        }
    }
    
    // Check Blade Template
    echo "\n=== BLADE TEMPLATE CHECK ===\n";
    if (file_exists('resources/views/teacher/grades/entry_new.blade.php')) {
        $content = file_get_contents('resources/views/teacher/grades/entry_new.blade.php');
        // Count @endsection occurrences
        $sectionCount = substr_count($content, '@section(');
        $endSectionCount = substr_count($content, '@endsection');
        
        echo "[✓] Blade template file exists\n";
        if ($sectionCount === $endSectionCount && $sectionCount === 1) {
            echo "[✓] Blade template has matching @section/@endsection (1 pair)\n";
        } else {
            echo "[✗] Blade template has {$sectionCount} @section but {$endSectionCount} @endsection\n";
        }
        
        // Check for required elements
        if (strpos($content, 'KNOWLEDGE') !== false) {
            echo "[✓] Template has KNOWLEDGE section\n";
        }
        if (strpos($content, 'SKILLS') !== false) {
            echo "[✓] Template has SKILLS section\n";
        }
        if (strpos($content, 'ATTITUDE') !== false) {
            echo "[✓] Template has ATTITUDE section\n";
        }
        
        // Check for component totals display
        if (strpos($content, 'output_total') !== false) {
            echo "[✓] Template displays output_total\n";
        }
        if (strpos($content, 'attitude_average') !== false) {
            echo "[✓] Template displays attitude_average\n";
        }
        
        // Check file size and lines
        $lines = count(file('resources/views/teacher/grades/entry_new.blade.php'));
        echo "[✓] Template has {$lines} lines\n";
    }
    
    // Check for old/duplicate views
    echo "\n=== CLEANUP CHECK ===\n";
    $oldFiles = [
        'resources/views/teacher/grades/entry.blade.php',
        'resources/views/teacher/grades/edit.blade.php',
        'resources/views/teacher/grades/show.blade.php',
        'resources/views/teacher/grades/index.blade.php'
    ];
    
    $foundOld = false;
    foreach ($oldFiles as $file) {
        if (file_exists($file)) {
            echo "[⚠] Old file still exists: {$file}\n";
            $foundOld = true;
        }
    }
    if (!$foundOld) {
        echo "[✓] No old duplicate grade entry files found\n";
    }
}

checkGradeSystem();
