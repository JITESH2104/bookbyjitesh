

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select City Type - Parcel Dispatch</title>
    <style>
        body {
            font-family: 'Poppins', Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 100px auto;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        h2 {
            margin-bottom: 20px;
            color: #333;
        }
        .city-box {
            width: 100%;
            padding: 20px;
            margin-top: 20px;
            border: 2px solid #ddd;
            border-radius: 12px;
            background-color: #f1f1f1;
            cursor: pointer;
            transition: 0.3s ease-in-out;
        }
        .city-box:hover {
            background-color: #e9ecef;
            border-color: #007bff;
        }
        .city-box h3 {
            margin: 0;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Select City Type for Parcel Dispatch</h2>

        <!-- Inner City Box -->
        <div class="city-box" onclick="redirectTo('inner_city.php')">
            <h3>📦 Dispatch to Inner City</h3>
        </div>

        <!-- Outer City Box -->
        <div class="city-box" onclick="redirectTo('outer.php')">
            <h3>🚚 Dispatch to Outer City</h3>
        </div>
    </div>

    <script>
        // ✅ Redirect to the appropriate page based on user selection
        function redirectTo(page) {
            window.location.href = page;
        }
    </script>
</body>
</html>
