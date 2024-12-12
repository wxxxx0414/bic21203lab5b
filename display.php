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

echo "<!-- Session Status Check -->";
echo "<!-- Session Exists: " . (isset($_SESSION['loggedin']) ? 'Yes' : 'No') . " -->";

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lab_5b";

try {
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    echo "<!-- Database Connection Successful -->";

    $sql = "SELECT matric, name, role FROM users";
    $result = $conn->query($sql);

    if ($result === false) {
        throw new Exception("Error executing query: " . $conn->error);
    }
} catch (Exception $e) {
    echo "<!-- Error: " . $e->getMessage() . " -->";
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Display</title>
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

        .container {
            background-color: #fff;
            color: #000;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.1);
            text-align: center;
            width: 80%;
            max-width: 800px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #000;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #000;
            color: #fff;
        }

        .action-links p {
            text-align: right;
        }

        .action-links a {
            color: blue;
            text-decoration: none;
            margin: 0 5px;
            text-align: right;
        }

        .action-links a:hover {
            text-decoration: underline;
        }
    </style>
    <script>
        function confirmDelete(matric) {
            if (confirm("Are you sure you want to delete this user?")) {
                window.location.href = 'delete.php?matric=' + matric;
            }
        }
    </script>
</head>

<body>
    <div class="container">
        <div class="action-links">
            <p><a href="logout.php">Logout</a></p>
        </div>
        <h1>Welcome</h1>
        <?php
        if (isset($result) && $result !== false) {
            if ($result->num_rows > 0) {
                echo "<table>";
                echo "<tr><th>Matric</th><th>Name</th><th>Role</th><th>Action</th></tr>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row["matric"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["role"]) . "</td>";
                    echo "<td class='action-links'>
                        <a href='update.php?matric=" . urlencode($row["matric"]) . "'>Update</a>
                        <a href='#' onclick='confirmDelete(\"" . urlencode($row["matric"]) . "\")'>Delete</a>
                    </td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p>No users found.</p>";
            }
        } else {
            echo "<p>Error fetching users.</p>";
        }
        ?>
    </div>
</body>

</html>