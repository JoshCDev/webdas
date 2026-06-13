<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_webdasar";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

$conn2 = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn2) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
