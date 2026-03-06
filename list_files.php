<?php
header('Content-Type: application/json');

$uploadDir = 'uploads/';

if (!file_exists($uploadDir)) {
    echo json_encode(['success' => true, 'files' => []]);
    exit;
}

$files = [];
$iterator = new FilesystemIterator($uploadDir);

foreach ($iterator as $file) {
    if ($file->isFile() && in_array(strtolower($file->getExtension()), ['xlsx', 'xls'])) {
        $files[] = [
            'name' => $file->getFilename(),
            'size' => $file->getSize(),
            'modified' => $file->getMTime()
        ];
    }
}

// Sortuj według daty modyfikacji (najnowsze pierwsze)
usort($files, function($a, $b) {
    return $b['modified'] - $a['modified'];
});

echo json_encode(['success' => true, 'files' => $files]);
?>