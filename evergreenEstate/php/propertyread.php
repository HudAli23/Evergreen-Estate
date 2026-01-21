<?php
$properties = mysqli_query($con, "SELECT * FROM property");

if (mysqli_num_rows($properties) > 0) {
    while ($property = mysqli_fetch_assoc($properties)) {
        echo "<p>PropertyID: {$property['propertyID']}</p>";
        echo "<p>AgentID: {$property['agentID']}</p>";
        echo "<h3>Propertyname: {$property['propertyname']}</h3>";
        echo "<p>Price: {$property['price']}</p>";
        echo "<p>Bedrooms: {$property['bedrooms']}</p>";
        echo "<p>Bathrooms : {$property['bathrooms']}</p>";
        echo "<p>Size : {$property['size']}</p>";
        echo "<p>Description : {$property['description']}</p>";
        echo "<p>Propertyowner : {$property['propertyowner']}</p>";
         if (!empty($property['propertyimage'])) {
    echo "<img src='../{$property['propertyimage']}' alt='{$property['propertyname']}' class='img-fluid'>";
}


        echo "<hr>";
    }
} else {
    echo "No properties found.";
}

mysqli_close($con);
?>
