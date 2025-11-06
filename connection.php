<?php
$host = getenv('DB_HOST') ?: 'sql7.freesqldatabase.com';
$user = getenv('DB_USER') ?: 'sql7806387';
$pass = getenv('DB_PASS') ?: 'n4SpQD3y4Y';
$port = getenv('DB_PORT') ?: 3306;

$db1 = getenv('DB_NAME1') ?: 'bus_donation';
$db2 = getenv('DB_NAME2') ?: 'bus_pass';

$conn1 = new mysqli($host, $user, $pass, $db1, $port);
$conn2 = new mysqli($host, $user, $pass, $db2, $port);

if ($conn1->connect_error) {
    die("Connection failed to bus_donation: " . $conn1->connect_error);
}
if ($conn2->connect_error) {
    die("Connection failed to bus_pass: " . $conn2->connect_error);
}
?>
