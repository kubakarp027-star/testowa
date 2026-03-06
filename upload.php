<?php
header('Content-Type: application/json');

// Katalog na pliki
$uploadDir = 'uploads/';

// Utwórz katalog jeśli nie istnieje
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Sprawdź czy plik został wysłany
if (!isset($_FILES['file'])) {
    echo json_encode(['success' => false, 'error' => 'Nie przesłano pliku']);
    exit;
}

$file = $_FILES['file'];

// Sprawdź czy nie ma błędów
if ($file['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'error' => 'Błąd podczas wgrywania pliku']);
    exit;
}

// Sprawdź typ pliku
$fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
if (!in_array($fileExt, ['xlsx', 'xls'])) {
    echo json_encode(['success' => false, 'error' => 'Dozwolone są tylko pliki Excel (.xlsx, .xls)']);
    exit;
}

// Generuj bezpieczną nazwę pliku
$fileName = basename($file['name']);
$fileName = preg_replace('/[^a-zA-Z0-9ąćęłńóśźżĄĆĘŁŃÓŚŹŻ.\-_]/u', '_', $fileName);
$filePath = $uploadDir . $fileName;

// Sprawdź czy plik już istnieje
if (file_exists($filePath)) {
    // Dodaj timestamp do nazwy
    $pathInfo = pathinfo($fileName);
    $fileName = $pathInfo['filename'] . '_' . time() . '.' . $pathInfo['extension'];
    $filePath = $uploadDir . $fileName;
}

// Przenieś plik
if (move_uploaded_file($file['tmp_name'], $filePath)) {
    echo json_encode(['success' => true, 'filename' => $fileName]);
} else {
    echo json_encode(['success' => false, 'error' => 'Nie można zapisać pliku']);
}
?>