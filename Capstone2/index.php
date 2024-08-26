<?php
include './inc/connect.php';

$error_message = '';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (!empty($username) && !empty($password)) {
        // Prepare and bind
        $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($id, $hashed_password);

        if ($stmt->num_rows > 0) {
            $stmt->fetch();
            if (password_verify($password, $hashed_password)) {
                // Password is correct, start a session and redirect to a protected page
                session_start();
                $_SESSION['userid'] = $id;
                $_SESSION['username'] = $username;
                header("Location: dashboard.php");
                exit();
            } else {
                $error_message = "Invalid password!";
            }
        } else {
            $error_message = "No user found with that username!";
        }

        $stmt->close();
    } else {
        $error_message = "Please fill in both fields.";
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    
    <link rel="stylesheet" type="text/css" href="style.css">
    <style>.error-message {
    color: red;
    margin-bottom: 15px;
    font-size: 14px;
    text-align: center;
}</style>
</head>
<body>
<div class="container">
    <div class="login">
        <div class="header">
            <h1>Login</h1>
        </div>
        <div class="main">
             <!-- Display error message if it exists -->
             <?php if (!empty($error_message)): ?>
                <div class="error-message">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>
            <form action="index.php" method="POST">
                <input type="text" name="username" placeholder="Username" required>
                <br>
                <input type="password" name="password" placeholder="Password" required>
                <br>
                <button type="submit" name="login">Login</button>
            </form>
          
        </div>
    </div>
    <div class="img">
        <span>
            <h1>Welcome</h1>
            <p>Irrigation Canal water level monitoring</p>
        </span>
    </div>
</div>
</body>
</html>
