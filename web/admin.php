<?php
// session_start();


// if(isset($_SESSION['username'])) {
//     // Destroy the session
//     session_unset();
//     session_destroy();
// }
$error_message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $conn = mysqli_connect('localhost', 'root', '', 'food_orders');
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $query = "SELECT * FROM admin WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $query);
    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }
    if (mysqli_num_rows($result) === 1) {
        $_SESSION['username'] = $username;
        header('Location: c.php');
        exit();
    } else {
        $error_message = "Invalid username or password";
    }
    mysqli_close($conn);
}

// Check if error message is set and display it
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Head content here -->

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Open Sans", sans-serif;
        }

        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            width: 100%;
            padding: 0 10px;
        }

        body::before {
            content: "";
            position: absolute;
            width: 100%;
            height: 100%;
            background: url("admin.jpg"), #000;
            background-position: center;
            background-size: cover;
        }

        .wrapper {
            width: 400px;
            border-radius: 8px;
            padding: 30px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(9px);
            -webkit-backdrop-filter: blur(9px);
        }

        form {
            display: flex;
            flex-direction: column;
        }

        h2 {
            font-size: 6vh;
            margin-bottom: 20px;
            text-transform: uppercase;
            font-family: 'Times New Roman', Times, serif;
            color: #fff;
        }

        .input-field {
            position: relative;
            border-bottom: 2px solid #ccc;
            margin: 15px 0;
        }

        .input-field label {
            position: absolute;
            top: 50%;
            left: 0;
            transform: translateY(-50%);
            color: #fff;
            font-size: 16px;
            pointer-events: none;
            transition: 0.15s ease;
        }

        .input-field input {
            width: 100%;
            height: 40px;
            background: transparent;
            border: none;
            outline: none;
            font-size: 16px;
            color: #fff;
        }

        .input-field input:focus~label,
        .input-field input:valid~label {
            font-size: 2vh;
            top: 1vh;
            transform: translateY(-120%);
        }

        .show_password {
            display: flex;
            align-items: center;
            flex-direction: row;
            justify-content: start;
            margin: 25px 0 35px 0;
            color: #fff;
        }

        #showPasswordCheckbox {
            accent-color: #fff;
        }

        .show_password p {
            margin-left: 8px;
        }

        button {
            background: #fff;
            color: #000;
            font-weight: 600;
            border: none;
            padding: 12px 20px;
            cursor: pointer;
            border-radius: 3px;
            font-size: 16px;
            border: 2px solid transparent;
            transition: 0.3s ease;
        }

        button:hover {
            color: #fff;
            border-color: #fff;
            background: rgba(255, 255, 255, 0.15);
        }
        h1{
            margin-bottom: 20px;
            text-transform: uppercase;
            font-family: Times;
            color: #fff;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <form method="POST" action="admin.php">
            <h1>admin panel</h1>
            <!-- Form fields -->
            <div class="input-field">
                <input type="text" name="username" id="username" required>
                <label>Enter Your username</label>
            </div>
            <div class="input-field">
                <input type="password" name="password" id="password" required>
                <label>Enter Your Password</label>
            </div>
            <?php if (!empty($error_message)) : ?>
                <div id="error-message" style="color: red; font-weight:700;  margin-top: 10px; font-style: italic; "><?php echo $error_message; ?></div>
            <?php endif; ?>
            <div class="show_password">
                <input type="checkbox" onclick="togglePasswordVisibility()">
                <p style=" font-style:italic">Show Password</p>
            </div>
            <button type="submit">LOG IN</button>
        </form>
    </div>

    <script>
        function togglePasswordVisibility() {
            var passwordInput = document.getElementById("password");
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
            } else {
                passwordInput.type = "password";
            }
        }
    </script>
</body>

</html>