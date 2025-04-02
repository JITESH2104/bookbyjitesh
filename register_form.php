<?php
session_start();
$conn = new mysqli("localhost", "root", "", "book_rental");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$successMsg = $errorMsg = "";
$verifiedEmail = isset($_SESSION['verified_email']) ? $_SESSION['verified_email'] : "";
unset($_SESSION['verified_email']); // Remove after use

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = trim($_POST['name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $mobile_number = trim($_POST['mobile_number']);
    $fullAddress = trim($_POST['address']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = $_POST['role'];

    $extraInfo = $_POST['customerPreferences'] ?? "";

    // Insert into database
    $sql = "INSERT INTO users (name, last_name, email, mobile_number, address, password, role) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $firstName, $last_name, $email, $mobile_number, $fullAddress, $password, $role);

    if ($stmt->execute()) {
        $successMsg = "Registration successful! You can now login.";
    } else {
        $errorMsg = "Error: " . $stmt->error;
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 350px;
        }
        h2 {
            color: #333;
        }
        input, select, textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 2px solid #ddd;
            border-radius: 5px;
            outline: none;
            font-size: 16px;
        }
        input:focus, textarea:focus {
            border-color: #007bff;
        }
        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 5px;
            width: 100%;
            font-size: 16px;
        }
        button:hover {
            background-color: #0056b3;
        }
        .message {
            color: green;
            margin: 10px 0;
        }
        .error {
            color: red;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>User Registration</h2>
        <?php if ($successMsg) echo "<p class='message'>$successMsg</p>"; ?>
        <?php if ($errorMsg) echo "<p class='error'>$errorMsg</p>"; ?>

        <form method="POST">
            <input type="text" name="name" required placeholder="First Name">
            <input type="text" name="last_name" required placeholder="Last Name">
            <input type="email" id="email" name="email" required autocomplete="email" placeholder="Email">
            <input type="tel" name="mobile_number" required placeholder="Mobile Number">
            <textarea id="address" name="address" required placeholder="Full Address" onblur="verifyAddress()"></textarea>
            <p id="addressStatus"></p>
            <input type="password" name="password" required placeholder="Password">
            <select name="role" required>
                <option value="">Select Role</option>
                <option value="customer">Customer</option>
                <option value="author">Author</option>
                <option value="librarian">Librarian</option>
                <option value="shopkeeper">Shopkeeper</option>
            </select>
            <button type="submit">Register</button>
        </form>
    </div>

    <script>
        // Autofill email field if session email is available
        document.addEventListener("DOMContentLoaded", function() {
            let emailField = document.getElementById("email");
            let savedEmail = "<?php echo $verifiedEmail; ?>";
            if (savedEmail) {
                emailField.value = savedEmail;
            }
        });

        // Address Verification using OpenStreetMap API
        function verifyAddress() {
            var address = document.getElementById('address').value;
            if (address.length < 5) {
                document.getElementById('addressStatus').innerHTML = "<span style='color:red;'>✖ Address too short</span>";
                return;
            }

            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}`)
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    document.getElementById('addressStatus').innerHTML = `<span style='color:green;'>✔ Verified: ${data[0].display_name}</span>`;
                } else {
                    document.getElementById('addressStatus').innerHTML = "<span style='color:red;'>✖ Invalid Address</span>";
                }
            })
            .catch(error => console.error("Error:", error));
        }
    </script>
</body>
</html>
