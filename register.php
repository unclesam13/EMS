<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'classes/User.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate password complexity
    $passwordRegex = '/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/'; // At least one letter, one number, and minimum 8 characters
    if (!preg_match($passwordRegex, $password)) {
        $message = 'Password must contain at least one number, one uppercase letter, and be at least 8 characters long.';
    } elseif (strpos($password, ' ') !== false) {
        $message = 'Password should not contain spaces.';
    } else {
        // Check if username or email already exists
        $user = new User();
        if ($user->isUsernameTaken($username)) {
            $message = 'Username is already taken.';
        } elseif ($user->isEmailTaken($email)) {
            $message = 'Email is already registered.';
        } else {
            // Proceed with registration
            if ($user->register($username, $email, $password)) {
                $message = 'User registered successfully!';
            } else {
                $message = 'User registration failed.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .form-container {
            margin-top: 50px;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }
        .form-heading {
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h1 class="form-heading text-center">Register</h1>
            <?php if ($message): ?>
                <div class="alert alert-danger"><?php echo $message; ?></div>
            <?php endif; ?>
            <form method="POST" action="register.php">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" class="form-control" id="username" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" class="form-control" id="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" class="form-control" id="password" required>
                    <small class="form-text text-muted">Password must contain at least one number, one uppercase letter, and be at least 8 characters long. Should not contain spaces.</small>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Register</button>
            </form>
            <p class="text-center mt-3"><a href="index.php">Back to Home</a></p>
        </div>
    </div>
</body>
</html>
