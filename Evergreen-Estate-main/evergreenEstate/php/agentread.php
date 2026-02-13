<?php
// Establish the database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "realestate";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection Error: " . $conn->connect_error);
}

$agent_result = mysqli_query($conn, "SELECT * FROM agenttable");

if (mysqli_num_rows($agent_result) > 0) {
    while ($agent = mysqli_fetch_assoc($agent_result)) {
        echo "<p>agentID: {$agent['agentID']}</p>";
        echo "<h3>agency: {$agent['agency']}</h3>";
        echo "<p>contactnumber: {$agent['contactnumber']}</p>";
        echo "<p>email: {$agent['email']}</p>";
        echo "<p>agentfirstname : {$agent['agentfirstname']}</p>";
        echo "<p>agentsurname : {$agent['agentsurname']}</p>";
        if (!empty($agent['agentimage'])) {
            $imageData = base64_encode($agent['agentimage']);
            $imageSrc = "data:image/jpeg;base64,{$imageData}";
            echo "<img src='{$imageSrc}' alt='{$agent['agency']}' class='img-fluid'>";
        }

        echo "<hr>";
    }
} else {
    echo "No agents found.";
}

mysqli_close($conn);
?>
