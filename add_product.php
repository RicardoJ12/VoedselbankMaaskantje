<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title>Product Toevoegen</title>
    <link rel="stylesheet" href="product.css">
</head>
<body>
    <div class="container">
        <h1>Voeg nieuw product toe</h1>
        <form action="add_product.php" method="POST">
            <label for="product_name">Productnaam:</label>
            <input type="text" id="product_name" name="product_name" required>
            <label for="product_image">Productafbeelding URL:</label>
            <input type="text" id="product_image" name="product_image" required>
            <label for="product_quantity">Aantal:</label>
            <input type="number" id="product_quantity" name="product_quantity" required>
            
            <label for="categorie_idcategorie">Categorie:</label>
            <select id="categorie_idcategorie" name="categorie_idcategorie" required>
                <option value="1">Soep</option>
            </select>

            <button type="submit">Product Toevoegen</button>
        </form>
    </div>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "mydb";

        $productName = $_POST['product_name'];
        $productImage = $_POST['product_image'];
        $productQuantity = $_POST['product_quantity'];
        $productCategory = $_POST['categorie_idcategorie'];

        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "INSERT INTO product (naamproduct, afbeeldingurl, aantal, categorie_idcategorie) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$productName, $productImage, $productQuantity, $productCategory]);

            echo "<p>Product succesvol toegevoegd!</p>";
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }
    ?>
    <a href="stock.php">Terug naar Voorraad</a>
</body>
</html>
