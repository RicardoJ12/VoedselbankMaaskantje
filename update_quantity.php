<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mydb";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($input['id']) && isset($input['delta'])) {
        $productId = (int)$input['id'];
        $delta = (int)$input['delta'];

        $stmt = $conn->prepare("UPDATE product SET aantal = aantal + :delta WHERE idproduct = :id");
        $stmt->bindParam(':delta', $delta, PDO::PARAM_INT);
        $stmt->bindParam(':id', $productId, PDO::PARAM_INT);
        $stmt->execute();

        $stmt = $conn->prepare("SELECT aantal FROM product WHERE idproduct = :id");
        $stmt->bindParam(':id', $productId, PDO::PARAM_INT);
        $stmt->execute();
        $newQuantity = $stmt->fetchColumn();

        echo json_encode(['success' => true, 'newQuantity' => $newQuantity]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid input']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
