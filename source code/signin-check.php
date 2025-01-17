<?php 
session_start();
include "db_conn.php"; // Include the database connection file

if (isset($_POST['user_input']) && isset($_POST['password'])) {
    function validate($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $user_input = validate($_POST['user_input']);
    $password = validate($_POST['password']);

    if (empty($user_input)) {
        header("Location: sign_in.html?error=User input is required");
        exit();
    } else if (empty($password)) {
        header("Location: sign_in.html?error=Password is required");
        exit();
    } else {
        // Query to check for email or phone number
        $sql = "SELECT * FROM customers WHERE (customer_email=? OR customer_phone=?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $user_input, $user_input);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) === 1) {
            $row = mysqli_fetch_assoc($result);
            
            // Verify the password using password_verify
            if (password_verify($password, $row['customer_password'])) {
                // Save user information in the session
                $_SESSION['customer_name'] = $row['customer_name'];
                $_SESSION['customer_email'] = $row['customer_email'];
                $_SESSION['customer_id'] = $row['customer_id'];

                header("Location: home.html"); // Redirect to the homepage
                exit();
            } else {
                header("Location: sign_in.html?error=Incorrect password");
                exit();
            }
        } else {
            header("Location: sign_in.html?error=Invalid email or phone number");
            exit();
        }
    }
} else {
    header("Location: sign_in.html");
    exit();
}
?>
