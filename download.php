<?php
$uploadDir = 'uploads/';
$filename = $_GET['file'] ?? '';

if (empty($filename)) {
    die('Nie podano nazwy pliku');
}

// Zabezpieczenie przed ścieżkami spoza katalogu
$filename = basename($filename);
$filepath = $uploadDir . $filename;

if (!file_exists($filepath)) {
    die('Plik nie istnieje');
}

// Sprawdź rozszerzenie
$ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
if (!in_array($ext, ['xlsx', 'xls'])) {
    die('Nieprawidłowy typ pliku');
}

// Ustaw nagłówki do pobierania
header('Content-Description: File Transfer');
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($filepath));

// Wyczyść bufor wyjściowy
ob_clean();
flush();

// Wyślij plik
readfile($filepath);
exit;
?>