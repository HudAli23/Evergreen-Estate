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
    $newClientsurname = isset($_POST["newClientsurnameUpdate"]) ? $_POST["newClientsurnameUpdate"] : "";
    $newClientName = isset($_POST["newClientName"]) ? $_POST["newClientName"] : "";
    $newContactNumber = isset($_POST["contactUpdate"]) ? $_POST["contactUpdate"] : "";
    $newEmail = isset($_POST["emailUpdate"]) ? $_POST["emailUpdate"] : "";
    $newAgentID = isset($_POST["agentIDUpdate"]) ? $_POST["agentIDUpdate"] : "";

    // Retrieve the selected client ID to update
    $clientIDToUpdate = isset($_POST["clientIDToUpdate"]) ? $_POST["clientIDToUpdate"] : "";

    // Update the database with the new values using a parameterized query
    $sql = "UPDATE client SET clientfirstname = ?, clientsurname = ?, contactnumber = ?, email = ?, agentID = ? WHERE clientID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssii", $newClientName, $newClientsurname, $newContactNumber, $newEmail, $newAgentID, $clientIDToUpdate);

    if ($stmt->execute()) {
        echo "Client record updated successfully";
    } else {
        echo "Error updating record: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
