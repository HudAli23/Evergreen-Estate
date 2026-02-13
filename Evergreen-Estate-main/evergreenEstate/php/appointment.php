<?php
// Page: Appointment scheduling CRUD (links client + property + agent)
// Database connection
$con = new mysqli("localhost", "root", "", "realestate");
if ($con->connect_error) {
    die("Connection Error: " . $con->connect_error);
}

// Mapping DB codes to readable labels
$decisionMap = [
    "U" => "Undecided",
    "Y" => "Yes",
    "N" => "No"
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Evergreen Estate | Appointments</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<style>
    .appointment-card { height: 100%; display: flex; flex-direction: column; }
    .appointment-card .card-body { flex-grow: 1; display: flex; flex-direction: column; }
    .appointment-card button { margin-top: auto; }
</style>
</head>
<body>

<header class="bg-dark text-white p-3">
    <div class="container d-flex justify-content-between align-items-center">
        <strong>Evergreen Estate</strong>
        <nav>
            <a href="property.php" class="text-white mr-3">Property</a>
            <a href="agent.php" class="text-white mr-3">Agent</a>
            <a href="client.php" class="text-white mr-3">Client</a>
            <a href="appointment.php" class="text-white">Appointment</a>
        </nav>
    </div>
</header>

<div class="container mt-5">

<!-- ADD APPOINTMENT -->
<div class="card p-4 mb-5 shadow-sm">
    <h2 class="mb-4">Add Appointment</h2>

    <form action="appointmentcreate.php" method="post">
        <div class="form-group">
            <label>Date</label>
            <input type="date" name="appointmentdate" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Time</label>
            <input type="time" name="appointmenttime" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Client Decision</label>
            <select name="clientdecision" class="form-control" required>
                <option value="U">Undecided</option>
                <option value="Y">Yes</option>
                <option value="N">No</option>
            </select>
        </div>

        <div class="form-group">
            <label>Select Client</label>
            <select name="clientID" class="form-control" required>
                <?php
                $clients = mysqli_query($con, "SELECT * FROM client");
                while ($c = mysqli_fetch_assoc($clients)) {
                    echo "<option value='{$c['clientID']}'>{$c['clientfirstname']} {$c['clientsurname']}</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label>Select Property</label>
            <select name="propertyID" class="form-control" required>
                <?php
                $properties = mysqli_query($con, "SELECT * FROM property");
                while ($p = mysqli_fetch_assoc($properties)) {
                    echo "<option value='{$p['propertyID']}'>{$p['propertyname']}</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label>Select Agent</label>
            <select name="agentID" class="form-control" required>
                <?php
                $agents = mysqli_query($con, "SELECT * FROM agenttable");
                while ($a = mysqli_fetch_assoc($agents)) {
                    echo "<option value='{$a['agentID']}'>{$a['agentfirstname']} {$a['agentsurname']}</option>";
                }
                ?>
            </select>
        </div>

        <button type="submit" class="btn btn-dark w-100">Add Appointment</button>
    </form>
</div>

<!-- APPOINTMENTS -->
<h2 class="mb-4">Scheduled Appointments</h2>
<div class="row">
<?php
$appointments = mysqli_query($con, "
    SELECT showings.*,
           client.clientfirstname, client.clientsurname,
           property.propertyname,
           agenttable.agentfirstname, agenttable.agentsurname
    FROM showings
    LEFT JOIN client ON showings.clientID = client.clientID
    LEFT JOIN property ON showings.propertyID = property.propertyID
    LEFT JOIN agenttable ON showings.agentID = agenttable.agentID
");

while ($appt = mysqli_fetch_assoc($appointments)):
    $timeValue = date("H:i", strtotime($appt['appointmenttime']));
    $decisionCode = trim($appt['clientdecision']); // 'Y', 'N', 'U'
?>
    <div class="col-md-4 mb-4">
        <div class="card p-3 shadow-sm appointment-card">
            <div class="card-body">

                <!-- Update Appointment -->
                <form action="appointmentupdate.php" method="post">
                    <input type="hidden" name="appointmentIDToUpdate" value="<?= $appt['appointmentID'] ?>">

                    <div class="form-group">
                        <label>Date</label>
                        <input type="date" name="appointmentdateUpdate" class="form-control"
                               value="<?= $appt['appointmentdate'] ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Time</label>
                        <input type="time" name="appointmenttimeUpdate" class="form-control"
                               value="<?= $timeValue ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Client Decision</label>
                        <select name="clientdecisionUpdate" class="form-control" required>
                            <?php foreach ($decisionMap as $code => $label): 
                                $selected = ($code === $decisionCode) ? "selected" : "";
                            ?>
                                <option value="<?= $code ?>" <?= $selected ?>><?= $label ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Client</label>
                        <select name="clientIDUpdate" class="form-control" required>
                            <?php
                            $clients = mysqli_query($con, "SELECT * FROM client");
                            while ($c = mysqli_fetch_assoc($clients)):
                                $sel = ($c['clientID'] == $appt['clientID']) ? "selected" : "";
                            ?>
                                <option value="<?= $c['clientID'] ?>" <?= $sel ?>><?= $c['clientfirstname'] ?> <?= $c['clientsurname'] ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Property</label>
                        <select name="propertyIDUpdate" class="form-control" required>
                            <?php
                            $properties = mysqli_query($con, "SELECT * FROM property");
                            while ($p = mysqli_fetch_assoc($properties)):
                                $sel = ($p['propertyID'] == $appt['propertyID']) ? "selected" : "";
                            ?>
                                <option value="<?= $p['propertyID'] ?>" <?= $sel ?>><?= $p['propertyname'] ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Agent</label>
                        <select name="agentIDUpdate" class="form-control" required>
                            <?php
                            $agents = mysqli_query($con, "SELECT * FROM agenttable");
                            while ($a = mysqli_fetch_assoc($agents)):
                                $sel = ($a['agentID'] == $appt['agentID']) ? "selected" : "";
                            ?>
                                <option value="<?= $a['agentID'] ?>" <?= $sel ?>><?= $a['agentfirstname'] ?> <?= $a['agentsurname'] ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <button class="btn btn-primary btn-sm w-100 mb-2">Update</button>
                </form>

                <!-- Delete Appointment -->
                <form action="appointmentdelete.php" method="post" onsubmit="return confirm('Are you sure you want to delete this appointment?');">
                    <input type="hidden" name="appointmentIDToDelete" value="<?= $appt['appointmentID'] ?>">
                    <button class="btn btn-danger btn-sm w-100">Delete</button>
                </form>

            </div>
        </div>
    </div>
<?php endwhile; ?>
</div>

</div>

<footer class="bg-dark text-white text-center p-3 mt-5">
    &copy; 2024 Evergreen Estate
</footer>

</body>
</html>
