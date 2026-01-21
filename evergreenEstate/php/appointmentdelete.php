<?php
$con = new mysqli("localhost", "root", "", "realestate");
if ($con->connect_error) die("Connection Error: " . $con->connect_error);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['appointmentIDToDelete'])) {
    $id = intval($_POST['appointmentIDToDelete']);
    $sql = "DELETE FROM showings WHERE appointmentID=$id";
    if ($con->query($sql)) {
        header("Location: appointment.php");
        exit;
    } else {
        die("Error: " . $con->error);
    }
}
$con->close();
?>
