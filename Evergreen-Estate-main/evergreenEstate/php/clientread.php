<?php
$clients = mysqli_query($conn, "SELECT * FROM client");

if (mysqli_num_rows($clients) > 0) {
    while ($client = mysqli_fetch_assoc($clients)) {
        echo "<p>clientID: {$client['clientID']}</p>";
        echo "<p>AgentID: {$client['agentID']}</p>";
        echo "<h3>clientfirstname: {$client['clientfirstname']}</h3>";
        echo "<p>clientsurname: {$client['clientsurname']}</p>";
        echo "<p>email: {$client['email']}</p>";
        echo "<p>contactnumber : {$client['contactnumber']}</p>";
      
        echo "<hr>";
    }

} else {
    echo "No clients found.";
}

mysqli_close($conn);
?>
