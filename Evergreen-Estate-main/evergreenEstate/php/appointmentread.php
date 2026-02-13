<?php
$con = new mysqli("localhost", "root", "", "realestate");
if ($con->connect_error) {
    die("Connection Error: " . $con->connect_error);
}

// Fetch appointments with client, property, and agent info
$sql = "
    SELECT s.appointmentID, s.appointmentdate, s.appointmenttime, s.clientdecision,
           c.clientfirstname, c.clientsurname,
           p.propertyname,
           a.agentfirstname, a.agentsurname
    FROM showings s
    LEFT JOIN client c ON s.clientID = c.clientID
    LEFT JOIN property p ON s.propertyID = p.propertyID
    LEFT JOIN agenttable a ON s.agentID = a.agentID
    ORDER BY s.appointmentdate DESC, s.appointmenttime DESC
";

$appointments = mysqli_query($con, $sql);

if (mysqli_num_rows($appointments) > 0) {
    echo '<div class="row">';
    while ($appt = mysqli_fetch_assoc($appointments)) {
        echo '<div class="col-md-4 mb-4">';
        echo '<div class="card p-3 shadow-sm">';
        echo "<h5>Appointment ID: {$appt['appointmentID']}</h5>";
        echo "<p><strong>Date:</strong> {$appt['appointmentdate']}</p>";
        echo "<p><strong>Time:</strong> {$appt['appointmenttime']}</p>";
        echo "<p><strong>Client Decision:</strong> {$appt['clientdecision']}</p>";
        echo "<p><strong>Client:</strong> {$appt['clientfirstname']} {$appt['clientsurname']}</p>";
        echo "<p><strong>Property:</strong> {$appt['propertyname']}</p>";
        echo "<p><strong>Agent:</strong> {$appt['agentfirstname']} {$appt['agentsurname']}</p>";
        echo '</div>';
        echo '</div>';
    }
    echo '</div>';
} else {
    echo "<p>No appointments found.</p>";
}

$con->close();
?>
