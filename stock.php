<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Voorraad Pagina</title>
    <link rel="stylesheet" href="stock.css">
    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="styles.css">
    <script src="main.js" defer></script>
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
<div class="container">
    <h1>Voorraad Pagina</h1>
    <a href="add_product.php" class="button">Voeg nieuwe producten toe</a>
    <div class="product-grid">
        <?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "mydb";

        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT * FROM product");
            $stmt->execute();
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($products as $product) {
                echo '<div class="product-card">';
                echo '<img src="' . htmlspecialchars($product['afbeeldingurl']) . '" alt="' . htmlspecialchars($product['naamproduct']) . '">';
                echo '<p>' . htmlspecialchars($product['naamproduct']) . '</p>';
                echo '<p>' . htmlspecialchars($product['aantal']) . 'x</p>';
                echo '<button class="btn-decrease" data-id="' . htmlspecialchars($product['idproduct']) . '">-</button>';
                echo '<button class="btn-increase" data-id="' . htmlspecialchars($product['idproduct']) . '">+</button>';
                echo '</div>';
            }
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
        ?>
    </div>
</div>
</body>
</html>
