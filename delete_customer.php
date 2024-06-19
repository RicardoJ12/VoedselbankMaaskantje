<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mydb";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_GET['idklanten'])) {
        $idklanten = $_GET['idklanten'];

        $stmt = $conn->prepare("DELETE FROM klanten WHERE idklanten=:idklanten");
        $stmt->bindParam(':idklanten', $idklanten);
        $stmt->execute();

        header("Location: customers.php");
        exit();
    }
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
