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

    // Fetch customers and unassigned food packages
    $klantenStmt = $conn->prepare("SELECT idklanten, voornaam, achternaam FROM klanten");
    $klantenStmt->execute();
    $klanten = $klantenStmt->fetchAll(PDO::FETCH_ASSOC);

    $pakkettenStmt = $conn->prepare("SELECT idvoedselpakket FROM voedselpakket WHERE klanten_idklanten IS NULL");
    $pakkettenStmt->execute();
    $voedselpakketten = $pakkettenStmt->fetchAll(PDO::FETCH_ASSOC);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $klant_id = $_POST['klant_id'];
        $pakket_id = $_POST['pakket_id'];

        $updateStmt = $conn->prepare("UPDATE voedselpakket SET klanten_idklanten = :klant_id WHERE idvoedselpakket = :pakket_id");
        $updateStmt->execute(['klant_id' => $klant_id, 'pakket_id' => $pakket_id]);
        $success = "Klant succesvol gekoppeld aan voedselpakket!";
    }
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Koppel Klant aan Voedselpakket</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="navbar.css">
    <style>
        .form-container {
            max-width: 500px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .form-container h2 {
            text-align: center;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group select, .form-group button {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
        }
        .success-message {
            color: green;
            text-align: center;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
<nav>
    <a href="customers.php" class="logo-link">
        <img src="Bestek.png" alt="Logo" class="logo" />
    </a>
    <br>
    <a class="logout-button" href="logout.php">Logout</a>
    <ul class="links">
        <li><a href="customers.php">Klanten</a></li>
        <li><a href="stock.php">Voorraad</a></li>
        <li><a href="leverancier.php">Leveranciers</a></li>
        <li><a href="voedselpakket.php">Voedselpakket</a></li>
    </ul>
</nav>
<br>
<div class="form-container">
    <h2>Koppel Klant aan Voedselpakket</h2>
    <?php if (isset($success)) echo "<p class='success-message'>$success</p>"; ?>
    <form method="post">
        <div class="form-group">
            <label for="klant_id">Selecteer Klant:</label>
            <select name="klant_id" id="klant_id" required>
                <?php foreach ($klanten as $klant): ?>
                <option value="<?php echo htmlspecialchars($klant['idklanten']); ?>">
                    <?php echo htmlspecialchars($klant['voornaam'] . ' ' . $klant['achternaam']); ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="pakket_id">Selecteer Voedselpakket:</label>
            <select name="pakket_id" id="pakket_id" required>
                <?php foreach ($voedselpakketten as $pakket): ?>
                <option value="<?php echo htmlspecialchars($pakket['idvoedselpakket']); ?>">
                    Pakket ID: <?php echo htmlspecialchars($pakket['idvoedselpakket']); ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <button type="submit">Koppel Klant aan Voedselpakket</button>
        </div>
    </form>
</div>

</body>
</html>
