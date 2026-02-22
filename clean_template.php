<?php
$file = 'resources/views/teacher/grades/entry_new.blade.php';
$content = file_get_contents($file);

// Find the first @endsection
$pos = strpos($content, '@endsection');
if ($pos !== false) {
    // Keep everything up to and including the first @endsection
    $cleaned = substr($content, 0, $pos + strlen('@endsection'));
    file_put_contents($file, $cleaned);
    echo "File cleaned! Kept first " . ($pos + strlen('@endsection')) . " characters\n";
    echo "New file size: " . strlen($cleaned) . " bytes\n";
} else {
    echo "ERROR: @endsection not found\n";
}
