<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

require "dbconfig.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fields = ['voornaam', 'achternaam', 'adres', 'telefoon', 'email', 'volwassen', 'kinderen', 'babys', 'opmerkingen', 'specifiekeWensen'];
    $data = [];

    foreach ($fields as $field) {
        if (!isset($_POST[$field]) || empty($_POST[$field])) {
            echo "Error: $field is required.";
            exit();
        }
        $data[$field] = htmlspecialchars(strip_tags($_POST[$field]));
    }

    try {
        $stmt = $conn->prepare("INSERT INTO klanten (voornaam, achternaam, adres, telefoon, email, volwassen, kinderen, babys, opmerkingen, specifiekewensen) VALUES (:voornaam, :achternaam, :adres, :telefoon, :email, :volwassen, :kinderen, :babys, :opmerkingen, :specifiekeWensen)");
        $stmt->bindParam(':voornaam', $data['voornaam']);
        $stmt->bindParam(':achternaam', $data['achternaam']);
        $stmt->bindParam(':adres', $data['adres']);
        $stmt->bindParam(':telefoon', $data['telefoon']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':volwassen', $data['volwassen']);
        $stmt->bindParam(':kinderen', $data['kinderen']);
        $stmt->bindParam(':babys', $data['babys']);
        $stmt->bindParam(':opmerkingen', $data['opmerkingen']);
        $stmt->bindParam(':specifiekeWensen', $data['specifiekeWensen']);
        
        $stmt->execute();
        
        // Redirect to customers page after successful insertion
        header("Location: customers.php");
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
    <title>Nieuwe klant toevoegen</title>
    <link rel="stylesheet" href="add_customers.css">
</head>
<body>
    <h2>Nieuwe klant toevoegen</h2>
    <form action="add_customers.php" method="post">
        <label for="voornaam">Voornaam:</label>
        <input type="text" id="voornaam" name="voornaam" required><br><br>
        <label for="achternaam">Achternaam:</label>
        <input type="text" id="achternaam" name="achternaam" required><br><br>
        <label for="adres">Adres:</label>
        <input type="text" id="adres" name="adres" required><br><br>
        <label for="telefoon">Telefoonnummer:</label>
        <input type="text" id="telefoon" name="telefoon" required><br><br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>
        <label for="volwassen">Aantal volwassenen:</label>
        <input type="number" id="volwassen" name="volwassen" required><br><br>
        <label for="kinderen">Aantal kinderen:</label>
        <input type="number" id="kinderen" name="kinderen" required><br><br>
        <label for="babys">Aantal baby's:</label>
        <input type="number" id="babys" name="babys" required><br><br>
        <label for="opmerkingen">Opmerkingen:</label>
        <textarea id="opmerkingen" name="opmerkingen"></textarea><br><br>
        <label for="specifiekeWensen">Specifieke wensen:</label>
        <textarea id="specifiekeWensen" name="specifiekeWensen"></textarea><br><br>
        <input type="submit" value="Toevoegen">
    </form>
    <br>
    <a href="customers.php">Terug naar klantenoverzicht</a>
</body>
</html>
