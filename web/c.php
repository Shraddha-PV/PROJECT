<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Orders</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            margin-top: 20px;
            margin-bottom: 20px;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
        }

        th,
        td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: left;
        }

        td {
            text-align: left;
            vertical-align: middle;
        }

        td p {
            margin: 0;
            font-size: 14px;
        }

        td button {
            padding: 8px 16px;
            background-color: #dc3545;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-size: 14px;
        }

        td button:hover {
            background-color: #c82333;
        }

        .no-orders {
            text-align: center;
            padding: 20px;
            color: #777;
        }

        .no-orders h2 {
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Orders</h1>
        <div class="orders-container">
            <?php
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "store";

            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql = "SELECT orders.*, SUM(ordered_items.item_price) AS total_price FROM orders LEFT JOIN ordered_items ON orders.id = ordered_items.order_id GROUP BY orders.id";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<table>";
                echo "<tr><th>Order #</th><th>Name</th><th>Phone</th><th>Address</th><th>Ordered Items</th><th>Total Price</th><th>Order time</th><th>Action</th></tr>";
                $orderNumber = 1;
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $orderNumber . "</td>";
                    echo "<td>" . $row["name"] . "</td>";
                    echo "<td>" . $row["phone"] . "</td>";
                    echo "<td>" . $row["address"] . "</td>";
                    echo "<td>";
                    $orderId = $row["id"];
                    $itemsSql = "SELECT * FROM ordered_items WHERE order_id = '$orderId'";
                    $itemsResult = $conn->query($itemsSql);
                    if ($itemsResult->num_rows > 0) {
                        while ($item = $itemsResult->fetch_assoc()) {
                            echo "<p><strong>Name:</strong> " . $item["item_name"] . ", <strong>Price:</strong> " . $item["item_price"] . "</p>";
                        }
                    } else {
                        echo "No items found for this order.";
                    }
                    echo "</td>";
                    echo "<td>₹" . number_format($row["total_price"], 2) . "</td>";
                    echo isset($row["created_at"]) ? "<td>" . $row["created_at"] . "</td>" : "<td>N/A</td>";
                    echo "<td><button onclick='removeOrder(" . $row["id"] . ")'>Delete</button></td>";
                    echo "</tr>";
                    $orderNumber++;
                }
                echo "</table>";
            } else {
                echo "<div class='no-orders'><h2>No orders found</h2><p>There are currently no orders in the database.</p></div>";
            }

            $conn->close();
            ?>
        </div>
    </div>

    <script>
        function removeOrder(orderId) {
            if (confirm('Are you sure you want to delete this order?')) {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'remove_order.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        alert(xhr.responseText);
                        window.location.reload();
                    } else {
                        alert('Error deleting order. Please try again later.');
                    }
                };
                xhr.send('order_id=' + orderId);
            }
        }
    </script>
</body>

</html>
