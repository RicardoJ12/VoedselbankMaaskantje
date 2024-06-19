<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

require "dbconfig.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fields = ['bedrijfsnaam', 'leveranciersnaam', 'adres', 'leveringsdatum', 'email'];
    $data = [];

    foreach ($fields as $field) {
        if (!isset($_POST[$field]) || empty($_POST[$field])) {
            echo "Error: $field is required.";
            exit();
        }
        $data[$field] = htmlspecialchars(strip_tags($_POST[$field]));
    }

    try {
        $stmt = $conn->prepare("INSERT INTO leveranciers (bedrijfsnaam, leveranciersnaam, adres, leveringsdatum, email) VALUES (:bedrijfsnaam, :leveranciersnaam, :adres, :leveringsdatum, :email)");
        $stmt->bindParam(':bedrijfsnaam', $data['bedrijfsnaam']);
        $stmt->bindParam(':leveranciersnaam', $data['leveranciersnaam']);
        $stmt->bindParam(':adres', $data['adres']);
        $stmt->bindParam(':leveringsdatum', $data['leveringsdatum']);
        $stmt->bindParam(':email', $data['email']);
        
        $stmt->execute();
        
        header("Location: leverancier.php");
        exit();
    } catch(PDOException $e) {
        echo "Database insert error: " . $e->getMessage();
     
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nieuwe leverancier toevoegen</title>
    <link rel="stylesheet" href="leverancierToevoegen.css">
</head>
<body>
    <h2>Nieuwe leverancier toevoegen</h2>
    <form action="leverancierToevoegen.php" method="post">
        <label for="bedrijfsnaam">Bedrijfsnaam:</label>
        <input type="text" id="bedrijfsnaam" name="bedrijfsnaam" required><br><br>
        <label for="leveranciersnaam">Naam contactpersoon:</label>
        <input type="text" id="leveranciersnaam" name="leveranciersnaam" required><br><br>
        <label for="adres">Adres:</label>
        <input type="text" id="adres" name="adres" required><br><br>
        <label for="leveringsdatum">Leveringsdatum:</label>
        <input type="date" id="leveringsdatum" name="leveringsdatum" required><br><br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>
        <input type="submit" value="Toevoegen">
    </form>
    <br>
    <a href="leverancier.php">Terug naar leveranciersoverzicht</a>
</body>
</html>
