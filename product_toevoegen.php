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

// Check if voedselpakket ID is provided and valid
if (!isset($_GET['idvoedselpakket']) || !is_numeric($_GET['idvoedselpakket'])) {
    echo "Ongeldige voedselpakket ID";
    exit();
}

$idvoedselpakket = $_GET['idvoedselpakket'];

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Query to get voedselpakket information
    $stmt = $conn->prepare("SELECT * FROM voedselpakket WHERE idvoedselpakket = :id");
    $stmt->bindParam(':id', $idvoedselpakket, PDO::PARAM_INT);
    $stmt->execute();
    $voedselpakket = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$voedselpakket) {
        echo "Voedselpakket niet gevonden";
        exit();
    }

    // Query to get products
    $stmt = $conn->prepare("SELECT * FROM product");
    $stmt->execute();
    $producten = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    echo "Verbinding mislukt: " . htmlspecialchars($e->getMessage());
    exit();
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Producten toevoegen aan voedselpakket</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="navbar.css">
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
<h2>Producten toevoegen aan voedselpakket</h2>
<h3>Voedselpakket: <?php echo htmlspecialchars($voedselpakket['idvoedselpakket']); ?></h3>

<form action="process_product_toevoegen.php" method="post">
    <input type="hidden" name="voedselpakket_id" value="<?php echo htmlspecialchars($idvoedselpakket); ?>">
    <label for="product">Product:</label>
    <select name="product_id" id="product">
        <?php foreach ($producten as $product): ?>
            <option value="<?php echo htmlspecialchars($product['idproduct']); ?>"><?php echo htmlspecialchars($product['naamproduct']); ?></option>
        <?php endforeach; ?>
    </select>
    <br><br>
    <label for="quantity">Aantal:</label>
    <input type="number" name="quantity" id="quantity" min="1" required>
    <br><br>
    <input type="submit" value="Product toevoegen">
</form>

</body>
</html>
