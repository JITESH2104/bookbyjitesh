<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dispatch Inner City Parcel</title>
    <style>
        body {
            font-family: 'Poppins', Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 500px;
            margin: 50px auto;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1);
            position: relative;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        label {
            font-weight: 600;
            margin-top: 10px;
            display: block;
        }
        input {
            width: 100%;
            padding: 10px;
            margin-top: 8px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        .button-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        button {
            width: 48%;
            padding: 10px;
            font-size: 12px;
            border-radius: 8px;
            cursor: pointer;
        }
        button.send {
            background-color: #28a745;
            color: white;
            border: none;
        }
        button.send:hover {
            background-color: #218838;
        }
        button.reset-btn {
            background-color: #dc3545;
            color: white;
            border: none;
        }
        button.reset-btn:hover {
            background-color: #c82333;
        }
        .btn-home {
            display: inline-block;
            margin-top: 10px;
            text-decoration: none;
            color: #fff;
            background-color: #007bff;
            padding: 10px 20px;
            border-radius: 8px;
        }
        .btn-home:hover {
            background-color: #0056b3;
        }
        .outer-city-link {
            position: absolute;
            top: 10px;
            right: 10px;
            text-decoration: none;
            color: #007bff;
            font-size: 12px;
            padding: 5px 10px;
            border-radius: 4px;
            background-color: #f1f1f1;
            border: 1px solid #007bff;
        }
        .outer-city-link:hover {
            color: #0056b3;
            background-color: #e0e0e0;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Outer City Link -->
        <a href="outer.php" class="outer-city-link">Outer City</a>
        
        <h2>Dispatch Parcel - Inner City</h2>
        <form action="process_inner_city.php" method="post" id="innerCityForm">

            <!-- Mobile Number -->
            <label for="mobile_number">Mobile Number:</label>
            <input type="tel" name="mobile_number" id="mobile_number" placeholder="Enter mobile number" pattern="[0-9]{10}" required>

            <!-- Meeting Address -->
            <label for="meeting_address">Meeting Address:</label>
            <input type="text" name="meeting_address" id="meeting_address" placeholder="Enter meeting address (e.g., Lane No, Landmark)" required>

            <!-- Meeting Time -->
            <label for="meeting_time">Meeting Time:</label>
            <input type="datetime-local" name="meeting_time" id="meeting_time" required>

            <!-- Button Container -->
            <div class="button-container">
                <!-- Dispatch Button -->
                <button type="submit" name="dispatch" class="send">Send Address</button>

                <!-- Reset Button -->
                <button type="button" class="reset-btn" onclick="resetForm()">Reset Form</button>
            </div>
        </form>
        
        <!-- Back to Home Button -->
        <a href="homepage.php" class="btn-home">🏠 Go to Homepage</a>
    </div>

    <script>
        // ✅ Reset form without page refresh
        function resetForm() {
            document.getElementById("innerCityForm").reset();
        }
    </script>
</body>
</html>
