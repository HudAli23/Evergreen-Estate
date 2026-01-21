<?php
$con = new mysqli("localhost", "root", "", "realestate");
if ($con->connect_error) die("Connection Error: " . $con->connect_error);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['appointmentIDToUpdate'])) {
    $id = intval($_POST['appointmentIDToUpdate']);
    $date = $con->real_escape_string($_POST['appointmentdateUpdate']);
    $time = $con->real_escape_string($_POST['appointmenttimeUpdate']);
    $decision = $con->real_escape_string($_POST['clientdecisionUpdate']);
    $clientID = intval($_POST['clientIDUpdate']);
    $propertyID = intval($_POST['propertyIDUpdate']);
    $agentID = intval($_POST['agentIDUpdate']);

    $sql = "UPDATE showings 
            SET appointmentdate='$date', appointmenttime='$time', clientdecision='$decision',
                clientID=$clientID, propertyID=$propertyID, agentID=$agentID
            WHERE appointmentID=$id";

    if ($con->query($sql)) {
        header("Location: appointment.php");
        exit;
    } else {
        die("Error: " . $con->error);
    }
}
$con->close();
?>
