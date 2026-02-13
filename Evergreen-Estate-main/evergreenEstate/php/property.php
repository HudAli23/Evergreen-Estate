<?php
// Page: Property CRUD UI (create + list + inline edit/delete)
// Minimal connection (consider centralizing in a shared config include)
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
    <title>Evergreen Estate | Properties</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .property-card { height: 100%; display: flex; flex-direction: column; }
        .property-card img { object-fit: cover; height: 200px; }
        .property-card .card-body { flex-grow: 1; display: flex; flex-direction: column; }
        .property-card button { margin-top: auto; }
    </style>
</head>
<body>
<header class="bg-dark text-white p-3">
    <div class="container d-flex justify-content-between align-items-center">
        <!-- Logo -->
        <?php
        $logoPath = "images/logo.png"; // Update path as needed
        if (!file_exists($logoPath)) {
            echo "<span style='color:#fff;font-weight:bold;'>Evergreen Estate</span>";
        } else {
            echo "<img src='{$logoPath}' alt='Evergreen Estate' style='height:50px; width:auto;'>";
        }
        ?>

        <!-- Navigation -->
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

    <!-- Add Property Card -->
    <div class="card p-4 mb-5 shadow-sm">
        <h2 class="mb-4">Add Property</h2>
        <form action="propertycreate.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label>Property Name</label>
                <input type="text" name="propertyname" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Price</label>
                <input type="number" name="price" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Bedrooms</label>
                <input type="number" name="bedrooms" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Bathrooms</label>
                <input type="number" name="bathrooms" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Size (sq ft)</label>
                <input type="text" name="size" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <input type="text" name="description" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Property Owner</label>
                <input type="text" name="propertyowner" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Agent</label>
                <select name="agentID" class="form-control" required>
                    <?php
                    $agents = mysqli_query($con, "SELECT * FROM agenttable");
                    while ($agent = mysqli_fetch_assoc($agents)) {
                        echo "<option value='{$agent['agentID']}'>{$agent['agentfirstname']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label>Property Image</label>
                <input type="file" name="propertyimage" class="form-control-file" required>
            </div>
            <button type="submit" class="btn btn-dark w-100">Add Property</button>
        </form>
    </div>

    <hr class="my-5">

    <!-- Existing Properties -->
    <h2 class="mb-4">Available Properties</h2>
    <div class="row">
        <?php
        $properties = mysqli_query($con, "SELECT * FROM property");
        while ($property = mysqli_fetch_assoc($properties)) {
            echo "<div class='col-md-4 mb-4'>";
            echo "<div class='card p-3 shadow-sm property-card'>";
            $imgPath = !empty($property['propertyimage']) ? "../{$property['propertyimage']}" : "../images/placeholder.jpg";
            echo "<img src='{$imgPath}' class='card-img-top mb-2' alt='Property Image'>";

            // Update form
            echo "<form action='propertyupdate.php' method='post' enctype='multipart/form-data'>";
            echo "<input type='hidden' name='propertyIDToUpdate' value='{$property['propertyID']}'>";
            echo "<div class='form-group'><label>Property Name</label>";
            echo "<input type='text' name='propertynameUpdate' class='form-control' value='{$property['propertyname']}' required></div>";
            echo "<div class='form-group'><label>Price</label>";
            echo "<input type='number' name='priceUpdate' class='form-control' value='{$property['price']}' required></div>";
            echo "<div class='form-group'><label>Bedrooms</label>";
            echo "<input type='number' name='bedroomsUpdate' class='form-control' value='{$property['bedrooms']}' required></div>";
            echo "<div class='form-group'><label>Bathrooms</label>";
            echo "<input type='number' name='bathroomsUpdate' class='form-control' value='{$property['bathrooms']}' required></div>";
            echo "<div class='form-group'><label>Size (sq ft)</label>";
            echo "<input type='text' name='sizeUpdate' class='form-control' value='{$property['size']}' required></div>";
            echo "<div class='form-group'><label>Description</label>";
            echo "<input type='text' name='descriptionUpdate' class='form-control' value='{$property['description']}' required></div>";
            echo "<div class='form-group'><label>Property Owner</label>";
            echo "<input type='text' name='propertyownerUpdate' class='form-control' value='{$property['propertyowner']}' required></div>";
            echo "<div class='form-group'><label>Agent</label><select name='agentIDUpdate' class='form-control' required>";
            $agents2 = mysqli_query($con, "SELECT * FROM agenttable");
            while ($agent2 = mysqli_fetch_assoc($agents2)) {
                $selected = ($agent2['agentID'] == $property['agentID']) ? 'selected' : '';
                echo "<option value='{$agent2['agentID']}' $selected>{$agent2['agentfirstname']}</option>";
            }
            echo "</select></div>";
            echo "<div class='form-group'><label>Property Image (optional)</label>";
            echo "<input type='file' name='propertyimageUpdate' class='form-control-file'></div>";
            echo "<button type='submit' class='btn btn-sm btn-primary w-100 mb-2'>Update</button>";
            echo "</form>";

            // Delete form
            echo "<form action='propertydelete.php' method='post' onsubmit=\"return confirm('Are you sure?');\">";
            echo "<input type='hidden' name='propertyIDToDelete' value='{$property['propertyID']}'>";
            echo "<button type='submit' class='btn btn-sm btn-danger w-100'>Delete</button>";
            echo "</form>";

            echo "</div></div>";
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
