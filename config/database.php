<?php
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'ferdigym';

try {
    $conn = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Koneksi gagal: " . $e->getMessage();
    die();
}

// Zona waktu
date_default_timezone_set('Asia/Jakarta');