<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "realestate";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection Error: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $propertyname   = $_POST["propertyname"];
    $price          = $_POST["price"];
    $size           = $_POST["size"];
    $bedrooms       = $_POST["bedrooms"];
    $bathrooms      = $_POST["bathrooms"];
    $description    = $_POST["description"];
    $propertyowner  = $_POST["propertyowner"];
    $agentID        = $_POST["agentID"];

    // Basic validation
    if (
        empty($propertyname) || empty($price) || empty($size) ||
        empty($bedrooms) || empty($bathrooms) || empty($description) ||
        empty($propertyowner) || empty($agentID)
    ) {
        die("All fields are required.");
    }

    // Image upload
    if (!empty($_FILES["propertyimage"]["name"])) {

        $uploadDir = "../images/";

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $imageName = basename($_FILES["propertyimage"]["name"]);
        $targetPath = $uploadDir . $imageName;

        if (move_uploaded_file($_FILES["propertyimage"]["tmp_name"], $targetPath)) {

            // Path saved in DB (relative to project)
            $imagePathForDB = "images/" . $imageName;

            // Insert property into database
            $stmt = $conn->prepare("
                INSERT INTO property
                (agentID, propertyname, price, bedrooms, bathrooms, size, description, propertyowner, propertyimage)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");

            $stmt->bind_param(
                "isiiissss",
                $agentID,
                $propertyname,
                $price,
                $bedrooms,
                $bathrooms,
                $size,
                $description,
                $propertyowner,
                $imagePathForDB
            );

            if ($stmt->execute()) {
                // Redirect to property page after successful insert
                header("Location: property.php");
                exit(); // Important to stop script execution
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();

        } else {
            die("Failed to upload image.");
        }

    } else {
        die("Property image is required.");
    }
}

$conn->close();
?>
