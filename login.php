<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class ="container">
    
    <h2>Admin Login</h2>
    <form action="login.php" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <br><br>
        <input type="submit" value="Login">
    </form>
</div>
</body>
</html>

<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "mydb"; 

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Haal gebruikersnaam en wachtwoord op uit het formulier
        $user = $_POST['username'];
        $pass = $_POST['password'];

        // Bereid de SQL-query voor
        $stmt = $conn->prepare("SELECT idmedewerker, wachtwoord FROM medewerker WHERE gebruikersnaam = :username");
        $stmt->bindParam(':username', $user);
        $stmt->execute();

        // Controleer of de gebruiker bestaat
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            // Controleer het wachtwoord
            if (password_verify($pass, $row['wachtwoord'])) {
                // Zet de sessie en stuur door naar de klantenoverzicht pagina
                $_SESSION['admin'] = $user;
                header("Location: customers.php");
            } else {
                echo "Invalid username or password";
            }
        } else {
            echo "Invalid username or password";
        }
    } catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
}
?>
