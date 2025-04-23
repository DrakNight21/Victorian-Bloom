<?php
header('Content-Type: application/json');

$host = 'localhost';
$db = 'projectlogin';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed.']);
    exit();
}

$action = $_POST['action'] ?? '';
$user_type = $_POST['user_type'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (!$action || !$user_type || !$email || !$password) {
    echo json_encode(['success' => false, 'message' => 'All fields are required.']);
    exit();
}

$table = $user_type === 'admin' ? 'admins' : 'customers';

if ($action === 'signup') {
    // Only allow signups for customers
    if ($user_type !== 'customer') {
        echo json_encode(['success' => false, 'message' => 'Only customers can sign up.']);
        exit();
    }

    // Check if user already exists
    $stmt = $conn->prepare("SELECT * FROM customers WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'User already exists. Please login.']);
    } else {
        // Create new customer
        $insert = $conn->prepare("INSERT INTO customers (email, password) VALUES (?, ?)");
        $insert->bind_param("ss", $email, $password);  // Consider using password_hash() in real use
        if ($insert->execute()) {
            echo json_encode(['success' => true, 'message' => 'Signup successful! Please log in.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Signup failed. Try again.']);
        }
        $insert->close();
    }
    $stmt->close();
} else if ($action === 'login') {
    // Attempt login
    $stmt = $conn->prepare("SELECT * FROM $table WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if ($user['password'] === $password) {
            $redirect = $user_type === 'admin' ? 'administrator.php' : 'home.html';
            echo json_encode(['success' => true, 'redirect' => $redirect]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Incorrect password.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'No user found with that email.']);
    }
    $stmt->close();
}

$conn->close();
?>
