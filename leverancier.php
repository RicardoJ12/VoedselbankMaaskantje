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

$leveranciers = [];

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("SELECT * FROM leveranciers");
    $stmt->execute();
    $leveranciers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    echo "Verbinding mislukt: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Leveranciers overzicht</title>
    <link rel="stylesheet" href="leverancier.css">
    <link rel="stylesheet" href="navbar.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 15px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .actions {
            display: flex;
            gap: 10px;
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
<h2>Leveranciers Overzicht</h2>
<br>
<div class="addsupplier">
    <a href="leverancierToevoegen.php"><button class="addsupplier">Nieuwe leverancier toevoegen</button></a>
</div>
<br>
<table>
    <tr>
        <th>Bedrijfsnaam</th>
        <th>Naam contactpersoon</th>
        <th>Leveringsdatum</th>
        <th>Adres</th>
        <th>Email</th>
        <th>Acties</th>
    </tr>
    <?php if (!empty($leveranciers)): ?>
        <?php foreach ($leveranciers as $leverancier): ?>
        <tr>
            <td><?php echo htmlspecialchars($leverancier['bedrijfsnaam']); ?></td>
            <td><?php echo htmlspecialchars($leverancier['leveranciersnaam']); ?></td>
            <td><?php echo htmlspecialchars($leverancier['leveringsdatum']); ?></td>
            <td><?php echo htmlspecialchars($leverancier['adres']); ?></td>
            <td><?php echo htmlspecialchars($leverancier['email']); ?></td>
            <td class="actions">
                <a href="edit_leverancier.php?id=<?php echo htmlspecialchars($leverancier['idleveranciers']); ?>"><button>Edit</button></a>
                <a href="delete_leverancier.php?id=<?php echo htmlspecialchars($leverancier['idleveranciers']); ?>" onclick="return confirm('Weet je zeker dat je deze leverancier wilt verwijderen?');"><button>Delete</button></a>
            </td>
        </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="7">Geen leveranciers gevonden.</td>
        </tr>
    <?php endif; ?>
</table>
</body>
</html>
