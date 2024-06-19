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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $voedselpakket_id = $_POST['voedselpakket_id'];
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    if (!is_numeric($voedselpakket_id) || !is_numeric($product_id) || !is_numeric($quantity) || $quantity <= 0) {
        echo "Ongeldige invoer";
        exit();
    }

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Start transaction
        $conn->beginTransaction();

        // Retrieve current stock quantity
        $stmt = $conn->prepare("SELECT aantal FROM product WHERE idproduct = :product_id");
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $stmt->execute();
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$product) {
            echo "Product niet gevonden";
            exit();
        }

        $current_stock = $product['aantal']; 

        if ($current_stock < $quantity) {
            echo "Onvoldoende voorraad";
            exit();
        }

        // trekt aantal van voorraad
        $new_stock = $current_stock - $quantity;
        $stmt = $conn->prepare("UPDATE product SET aantal = :new_stock WHERE idproduct = :product_id");
        $stmt->bindParam(':new_stock', $new_stock, PDO::PARAM_INT);
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $stmt->execute();
        
        //kijkt of het record al bestaat in voedselpakket
        $stmt = $conn->prepare("SELECT aantal FROM product_has_voedselpakket WHERE product_idproduct = :product_id AND voedselpakket_idvoedselpakket = :voedselpakket_id");
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $stmt->bindParam(':voedselpakket_id', $voedselpakket_id, PDO::PARAM_INT);
        $stmt->execute();
        $existing_record = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing_record) {
            // record bestaat al en voegt bij het aantal bij
            $new_quantity = $existing_record['aantal'] + $quantity;
            $stmt = $conn->prepare("UPDATE product_has_voedselpakket SET aantal = :new_quantity WHERE product_idproduct = :product_id AND voedselpakket_idvoedselpakket = :voedselpakket_id");
            $stmt->bindParam(':new_quantity', $new_quantity, PDO::PARAM_INT);
            $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
            $stmt->bindParam(':voedselpakket_id', $voedselpakket_id, PDO::PARAM_INT);
            $stmt->execute();
        } else {
            //toevoegen aan pakket
            $stmt = $conn->prepare("INSERT INTO product_has_voedselpakket (product_idproduct, voedselpakket_idvoedselpakket, aantal) VALUES (:product_id, :voedselpakket_id, :aantal)");
            $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
            $stmt->bindParam(':voedselpakket_id', $voedselpakket_id, PDO::PARAM_INT);
            $stmt->bindParam(':aantal', $quantity, PDO::PARAM_INT);
            $stmt->execute();
        }

        // Commit transaction
        $conn->commit();

        header("Location: voedselpakket.php");
        exit();

    } catch(PDOException $e) {
        // Rollback transaction on error
        if (isset($conn)) {
            $conn->rollBack();
        }
        echo "Verbinding mislukt: " . htmlspecialchars($e->getMessage());
        exit();
    }
} else {
    echo "Ongeldige aanvraag";
    exit();
}
?>
