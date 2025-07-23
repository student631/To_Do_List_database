<?php
$host = 'localhost';
$user = 'root';         // apne DB user ke hisaab se
$pass = '';             // apne DB password ke hisaab se
$dbname = 'shopping_db';

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}
?>
