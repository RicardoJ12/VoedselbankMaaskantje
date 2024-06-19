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

$voedselpakketten = []; // Initialize the variable

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Query om voedselpakketten op te halen met klantachternaam en producten
    $stmt = $conn->prepare("SELECT vp.idvoedselpakket, vp.datumafgifte, vp.aanmaakdatum, k.achternaam AS klant_achternaam, 
                                   GROUP_CONCAT(CONCAT(p.naamproduct, ' (', phv.aantal, ')') SEPARATOR ', ') AS producten
                            FROM voedselpakket vp 
                            INNER JOIN klanten k ON vp.klanten_idklanten = k.idklanten
                            LEFT JOIN product_has_voedselpakket phv ON vp.idvoedselpakket = phv.voedselpakket_idvoedselpakket
                            LEFT JOIN product p ON phv.product_idproduct = p.idproduct
                            GROUP BY vp.idvoedselpakket, vp.datumafgifte, vp.aanmaakdatum, k.achternaam");
    $stmt->execute();
    $voedselpakketten = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    echo "Connection failed: " . htmlspecialchars($e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Voedselpakket Overzicht</title>
    <link rel="stylesheet" href="styles.css">
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
<h2>Voedselpakket Overzicht</h2>
<br>
<div class="addpackage">
    <a href="voedselpakket_klanten.php"><button class="addpackage">Nieuw voedselpakket toevoegen</button></a>
</div>
<br>
<table>
    <tr>
        <th>ID Voedselpakket</th>
        <th>Datum Afgifte</th>
        <th>Aanmaakdatum</th>
        <th>Klant Achternaam</th>
        <th>Producten</th>
        <th>Acties</th>
    </tr>
    <?php if (!empty($voedselpakketten)): ?>
        <?php foreach ($voedselpakketten as $pakket): ?>
        <tr>
            <td><?php echo htmlspecialchars($pakket['idvoedselpakket']); ?></td>
            <td>
                <form action="update_datumafgifte.php" method="post">
                    <input type="date" name="datumafgifte" value="<?php echo htmlspecialchars($pakket['datumafgifte']); ?>" <?php echo ($pakket['datumafgifte'] !== null) ? 'readonly' : ''; ?>>
                    <input type="hidden" name="idvoedselpakket" value="<?php echo htmlspecialchars($pakket['idvoedselpakket']); ?>">
                    <button type="submit" <?php echo ($pakket['datumafgifte'] !== null) ? 'disabled' : ''; ?>>Opslaan</button>
                </form>
            </td>
            <td><?php echo htmlspecialchars($pakket['aanmaakdatum']); ?></td>
            <td><?php echo htmlspecialchars($pakket['klant_achternaam']); ?></td>
            <td><?php echo htmlspecialchars($pakket['producten']); ?></td>
            <td>
                <a href="product_toevoegen.php?idvoedselpakket=<?php echo htmlspecialchars($pakket['idvoedselpakket']); ?>">
                    <button class="addproduct">Producten toevoegen aan voedselpakket</button>
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="6">Geen voedselpakketten gevonden.</td>
        </tr>
    <?php endif; ?>
</table>

</body>
</html>
