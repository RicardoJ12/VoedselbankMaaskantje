<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idvoedselpakket = $_POST['idvoedselpakket'];
    $datumafgifte = $_POST['datumafgifte'];

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "mydb";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Query om datum afgifte bij te werken
        $stmt = $conn->prepare("UPDATE voedselpakket SET datumafgifte = :datumafgifte WHERE idvoedselpakket = :idvoedselpakket");
        $stmt->bindParam(':datumafgifte', $datumafgifte);
        $stmt->bindParam(':idvoedselpakket', $idvoedselpakket);
        $stmt->execute();

        header("Location: voedselpakket.php");
        exit();
    } catch(PDOException $e) {
        echo "Connection failed: " . htmlspecialchars($e->getMessage());
    }
} else {
    header("Location: voedselpakket.php");
    exit();
}
?>
