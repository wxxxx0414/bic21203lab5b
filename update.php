<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit;
}
?>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lab_5b";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Update logic
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["old_matric"])) {
    $old_matric = $_POST["old_matric"]; // Original matric value
    $new_matric = $_POST["new_matric"]; // New matric value
    $name = $_POST["name"];
    $role = $_POST["role"]; // Dropdown value for role

    // Check if the new matric already exists (prevent duplication)
    $checkStmt = $conn->prepare("SELECT * FROM users WHERE matric = ? AND matric != ?");
    $checkStmt->bind_param("ss", $new_matric, $old_matric);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        echo "<script>alert('The matric number is already in use. Please choose a different one.'); window.location.href='update.php?matric=" . urlencode($old_matric) . "';</script>";
        exit;
    }

    // Proceed with the update
    $stmt = $conn->prepare("UPDATE users SET matric = ?, name = ?, role = ? WHERE matric = ?");
    $stmt->bind_param("ssss", $new_matric, $name, $role, $old_matric);

    if ($stmt->execute()) {
        echo "<script>alert('Record updated successfully!'); window.location.href='display.php';</script>";
    } else {
        echo "Error updating record: " . $stmt->error;
    }

    $stmt->close();
    exit;
}

// Get user data for the form
if (isset($_GET["matric"])) {
    $matric = $_GET["matric"];
    $stmt = $conn->prepare("SELECT * FROM users WHERE matric = ?");
    $stmt->bind_param("s", $matric);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        echo "<script>alert('User not found!'); window.location.href='display.php';</script>";
        exit;
    }
    $stmt->close();
} else {
    echo "<script>alert('Invalid access!'); window.location.href='display.php';</script>";
    exit;
}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update User</title>
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

        .update-container {
            background-color: #fff;
            color: #000;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.1);
            text-align: left;
            width: 300px; /* Adjust the width to fit the content */
        }

        .update-container label {
            display: inline-block;
            width: 100px; /* Adjust the width as needed */
            text-align: left;
            margin-right: 10px;
        }

        .update-container input{
            width: 95%; /* Adjust the width to fit the container */
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .update-container select {
            width: 100%; /* Adjust the width to fit the container */
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .update-container button {
            width: 100%;
            padding: 10px;
            background-color: #000;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .update-container button:hover {
            background-color: #444;
        }

        .update-container p {
            text-align: center;
        }
        
        .update-container a {
            color: blue; /* Change the color to blue */
            text-decoration: none;
        }

        .update-container a:hover {
            text-decoration: underline;
        }

        .form-group {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="update-container">
        <h2>Update User</h2>
        <form method="POST" action="update.php">
            <!-- Include the original matric value as a hidden input -->
            <input type="hidden" name="old_matric" value="<?php echo htmlspecialchars($user['matric']); ?>">

            <div class="form-group">
                <label for="new_matric">Matric:</label>
                <input type="text" id="new_matric" name="new_matric" value="<?php echo htmlspecialchars($user['matric']); ?>" required>
            </div>

            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
            </div>

            <div class="form-group">
                <label for="role">Access Level:</label>
                <select id="role" name="role" required>
                    <option value="Student" <?php echo ($user['role'] == 'Student') ? 'selected' : ''; ?>>Student</option>
                    <option value="Lecturer" <?php echo ($user['role'] == 'Lecturer') ? 'selected' : ''; ?>>Lecturer</option>
                </select>
            </div>

            <button type="submit">Update</button>
            <p><a href="display.php">Cancel</a></p>
        </form>
    </div>
</body>
</html>
