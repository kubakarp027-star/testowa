<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Nieprawidłowa metoda']);
    exit;
}

$uploadDir = 'uploads/';
$oldName = $_POST['old_name'] ?? '';
$newName = $_POST['new_name'] ?? '';

if (empty($oldName) || empty($newName)) {
    echo json_encode(['success' => false, 'error' => 'Nie podano nazw plików']);
    exit;
}

// Zabezpieczenie przed ścieżkami spoza katalogu
$oldName = basename($oldName);
$newName = basename($newName);

// Sprawdź rozszerzenie
$oldExt = strtolower(pathinfo($oldName, PATHINFO_EXTENSION));
$newExt = strtolower(pathinfo($newName, PATHINFO_EXTENSION));

if (!in_array($oldExt, ['xlsx', 'xls'])) {
    echo json_encode(['success' => false, 'error' => 'Nieprawidłowe rozszerzenie pliku źródłowego']);
    exit;
}

// Jeśli nowa nazwa nie ma rozszerzenia, dodaj takie samo jak stary plik
if (empty($newExt)) {
    $newName .= '.' . $oldExt;
} else if (!in_array($newExt, ['xlsx', 'xls'])) {
    echo json_encode(['success' => false, 'error' => 'Nowy plik musi mieć rozszerzenie .xlsx lub .xls']);
    exit;
}

$oldPath = $uploadDir . $oldName;
$newPath = $uploadDir . $newName;

if (!file_exists($oldPath)) {
    echo json_encode(['success' => false, 'error' => 'Plik źródłowy nie istnieje']);
    exit;
}

if (file_exists($newPath)) {
    echo json_encode(['success' => false, 'error' => 'Plik o takiej nazwie już istnieje']);
    exit;
}

if (rename($oldPath, $newPath)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Nie można zmienić nazwy pliku']);
}
?>