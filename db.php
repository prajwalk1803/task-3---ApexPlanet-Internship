<?php
// db.php - change these values to match your environment
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'task3_db';

$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($mysqli->connect_errno) {
    die('DB Connection failed: ' . $mysqli->connect_error);
}
// set charset
$mysqli->set_charset('utf8mb4');
?>