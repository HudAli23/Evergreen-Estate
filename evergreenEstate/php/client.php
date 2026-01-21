<?php
// Page: Client CRUD (assigns each client to an agent)
// Connect to database
$con = new mysqli("localhost", "root", "", "realestate");
if ($con->connect_error) {
    die("Connection Error: " . $con->connect_error);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evergreen Estate | Clients</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .client-card {
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .client-card .card-body {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }
        .client-card button {
            margin-top: auto;
        }
    </style>
</head>

<body>

<header class="bg-dark text-white p-3">
    <div class="container d-flex justify-content-between align-items-center">
        <?php
        $logoPath = "images/logo.png";
        if (!file_exists($logoPath)) {
            echo "<span style='color:#fff;font-weight:bold;'>Evergreen Estate</span>";
        } else {
            echo "<img src='{$logoPath}' alt='Evergreen Estate' style='height:50px; width:auto;'>";
        }
        ?>
        <nav>
            <ul class="list-unstyled d-flex mb-0">
                <li class="mr-3"><a href="property.php" class="text-white">Property</a></li>
                <li class="mr-3"><a href="agent.php" class="text-white">Agent</a></li>
                <li class="mr-3"><a href="client.php" class="text-white">Client</a></li>
                <li class="mr-3"><a href="appointment.php" class="text-white">Appointment</a></li>
            </ul>
        </nav>
    </div>
</header>

<div class="container mt-5">

    <!-- Add Client Form -->
    <div class="card p-4 mb-5 shadow-sm">
        <h2 class="mb-4">Add Client</h2>
        <form action="clientcreate.php" method="post">
            <div class="form-group">
                <label>Client First Name</label>
                <input type="text" name="clientfirstname" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Client Surname</label>
                <input type="text" name="clientsurname" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Contact Number</label>
                <input type="text" name="contactnumber" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Assign Agent</label>
                <select name="agentID" class="form-control" required>
                    <?php
                    $agents = mysqli_query($con, "SELECT * FROM agenttable");
                    while ($agent = mysqli_fetch_assoc($agents)) {
                        echo "<option value='{$agent['agentID']}'>{$agent['agentfirstname']} {$agent['agentsurname']}</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-dark w-100">Add Client</button>
        </form>
    </div>

    <!-- Available Clients -->
    <h2 class="mb-4">Available Clients</h2>
    <div class="row">
        <?php
        $clients = mysqli_query($con, "SELECT client.*, agenttable.agentfirstname, agenttable.agentsurname 
                                        FROM client 
                                        LEFT JOIN agenttable ON client.agentID = agenttable.agentID");

        while ($client = mysqli_fetch_assoc($clients)) {
            echo "<div class='col-md-4 mb-4'>";
            echo "<div class='card p-3 shadow-sm client-card'>";

            // Update form
            echo "<form action='clientupdate.php' method='post'>";
            echo "<input type='hidden' name='clientIDToUpdate' value='{$client['clientID']}'>";

            echo "<div class='form-group'><label>First Name</label>";
            echo "<input type='text' name='clientfirstnameUpdate' class='form-control' value='{$client['clientfirstname']}' required></div>";

            echo "<div class='form-group'><label>Surname</label>";
            echo "<input type='text' name='clientsurnameUpdate' class='form-control' value='{$client['clientsurname']}' required></div>";

            echo "<div class='form-group'><label>Contact Number</label>";
            echo "<input type='text' name='contactnumberUpdate' class='form-control' value='{$client['contactnumber']}' required></div>";

            echo "<div class='form-group'><label>Email</label>";
            echo "<input type='email' name='emailUpdate' class='form-control' value='{$client['email']}' required></div>";

            echo "<div class='form-group'><label>Assigned Agent</label>";
            echo "<select name='agentIDUpdate' class='form-control' required>";
            $agents = mysqli_query($con, "SELECT * FROM agenttable");
            while ($agentOption = mysqli_fetch_assoc($agents)) {
                $selected = ($agentOption['agentID'] == $client['agentID']) ? "selected" : "";
                echo "<option value='{$agentOption['agentID']}' {$selected}>{$agentOption['agentfirstname']} {$agentOption['agentsurname']}</option>";
            }
            echo "</select></div>";

            echo "<button type='submit' class='btn btn-sm btn-primary w-100 mb-2'>Update</button>";
            echo "</form>";

            // Delete form
            echo "<form action='clientdelete.php' method='post' onsubmit=\"return confirm('Are you sure you want to delete this client?');\">";
            echo "<input type='hidden' name='clientIDToDelete' value='{$client['clientID']}'>";
            echo "<button type='submit' class='btn btn-sm btn-danger w-100'>Delete</button>";
            echo "</form>";

            echo "</div>"; // card
            echo "</div>"; // col
        }
        ?>
    </div>

</div>

<footer class="bg-dark text-white text-center p-3 mt-5">
    &copy; 2024 Evergreen Estate
</footer>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
