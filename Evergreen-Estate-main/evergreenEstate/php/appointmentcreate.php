<?php
// Create appointment
$con = new mysqli("localhost", "root", "", "realestate");
if ($con->connect_error) die("Connection Error: " . $con->connect_error);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $appointmentdate = $con->real_escape_string($_POST['appointmentdate']);
    $appointmenttime = $con->real_escape_string($_POST['appointmenttime']);
    $clientdecision = $con->real_escape_string($_POST['clientdecision']);
    $clientID = intval($_POST['clientID']);
    $propertyID = intval($_POST['propertyID']);
    $agentID = intval($_POST['agentID']);

    if (!$appointmentdate || !$appointmenttime || !$clientdecision || !$clientID || !$propertyID || !$agentID) {
        die("All fields are required.");
    }

    $sql = "INSERT INTO showings (appointmentdate, appointmenttime, clientdecision, clientID, propertyID, agentID) 
            VALUES ('$appointmentdate', '$appointmenttime', '$clientdecision', $clientID, $propertyID, $agentID)";
    
    if ($con->query($sql)) {
        header("Location: appointment.php"); // redirect back to appointment page
        exit;
    } else {
        die("Error: " . $con->error);
    }
}
$con->close();
?>
