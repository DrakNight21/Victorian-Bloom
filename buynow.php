<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = $_POST['product_name'] ?? '';
    $name = $_POST['name'] ?? '';
    $contact = $_POST['contact'] ?? '';
    $address = $_POST['address'] ?? '';

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "projectlogin";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "INSERT INTO orders (product_name, name, contact, address) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("ssss", $product_name, $name, $contact, $address);

    if ($stmt->execute()) {
        echo "<script>alert('Order placed successfully!'); window.location.href = 'home.html';</script>";
    } else {
        echo "<script>alert('Error placing order! Please try again.');</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
