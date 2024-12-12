<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lab_5b";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matric = $_POST['matric'];
    $name = $_POST['name'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $stmt = $conn->prepare("INSERT INTO users (matric, name, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $matric, $name, $password, $role);

    if ($stmt->execute()) {
        echo "<script>alert('Registration successful!');</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}
$conn->close();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Registration</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: black;
            font-family: Arial, sans-serif;
        }

        .registration-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: left;
            width: 300px;
            /* Adjust the width to fit the content */
        }

        .registration-container input {
            width: 93%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .registration-container select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .registration-container button {
            width: 100%;
            padding: 10px;
            background-color: #000;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .registration-container button:hover {
            background-color: #444;
        }

        .registration-container p {
            text-align: center;
        }

        .registration-container a {
            color: blue;
            /* Change the color to blue */
            text-decoration: none;
        }

        .registration-container a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="registration-container">
        <form method="POST" action="">
            <h2>Register</h2>
            <label for="matric">Matric:</label>
            <input type="text" id="matric" name="matric" required><br>

            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required><br>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br>

            <label for="role">Role:</label>
            <select id="role" name="role" required>
                <option value="">Please select</option>
                <option value="Lecturer">Lecturer</option>
                <option value="Student">Student</option>
            </select><br>

            <button type="submit" value="Submit">Register</button><br>

            <p>Back to <a href="login.php">Login</a></p>
        </form>
    </div>
</body>

</html>