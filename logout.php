<?php
session_start();
session_unset();
session_destroy();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Logout</title>
    <meta http-equiv="refresh" content="3;url=login.php" />
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #000;
            color: #fff;
            font-family: Arial, sans-serif;
        }

        .message-container {
            background-color: #fff;
            color: #000;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.1);
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="message-container">
        <p>You have been successfully logged out.</p>
        <p>Redirecting to login page in 3 seconds...</p>
    </div>
</body>
</html>