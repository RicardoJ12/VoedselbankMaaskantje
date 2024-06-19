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

    $stmt = $conn->prepare("SELECT * FROM klanten");
    $stmt->execute();
    $klanten = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Klantenoverzicht</title>
    <link rel="stylesheet" href="customers.css">
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
    <h2>Klanten Overzicht</h2>
    <br>
    <div class="addcustomer">
    <a href="add_customers.php"><button class="addcustomer">Nieuwe klant toevoegen</button></a>
    </div>
    <br>
    <table>
        <tr>
            <th>Familienaam</th>
            <th>Naam klant</th>
            <th>Adres</th>
            <th>Email</th>
            <th>Telefoonnummer</th>
            <th>Acties</th>
        </tr>
        <?php foreach ($klanten as $klant): ?>
        <tr>
            <td><a href="customer_detail.php?idklanten=<?php echo htmlspecialchars($klant['idklanten']); ?>"><?php echo htmlspecialchars($klant['achternaam']); ?></a></td>
            <td><?php echo htmlspecialchars($klant['voornaam'] . ' ' . htmlspecialchars($klant['achternaam'])); ?></td>
            <td><?php echo htmlspecialchars($klant['adres']); ?></td>
            <td><?php echo htmlspecialchars($klant['email']); ?></td>
            <td><?php echo htmlspecialchars($klant['telefoon']); ?></td>
            <td>
                <a href="edit_customer.php?idklanten=<?php echo htmlspecialchars($klant['idklanten']); ?>">Edit</a>
                <a href="delete_customer.php?idklanten=<?php echo htmlspecialchars($klant['idklanten']); ?>" onclick="return confirm('Weet je zeker dat je deze klant wilt verwijderen?');">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
