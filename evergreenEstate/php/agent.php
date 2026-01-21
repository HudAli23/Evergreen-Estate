<?php
// Page: Agent CRUD (agents referenced by clients/properties)
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
    <title>Evergreen Estate | Agents</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>

<header class="bg-dark text-white p-3">
    <div class="container d-flex justify-content-between align-items-center">
        <?php
        // Dynamic logo path
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

    <!-- Add Agent Card -->
    <div class="card p-4 mb-5 shadow-sm">
        <h2 class="mb-4">Add Agent</h2>
        <form action="agentcreate.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label>Agency Name</label>
                <input type="text" name="agency" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Agent Contact Number</label>
                <input type="text" name="contactnumber" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Agent Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Agent First Name</label>
                <input type="text" name="agentfirstname" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Agent Surname</label>
                <input type="text" name="surname" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-dark w-100">Add Agent</button>
        </form>
    </div>

    <hr class="my-5">

    <!-- Available Agents -->
    <h2 class="mb-4">Available Agents</h2>
    <div class="row">
        <?php
        $agents = mysqli_query($con, "SELECT * FROM agenttable");

        while ($agent = mysqli_fetch_assoc($agents)) {
            echo "<div class='col-md-4 mb-4'>";
            echo "<div class='card p-3 shadow-sm'>";

            // Editable agent form
            echo "<form action='agentupdate.php' method='post'>";
            echo "<input type='hidden' name='agentIDToUpdate' value='{$agent['agentID']}'>";

            echo "<div class='form-group'>";
            echo "<label>Agency Name</label>";
            echo "<input type='text' name='newAgency' class='form-control' value='{$agent['agency']}' required>";
            echo "</div>";

            echo "<div class='form-group'>";
            echo "<label>Contact Number</label>";
            echo "<input type='text' name='newContactNumber' class='form-control' value='{$agent['contactnumber']}' required>";
            echo "</div>";

            echo "<div class='form-group'>";
            echo "<label>Email</label>";
            echo "<input type='email' name='newEmail' class='form-control' value='{$agent['email']}' required>";
            echo "</div>";

            echo "<div class='form-group'>";
            echo "<label>First Name</label>";
            echo "<input type='text' name='newFirstName' class='form-control' value='{$agent['agentfirstname']}' required>";
            echo "</div>";

            echo "<div class='form-group'>";
            echo "<label>Surname</label>";
            echo "<input type='text' name='newSurname' class='form-control' value='{$agent['agentsurname']}' required>";
            echo "</div>";

            echo "<button type='submit' class='btn btn-sm btn-primary w-100 mb-2'>Update</button>";
            echo "</form>";

            // Delete button
            echo "<form action='agentdelete.php' method='post' onsubmit=\"return confirm('Are you sure you want to delete this agent?');\">";
            echo "<input type='hidden' name='agentID' value='{$agent['agentID']}'>";
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
