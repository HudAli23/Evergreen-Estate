<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "realestate";

// Connect to database
$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection Error: " . $conn->connect_error);
}

// Only process POST requests
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Ensure property ID is provided
    if (!isset($_POST["propertyIDToUpdate"]) || empty($_POST["propertyIDToUpdate"])) {
        die("Property ID is missing.");
    }

    $propertyID     = intval($_POST["propertyIDToUpdate"]);
    $propertyname   = isset($_POST["propertynameUpdate"]) ? trim($_POST["propertynameUpdate"]) : '';
    $price          = isset($_POST["priceUpdate"]) ? floatval($_POST["priceUpdate"]) : 0;
    $size           = isset($_POST["sizeUpdate"]) ? floatval($_POST["sizeUpdate"]) : 0;
    $bedrooms       = isset($_POST["bedroomsUpdate"]) ? intval($_POST["bedroomsUpdate"]) : 0;
    $bathrooms      = isset($_POST["bathroomsUpdate"]) ? intval($_POST["bathroomsUpdate"]) : 0;
    $description    = isset($_POST["descriptionUpdate"]) ? trim($_POST["descriptionUpdate"]) : '';
    $propertyowner  = isset($_POST["propertyownerUpdate"]) ? trim($_POST["propertyownerUpdate"]) : '';

    // Validate required fields
    if (empty($propertyname) || $price <= 0 || $size <= 0 || $bedrooms < 0 || $bathrooms < 0 || empty($description) || empty($propertyowner)) {
        die("Please fill all required fields correctly.");
    }

    // Handle optional image upload
    $imageSQL = "";
    $imagePathForDB = "";

    if (!empty($_FILES["propertyimageUpdate"]["name"])) {

        $uploadDir = "../images/";
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $imageName = basename($_FILES["propertyimageUpdate"]["name"]);
        $imageExt = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
        $allowed = ["jpg", "jpeg", "png", "gif"];

        if (!in_array($imageExt, $allowed)) {
            die("Invalid image format. Allowed: jpg, jpeg, png, gif.");
        }

        $targetPath = $uploadDir . $imageName;
        if (!move_uploaded_file($_FILES["propertyimageUpdate"]["tmp_name"], $targetPath)) {
            die("Failed to upload image.");
        }

        $imagePathForDB = "images/" . $imageName;
        $imageSQL = ", propertyimage=?";
    }

    // Build SQL dynamically based on image upload
    $sql = "UPDATE property SET
            propertyname=?,
            price=?,
            size=?,
            bedrooms=?,
            bathrooms=?,
            description=?,
            propertyowner=?
            $imageSQL
            WHERE propertyID=?";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    // Bind parameters based on whether image is uploaded
    if (!empty($imageSQL)) {
        $stmt->bind_param(
            "sdiissssi",
            $propertyname,
            $price,
            $size,
            $bedrooms,
            $bathrooms,
            $description,
            $propertyowner,
            $imagePathForDB,
            $propertyID
        );
    } else {
        $stmt->bind_param(
            "sdiisssi",
            $propertyname,
            $price,
            $size,
            $bedrooms,
            $bathrooms,
            $description,
            $propertyowner,
            $propertyID
        );
    }

    // Execute statement and redirect
    if ($stmt->execute()) {
        header("Location: property.php");
        exit();
    } else {
        die("Update error: " . $stmt->error);
    }

    $stmt->close();
}

$conn->close();
?>
