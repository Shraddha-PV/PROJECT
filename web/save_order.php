<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "store";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve form data
$name = $_POST['name'];
$phone = $_POST['phone'];
$address = $_POST['address'];
$orderedItems = $_POST['orderedItems']; // This will contain a JSON string representing the ordered items

// Insert order details into the database
$sql = "INSERT INTO orders (name, phone, address) VALUES ('$name', '$phone', '$address')";

if ($conn->query($sql) === TRUE) {
    // Get the last inserted order ID
    $lastOrderId = $conn->insert_id;

    // Decode the JSON string containing the ordered items
    $orderedItemsArray = json_decode($orderedItems, true);

    // Insert each ordered item into the database
    foreach ($orderedItemsArray as $item) {
        $itemName = $item['name'];
        $itemPrice = $item['price'];
        $sql = "INSERT INTO ordered_items (order_id, item_name, item_price) VALUES ('$lastOrderId', '$itemName', '$itemPrice')";
        $conn->query($sql);
    }

    echo "Order submitted successfully!";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
