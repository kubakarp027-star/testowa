<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Nieprawidłowa metoda']);
    exit;
}

$uploadDir = 'uploads/';
$filename = $_POST['filename'] ?? '';

if (empty($filename)) {
    echo json_encode(['success' => false, 'error' => 'Nie podano nazwy pliku']);
    exit;
}

// Zabezpieczenie przed ścieżkami spoza katalogu
$filename = basename($filename);
$filepath = $uploadDir . $filename;

if (!file_exists($filepath)) {
    echo json_encode(['success' => false, 'error' => 'Plik nie istnieje']);
    exit;
}

if (unlink($filepath)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Nie można usunąć pliku']);
}
?>