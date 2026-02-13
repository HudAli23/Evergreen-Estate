<?php
$con = new mysqli("localhost", "root", "", "realestate");
if ($con->connect_error) {
    die("DB Connection failed");
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['agentID'])) {

    $agentID = intval($_POST['agentID']);

    // 🔒 BLOCK DELETE IF AGENT IS USED
    $check1 = $con->query("SELECT COUNT(*) AS c FROM client WHERE agentID = $agentID");
    $usedByClient = $check1->fetch_assoc()['c'];

    $check2 = $con->query("SELECT COUNT(*) AS c FROM property WHERE agentID = $agentID");
    $usedByProperty = $check2->fetch_assoc()['c'];

    if ($usedByClient > 0 || $usedByProperty > 0) {
        header("Location: agent.php?error=linked");
        exit;
    }

    // 🗑 DELETE AGENT
    $stmt = $con->prepare("DELETE FROM agenttable WHERE agentID = ?");
    $stmt->bind_param("i", $agentID);
    $stmt->execute();

    header("Location: agent.php?success=deleted");
    exit;
}

header("Location: agent.php?error=invalid");
exit;
