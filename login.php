<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'classes/User.php';
require 'classes/Session.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $user = new User();
    $loginResult = $user->login($username, $password);

    if (is_int($loginResult)) {
        // Successful login
        Session::start();
        Session::set('user_id', $loginResult);
        header('Location: events.php');
        exit;
    } elseif ($loginResult === 'username_not_found') {
        $message = 'This username does not exist.';
    } elseif ($loginResult === 'incorrect_password') {
        $message = 'Incorrect password.';
    } else {
        $message = 'Login failed.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
            <h1 class="form-heading text-center">Login</h1>
            <?php if ($message): ?>
                <div class="alert alert-danger"><?php echo $message; ?></div>
            <?php endif; ?>
            <form method="POST" action="login.php">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" class="form-control" id="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" class="form-control" id="password" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Login</button>
            </form>
            <p class="text-center mt-3"><a href="index.php">Back to Home</a></p>
        </div>
    </div>
</body>
</html>
