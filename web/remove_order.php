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

// Check if the order ID is set in the POST request
if(isset($_POST['order_id'])) {
    // Sanitize the input to prevent SQL injection
    $order_id = mysqli_real_escape_string($conn, $_POST['order_id']);

    // Construct the DELETE query for ordered items
    $items_sql = "DELETE FROM ordered_items WHERE order_id = '$order_id'";
    
    // Execute the query to delete ordered items
    if ($conn->query($items_sql) === TRUE) {
        // Construct the DELETE query for the order itself
        $order_sql = "DELETE FROM orders WHERE id = '$order_id'";
        
        // Execute the query to delete the order
        if ($conn->query($order_sql) === TRUE) {
            echo "Order and associated items removed successfully!";
        } else {
            echo "Error removing order: " . $conn->error;
        }
    } else {
        echo "Error removing ordered items: " . $conn->error;
    }
} else {
    echo "Order ID not provided.";
}

// Close the connection
$conn->close();
?>
