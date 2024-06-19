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

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $idklanten = $_POST['idklanten'];
        $achternaam = $_POST['achternaam'];
        $voornaam = $_POST['voornaam'];
        $adres = $_POST['adres'];
        $email = $_POST['email'];
        $telefoon = $_POST['telefoon'];

        $stmt = $conn->prepare("UPDATE klanten SET achternaam=:achternaam, voornaam=:voornaam, adres=:adres, email=:email, telefoon=:telefoon WHERE idklanten=:idklanten");
        $stmt->bindParam(':achternaam', $achternaam);
        $stmt->bindParam(':voornaam', $voornaam);
        $stmt->bindParam(':adres', $adres);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':telefoon', $telefoon);
        $stmt->bindParam(':idklanten', $idklanten);
        $stmt->execute();

        header("Location: customers.php");
        exit();
    } else {
        if (isset($_GET['idklanten'])) {
            $idklanten = $_GET['idklanten'];

            $stmt = $conn->prepare("SELECT * FROM klanten WHERE idklanten=:idklanten");
            $stmt->bindParam(':idklanten', $idklanten);
            $stmt->execute();
            $klant = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$klant) {
                throw new Exception("Klant niet gevonden.");
            }
        } else {
            throw new Exception("Geen klant ID opgegeven.");
        }
    }
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
} catch(Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Klant</title>
</head>
<body>
    <h2>Edit Klant</h2>
    <form method="post" action="edit_customer.php">
        <input type="hidden" name="idklanten" value="<?php echo htmlspecialchars($klant['idklanten']); ?>">
        <label for="achternaam">Achternaam:</label>
        <input type="text" id="achternaam" name="achternaam" value="<?php echo htmlspecialchars($klant['achternaam']); ?>"><br>
        <label for="voornaam">Voornaam:</label>
        <input type="text" id="voornaam" name="voornaam" value="<?php echo htmlspecialchars($klant['voornaam']); ?>"><br>
        <label for="adres">Adres:</label>
        <input type="text" id="adres" name="adres" value="<?php echo htmlspecialchars($klant['adres']); ?>"><br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($klant['email']); ?>"><br>
        <label for="telefoon">Telefoon:</label>
        <input type="text" id="telefoon" name="telefoon" value="<?php echo htmlspecialchars($klant['telefoon']); ?>"><br>
        <input type="submit" value="Update">
    </form>
</body>
</html>
