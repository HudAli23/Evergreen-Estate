<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "realestate";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection Error: " . $conn->connect_error);
}

// Process the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form data
    $clientIDToDelete = mysqli_real_escape_string($conn, $_POST["clientIDToDelete"]);

    // Delete the record from the database
    $sql = "DELETE FROM client WHERE clientID = '$clientIDToDelete'";

    if ($conn->query($sql) === TRUE) {
        echo "Client record deleted successfully";
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>
