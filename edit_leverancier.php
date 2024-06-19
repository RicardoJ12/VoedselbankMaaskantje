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

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $bedrijfsnaam = $_POST['bedrijfsnaam'];
            $leveranciersnaam = $_POST['leveranciersnaam'];
            $leveringsdatum = $_POST['leveringsdatum'];
            $adres = $_POST['adres'];
            $email = $_POST['email'];
            $leverancierscol = $_POST['leverancierscol'];

            $stmt = $conn->prepare("UPDATE leveranciers SET bedrijfsnaam = :bedrijfsnaam, leveranciersnaam = :leveranciersnaam, leveringsdatum = :leveringsdatum, adres = :adres, email = :email, leverancierscol = :leverancierscol WHERE idleveranciers = :id");
            $stmt->bindParam(':bedrijfsnaam', $bedrijfsnaam);
            $stmt->bindParam(':leveranciersnaam', $leveranciersnaam);
            $stmt->bindParam(':leveringsdatum', $leveringsdatum);
            $stmt->bindParam(':adres', $adres);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':leverancierscol', $leverancierscol);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            header("Location: leverancier.php");
            exit();
        } else {
            $stmt = $conn->prepare("SELECT * FROM leveranciers WHERE idleveranciers = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $leverancier = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$leverancier) {
                header("Location: leverancier.php");
                exit();
            }
        }
    } catch(PDOException $e) {
        echo "Verbinding mislukt: " . $e->getMessage();
    }
} else {
    header("Location: leverancier.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Leverancier Bewerken</title>
    <link rel="stylesheet" href="leverancier.css">
</head>
<body>
<h2>Leverancier Bewerken</h2>
<form method="post">
    <label for="bedrijfsnaam">Bedrijfsnaam:</label>
    <input type="text" id="bedrijfsnaam" name="bedrijfsnaam" value="<?php echo htmlspecialchars($leverancier['bedrijfsnaam']); ?>" required><br>

    <label for="leveranciersnaam">Naam contactpersoon:</label>
    <input type="text" id="leveranciersnaam" name="leveranciersnaam" value="<?php echo htmlspecialchars($leverancier['leveranciersnaam']); ?>" required><br>

    <label for="leveringsdatum">Leveringsdatum:</label>
    <input type="date" id="leveringsdatum" name="leveringsdatum" value="<?php echo htmlspecialchars($leverancier['leveringsdatum']); ?>" required><br>

    <label for="adres">Adres:</label>
    <input type="text" id="adres" name="adres" value="<?php echo htmlspecialchars($leverancier['adres']); ?>" required><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($leverancier['email']); ?>" required><br>

    <label for="leverancierscol">Extra informatie:</label>
    <textarea id="leverancierscol" name="leverancierscol" required><?php echo htmlspecialchars($leverancier['leverancierscol']); ?></textarea><br>

    <input type="submit" value="Opslaan">
</form>

</body>
</html>
