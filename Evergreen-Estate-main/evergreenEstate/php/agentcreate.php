<?php
$con = new mysqli("localhost", "root", "", "realestate");
if ($con->connect_error) {
    die("DB error");
}

$stmt = $con->prepare("
    INSERT INTO agenttable (agency, contactnumber, email, agentfirstname, agentsurname)
    VALUES (?, ?, ?, ?, ?)
");

$stmt->bind_param(
    "sssss",
    $_POST['agency'],
    $_POST['contactnumber'],
    $_POST['email'],
    $_POST['agentfirstname'],
    $_POST['surname']
);

$stmt->execute();

header("Location: agent.php?success=created");
exit;
