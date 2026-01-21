<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "realestate";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection Error: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the array keys exist before using them
    $contactnumber = isset($_POST["contactnumber"]) ? $_POST["contactnumber"] : "";
    $email = isset($_POST["email"]) ? $_POST["email"] : "";
    $clientfirstname = isset($_POST["clientfirstname"]) ? $_POST["clientfirstname"] : "";
    $clientsurname = isset($_POST["clientsurname"]) ? $_POST["clientsurname"] : "";
    $agentID = isset($_POST["agentID"]) ? $_POST["agentID"] : "";

    // Validate input data (you may need to add more validation based on your requirements)
    if (empty($contactnumber) || empty($email) || empty($clientfirstname) || empty($clientsurname) || empty($agentID)) {
        die("Error: All fields are required.");
    }

    // Use parameterized query to prevent SQL injection
    $sql = "INSERT INTO client (contactnumber, email, clientfirstname, clientsurname, agentID) 
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $contactnumber, $email, $clientfirstname, $clientsurname, $agentID);

    if ($stmt->execute()) {
        echo "New client record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
