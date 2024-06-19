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

if (isset($_GET['idklanten'])) {
    $idklanten = $_GET['idklanten'];

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Haal klantinformatie op
        $stmt = $conn->prepare("SELECT * FROM klanten WHERE idklanten = :idklanten");
        $stmt->bindParam(':idklanten', $idklanten);
        $stmt->execute();
        $klant = $stmt->fetch(PDO::FETCH_ASSOC);

        // Voeg voedselpakket toe
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $aanmaakdatum = $_POST['aanmaakdatum'];
            $stmt = $conn->prepare("INSERT INTO voedselpakket (klanten_idklanten, aanmaakdatum) VALUES (:klanten_idklanten, :aanmaakdatum)");
            $stmt->bindParam(':klanten_idklanten', $idklanten);
            $stmt->bindParam(':aanmaakdatum', $aanmaakdatum);
            $stmt->execute();

            header("Location: customer_detail.php?idklanten=" . $idklanten);
            exit();
        }
    } catch(PDOException $e) {
        echo "Verbinding mislukt: " . $e->getMessage();
    }
} else {
    header("Location: customers.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Klant Detail</title>
    <link rel="stylesheet" href="customers.css">
</head>
<body>
<h2>Klant Details</h2>
<p><strong>Familienaam:</strong> <?php echo htmlspecialchars($klant['achternaam']); ?></p>
<p><strong>Voornaam:</strong> <?php echo htmlspecialchars($klant['voornaam']); ?></p>
<p><strong>Adres:</strong> <?php echo htmlspecialchars($klant['adres']); ?></p>
<p><strong>Email:</strong> <?php echo htmlspecialchars($klant['email']); ?></p>
<p><strong>Telefoonnummer:</strong> <?php echo htmlspecialchars($klant['telefoon']); ?></p>
<p><strong>Volwassenen:</strong> <?php echo htmlspecialchars($klant['volwassen']); ?></p>
<p><strong>Kinderen:</strong> <?php echo htmlspecialchars($klant['kinderen']); ?></p>
<p><strong>Baby's:</strong> <?php echo htmlspecialchars($klant['babys']); ?></p>
<p><strong>Opmerkingen:</strong> <?php echo htmlspecialchars($klant['opmerkingen']); ?></p>
<p><strong>Specifieke Wensen:</strong> <?php echo htmlspecialchars($klant['specifiekewensen']); ?></p>

<h3>Voedselpakket Aanmaken</h3>
<form method="post">
    <label for="aanmaakdatum">Aanmaakdatum:</label>
    <input type="date" id="aanmaakdatum" name="aanmaakdatum" required><br>
    <input type="submit" value="Aanmaken">
</form>
</body>
</html>
