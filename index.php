<?php
require 'classes/Session.php';
require 'classes/User.php';

// Start the session
Session::start();
$user_id = Session::get('user_id');
$username = ''; // Initialize $username

if ($user_id) {
    // Fetch username from database based on user_id
    $user = new User();
    $userInfo = $user->getUserById($user_id); // Assuming we have a method to fetch user info by ID
    if ($userInfo) {
        $username = $userInfo['username'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management System</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .main-container {
            margin-top: 50px;
        }
        .btn-login,
        .btn-register {
            width: 150px; 
        }
        .btn-register {
            background-color: green;
            border-color: green;
        }
    </style>
</head>
<body>
    <div class="container main-container text-center">
        <h1>E.M.S. by Samir</h1>
        <?php if ($user_id): ?>
        <p class="mt-3"> Hello, <?php echo htmlspecialchars($username);?>! </p> <!--Greet logged-in users with their username-->
            <p> <a href="events.php" class="btn btn-success">Manage Your Events</a></p>
            <p> <a href="logout.php" class="btn btn-danger">Logout</a> </p>
        <?php else: ?>
            <p class="mt-3"> <a href="login.php" class="btn btn-primary btn-login">Login</a></p>
            <p class="mt-3"> <a href="register.php" class="btn btn-primary btn-register">Register</a></p>
        <?php endif; ?>
    </div>
</body>
</html>
