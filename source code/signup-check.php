<?php
session_start();
include "db_conn.php";

// Checking for required POST parameters
if (
    isset($_POST['customer_name']) &&
    isset($_POST['customer_email']) &&
    isset($_POST['customer_phone']) &&
    isset($_POST['customer_password'])
) {

    // Function to validate and sanitize user input
    function validate($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $name = validate($_POST['customer_name']);
    $email = validate($_POST['customer_email']);
    $phone = validate($_POST['customer_phone']);
    $password = validate($_POST['customer_password']);
    
    // Data to retain in case of error
    $user_data = 'customer_name=' . urlencode($name) . 
        '&customer_email=' . urlencode($email) . 
        '&customer_phone=' . urlencode($phone);

    // Validation checks
    if (empty($name)) {
        header("Location: sign_up.html?error=Name is required&$user_data");
        exit();
    } elseif (empty($email)) {
        header("Location: sign_up.html?error=Email is required&$user_data");
        exit();
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: sign_up.html?error=Invalid email format&$user_data");
        exit();
    } elseif (empty($phone)) {
        header("Location: sign_up.html?error=Phone number is required&$user_data");
        exit();
    } elseif (!ctype_digit($phone)) {
        header("Location: sign_up.html?error=Phone number must contain only digits&$user_data");
        exit();
    } elseif (empty($password)) {
        header("Location: sign_up.html?error=Password is required&$user_data");
        exit();
    }

    // Hash the password securely
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if the email already exists
    $sql = "SELECT * FROM customers WHERE customer_email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Handling duplicate email addresses
    if ($result->num_rows > 0) {
        header("Location: sign_up.html?error=Email already exists&$user_data");
        exit();
    } else {
        // Insert user into the database
        $sql2 = "INSERT INTO customers (customer_name, customer_email, customer_phone, customer_password) VALUES (?, ?, ?, ?)";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param("ssss", $name, $email, $phone, $hashed_password);
        $result2 = $stmt2->execute();

        if ($result2) {
            header("Location: status.html?success=Account created successfully");
            exit();
        } else {
            header("Location: sign_up.html?error=Unknown error occurred&$user_data");
            exit();
        }
    }
} else {
    header("Location: sign_up.html");
    exit();
}
?>
