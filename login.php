<?php
session_start();
$servername = "localhost";
$username = "newuser"; // Your MySQL username
$password = "password"; // Your MySQL password
$dbname = "user_auth"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Protect against SQL injection
    $email = stripslashes($email);
    $password = stripslashes($password);
    $email = mysqli_real_escape_string($conn, $email);
    $password = mysqli_real_escape_string($conn, $password);

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['login_user'] = $email;
            header("location: welcome.php"); // Redirect to another page after successful login
        } else {
            echo "Your Login Name or Password is invalid";
        }
    } else {
        echo "No user found with this email.";
    }
}

$conn->close();
?>
