<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli("localhost", "root", "", "book_rental");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ✅ Check if the user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] == 0) {
    echo "<script>
        alert('You must be logged in to add a book!');
        window.location.href = 'login.php';
    </script>";
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form values
    $title = isset($_POST['book_title']) ? trim($conn->real_escape_string($_POST['book_title'])) : "";
    $author = isset($_POST['book_author']) ? trim($conn->real_escape_string($_POST['book_author'])) : "";
    $book_writer = isset($_POST['book_writer']) ? trim($conn->real_escape_string($_POST['book_writer'])) : "";
    $book_publisher = isset($_POST['book_publisher']) ? trim($conn->real_escape_string($_POST['book_publisher'])) : "";
    $genre = isset($_POST['book_genre']) ? trim($conn->real_escape_string($_POST['book_genre'])) : "";
    $isbn_10 = isset($_POST['isbn_10']) ? trim($conn->real_escape_string($_POST['isbn_10'])) : "";
    $isbn_13 = isset($_POST['isbn_13']) ? trim($conn->real_escape_string($_POST['isbn_13'])) : "";
    $description = isset($_POST['book_description']) ? trim($conn->real_escape_string($_POST['book_description'])) : "";
    $condition = isset($_POST['book_condition']) ? trim($conn->real_escape_string($_POST['book_condition'])) : "";
    $language = isset($_POST['book_language']) ? trim($conn->real_escape_string($_POST['book_language'])) : "";
    $pages = isset($_POST['book_pages']) ? (int)$_POST['book_pages'] : 0;
    $rent_price = isset($_POST['rent_price']) ? (float)$_POST['rent_price'] : 0.0;
    $full_name = isset($_POST['full_name']) ? trim($conn->real_escape_string($_POST['full_name'])) : "";
    $contact_info = isset($_POST['contact_info']) ? trim($conn->real_escape_string($_POST['contact_info'])) : "";
    $pickup_method = isset($_POST['pickup_method']) ? trim($conn->real_escape_string($_POST['pickup_method'])) : "";

    // ✅ Get individual address components
    $houseNo = isset($_POST['houseNo']) ? trim($conn->real_escape_string($_POST['houseNo'])) : "";
    $street = isset($_POST['street']) ? trim($conn->real_escape_string($_POST['street'])) : "";
    $village = isset($_POST['village']) ? trim($conn->real_escape_string($_POST['village'])) : "";
    $district = isset($_POST['district']) ? trim($conn->real_escape_string($_POST['district'])) : "";
    $state = isset($_POST['state']) ? trim($conn->real_escape_string($_POST['state'])) : "";
    $pincode = isset($_POST['pincode']) ? trim($conn->real_escape_string($_POST['pincode'])) : "";

    // ✅ Concatenate all address components into one string
    $address2 = "$houseNo, $street, $village, $district, $state - $pincode";

    // ✅ Ensure availability_status is always set correctly
    $availability_status = "available"; // Matches ENUM('available', 'rented')

    // Get user ID from session
    $user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
    $add_date = date('Y-m-d');

    // ✅ Handle image upload securely
    $imagePath = '';
    if (isset($_FILES['book_image']) && $_FILES['book_image']['error'] == 0) {
        $imageDir = "uploads/";
        if (!is_dir($imageDir)) {
            mkdir($imageDir, 0777, true);
        }
        $fileName = basename($_FILES['book_image']['name']);
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Allowed file types
        $allowedTypes = ["jpg", "jpeg", "png", "gif"];
        if (in_array($fileExt, $allowedTypes)) {
            $imagePath = $imageDir . uniqid("book_", true) . "." . $fileExt;
            if (!move_uploaded_file($_FILES['book_image']['tmp_name'], $imagePath)) {
                echo "
                <div style='background-color: #f8d7da; color: #721c24; padding: 15px; border: 1px solid #f5c6cb; border-radius: 5px; margin-top: 10px;'>
                    ❌ <strong>Error!</strong> Failed to upload the image. Please try again.
                </div>";
                $imagePath = "";
            }
        } else {
            echo "
            <div style='background-color: #f8d7da; color: #721c24; padding: 15px; border: 1px solid #f5c6cb; border-radius: 5px; margin-top: 10px;'>
                ❌ <strong>Invalid file type!</strong> Only JPG, PNG, and GIF formats are allowed.
            </div>";
            $imagePath = "";
        }
    }

    // ✅ Insert data into the database
    $stmt = $conn->prepare("INSERT INTO books 
        (title, author, book_writer, book_publisher, genre, isbn_10, isbn_13, description, book_condition, address2, language, pages, image_path, rent_price, availability_status, user_id, add_date, full_name, contact_info, pickup_method) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param(
        "sssssssssssississsss",
        $title, $author, $book_writer, $book_publisher, $genre, $isbn_10, $isbn_13, $description,
        $condition, $address2, $language, $pages, $imagePath, $rent_price, $availability_status,
        $user_id, $add_date, $full_name, $contact_info, $pickup_method
    );

    if ($stmt->execute()) {
        echo "
        <div style='background-color: #e7f4e4; color: #2d662f; padding: 15px; border: 1px solid #c3e6cb; border-radius: 5px; margin-top: 10px;'>
            ✅ <strong>Success!</strong> The book has been added successfully.
        </div>";

        // ✅ Redirect to prevent form resubmission
        header("Location: addbook.php?success=true");
        exit();
    } else {
        echo "
        <div style='background-color: #f8d7da; color: #721c24; padding: 15px; border: 1px solid #f5c6cb; border-radius: 5px; margin-top: 10px;'>
            ❌ <strong>Error:</strong> " . $stmt->error . "
        </div>";
    }

    $stmt->close();
    $conn->close();
}
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Book</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-label {
            display: block;
            margin-bottom: 5px;
        }
        .form-input {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
        .form-buttons {
            margin-top: 15px;
        }
        .btn {
            padding: 10px 20px;
            border: none;
            color: #fff;
            background-color: #007BFF;
            cursor: pointer;
            border-radius: 5px;
        }
        .btn-secondary {
            background-color: #6C757D;
        }
        .hidden {
            display: none;
        }
    </style>
</head>
<body>
    <h1>Add New Book</h1>
    <form method="POST" enctype="multipart/form-data" id="bookForm">
        <!-- Step 1: Book Details -->
        <div id="step1">
    <div class="form-group">
        <label class="form-label" for="book_title">Book Title</label>
        <input type="text" id="book_title" name="book_title" class="form-input" required>
    </div>
    <div class="form-group">
        <label class="form-label" for="book_author">Author</label>
        <input type="text" id="book_author" name="book_author" class="form-input" required>
    </div>
    
    <div class="form-group">
        <label class="form-label" for="book_writer">Book Writer</label>
        <input type="text" id="book_writer" name="book_writer" class="form-input" required>
    </div>

    <div class="form-group">
        <label class="form-label" for="book_publisher">Publisher</label>
        <input type="text" id="book_publisher" name="book_publisher" class="form-input" required>
    </div>

    <div class="form-group">
        <label class="form-label" for="book_genre">Genre</label>
        <select id="book_genre" name="book_genre" class="form-input" required>
            <option value="">-- Select Genre --</option>
            <option value="Fiction">Fiction</option>
            <option value="Non-fiction">Non-fiction</option>
            <option value="Mystery">Mystery</option>
            <option value="Science Fiction">Science Fiction</option>
            <option value="Fantasy">Fantasy</option>
            <option value="Romance">Romance</option>
            <option value="Engineering & Medical">Engineering & Medical</option>
            <option value="Government Jobs">Government Jobs</option>
            <option value="Religion & Spirituality">Religion & Spirituality</option>
            <option value="Biography">Biography</option>
            <option value="History">History</option>
            <option value="Children">Children</option>
            <option value="Others">Others</option>
        </select>
    </div>

    <div class="form-group">
        <label class="form-label" for="isbn_10">ISBN Number 10</label>
        <input type="text" id="isbn_10" name="isbn_10" class="form-input">
    </div>

    <div class="form-group">
        <label class="form-label" for="isbn_13">ISBN Number 13</label>
        <input type="text" id="isbn_13" name="isbn_13" class="form-input">
    </div>

    <div class="form-group">
        <label class="form-label" for="book_description">Book Description</label>
        <textarea id="book_description" name="book_description" class="form-input" rows="4" required></textarea>
    </div>

    <div class="form-group">
        <label class="form-label" for="book_condition">Condition</label>
        <select id="book_condition" name="book_condition" class="form-input" required>
            <option value="">-- Select Condition --</option>
            <option value="New">New</option>
            <option value="Like New">Like New</option>
            <option value="Good">Good</option>
            <option value="Acceptable">Acceptable</option>
        </select>
    </div>

    <div class="form-group">
        <label class="form-label" for="book_language">Language</label>
        <select id="book_language" name="book_language" class="form-input" required>
            <option value="English">English</option>
            <option value="Marathi">Marathi</option>
            <option value="Hindi">Hindi</option>
            <option value="Malayalam">Malayalam</option>
            <option value="Telugu">Telugu</option>
            <option value="Tamil">Tamil</option>
            <option value="Kannada">Kannada</option>
            <option value="Gujarati">Gujarati</option>
            <option value="Bengali">Bengali</option>
            <option value="Punjabi">Punjabi</option>
            <option value="Assamese">Assamese</option>
            <option value="Urdu">Urdu</option>
            <option value="Sanskrit">Sanskrit</option>
            <option value="Konkani">Konkani</option>
            <option value="Manipuri">Manipuri</option>
            <option value="Dogri">Dogri</option>
            <option value="Maithili">Maithili</option>
            <option value="Sindhi">Sindhi</option>
            <option value="Bodo">Bodo</option>
        </select>
    </div>

    <div class="form-group">
        <label class="form-label" for="book_pages">Number of Pages</label>
        <input type="number" id="book_pages" name="book_pages" class="form-input" required>
    </div>

    <div class="form-group">
        <label class="form-label" for="book_image">Book Image</label>
        <input type="file" id="book_image" name="book_image" class="form-input" accept="image/*">
    </div>

    <div class="form-buttons">
        <button type="button" class="btn" onclick="showStep2()">Next</button>
        <button type="button" class="btn btn-secondary" onclick="window.location.href='homepage.php'">Cancel</button>
    </div>
</div>

        <!-- Step 2: Rental Details -->
        <div id="step2" class="hidden">
            <div class="form-group">
                <label class="form-label" for="rent_price">Rental Price</label>
                <input type="text" id="rent_price" name="rent_price" class="form-input" required>
            </div>

            <div class="form-group">
    <label class="form-label" for="houseNo">House/Building/Flat No.</label>
    <input type="text" id="houseNo" name="houseNo" class="form-input" required>
</div>

<div class="form-group">
    <label class="form-label" for="street">Street/Locality/Landmark</label>
    <input type="text" id="street" name="street" class="form-input" required>
</div>

<div class="form-group">
    <label class="form-label" for="village">Village/Town/City</label>
    <input type="text" id="village" name="village" class="form-input" required>
</div>

<div class="form-group">
    <label class="form-label" for="district">District</label>
    <input type="text" id="district" name="district" class="form-input" required>
</div>

<div class="form-group">
    <label class="form-label" for="state">State</label>
    <input type="text" id="state" name="state" class="form-input" required>
</div>

<div class="form-group">
    <label class="form-label" for="pincode">PIN Code</label>
    <input type="text" id="pincode" name="pincode" class="form-input" pattern="[0-9]{6}" maxlength="6" required>
</div>


            
            <!-- More fields for step 2 here -->
            <div class="form-buttons">
                <button type="button" class="btn" onclick="showStep3()">Next</button>
                <button type="button" class="btn btn-secondary" onclick="showStep1()">Back</button>
            </div>
        </div>

        <!-- Step 3: User/Owner Details -->
        <div id="step3" class="hidden">
            <div class="form-group">
                <label class="form-label" for="full_name">Full Name</label>
                <input type="text" id="full_name" name="full_name" class="form-input" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="contact_info">Contact Information</label>
                <input type="text" id="contact_info" name="contact_info" class="form-input" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="pickup_method">Pickup/Delivery Method</label>
                <select id="pickup_method" name="pickup_method" class="form-input" required>
                    <option value="">-- Select Method --</option>
                    <option value="Self Pickup">Self Pickup</option>
                    <option value="Home Delivery">Home Delivery</option>
                </select>
            </div>
            <div class="form-buttons">
                <button type="submit" class="btn">Submit</button>
                <button type="button" class="btn btn-secondary" onclick="showStep2()">Back</button>
            </div>
        </div>
    </form>

    <script>
        function showStep2() {
            document.getElementById('step1').classList.add('hidden');
            document.getElementById('step2').classList.remove('hidden');
        }

        function showStep3() {
            document.getElementById('step2').classList.add('hidden');
            document.getElementById('step3').classList.remove('hidden');
        }

        function showStep1() {
            document.getElementById('step2').classList.add('hidden');
            document.getElementById('step1').classList.remove('hidden');
        }
    </script>
</body>
</html>
