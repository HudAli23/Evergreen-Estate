<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "realestate";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection Error: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST["propertyIDToDelete"])) {

    $propertyID = intval($_POST["propertyIDToDelete"]);

    // 1️⃣ Get image path first
    $imgStmt = $conn->prepare("SELECT propertyimage FROM property WHERE propertyID=?");
    $imgStmt->bind_param("i", $propertyID);
    $imgStmt->execute();
    $imgStmt->bind_result($imagePath);
    $imgStmt->fetch();
    $imgStmt->close();

    // 2️⃣ Delete image file (if exists)
    if (!empty($imagePath)) {
        $fullPath = "../" . $imagePath;
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
    }

    // Delete database record
    $stmt = $conn->prepare("DELETE FROM property WHERE propertyID=?");
    $stmt->bind_param("i", $propertyID);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header("Location: property.php");
        exit();
    } else {
        echo "Delete error: " . $stmt->error;
    }
} else {
    echo "Invalid delete request.";
}

$conn->close();
?>
