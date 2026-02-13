<?php
$con = new mysqli("localhost", "root", "", "realestate");
if ($con->connect_error) {
    die("DB error");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $stmt = $con->prepare("
        UPDATE agenttable
        SET agency=?, contactnumber=?, email=?, agentfirstname=?, agentsurname=?
        WHERE agentID=?
    ");

    $stmt->bind_param(
        "sssssi",
        $_POST['newAgency'],
        $_POST['newContactNumber'],
        $_POST['newEmail'],
        $_POST['newFirstName'],
        $_POST['newSurname'],
        $_POST['agentIDToUpdate']
    );

    $stmt->execute();

    header("Location: agent.php?success=updated");
    exit;
}
