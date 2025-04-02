<?php
session_start();
include 'C:\xampp\htdocs\book\database_connection.php'; // Database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please log in first!'); window.location.href='login.php';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user rentals
$query = "SELECT r.id AS rental_id, r.return_status, r.return_date, r.rental_days, b.title, b.author 
          FROM rentals r
          JOIN books b ON r.id = b.id
          WHERE r.renter_id = ?
          ORDER BY r.return_status ASC, r.return_date DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Rented Books</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <div class="row mb-4">
            <div class="col">
                <h2>My Rented Books</h2>
            </div>
            <div class="col-auto">
                <a href="browse_books.php" class="btn btn-primary">Browse Books</a>
            </div>
        </div>
        
        <?php if ($result->num_rows > 0) { ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Book Title</th>
                            <th>Author</th>
                            <th>Return Status</th>
                            <th>Return Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['title']); ?></td>
                                <td><?php echo htmlspecialchars($row['author']); ?></td>
                                <td>
                                    <?php if ($row['return_status'] == 'Pending') { ?>
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    <?php } else { ?>
                                        <span class="badge bg-success">Returned</span>
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php
                                    if (!empty($row['return_date'])) {
                                        echo htmlspecialchars($row['return_date']);
                                    } else if (!empty($row['due_date'])) {
                                        $due_date = new DateTime($row['due_date']);
                                        $today = new DateTime();
                                        $days_remaining = $today->diff($due_date)->days;
                                        $is_overdue = $today > $due_date;
                                        
                                        if ($is_overdue) {
                                            echo '<span class="text-danger">Overdue by ' . $days_remaining . ' days</span>';
                                        } else {
                                            echo '<span class="text-info">' . $days_remaining . ' days remaining</span>';
                                        }
                                    } else {
                                        echo "Not Returned";
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php if ($row['return_status'] == 'Pending') { ?>
                                        <form method="POST" action="return_book.php" class="d-inline">
                                            <input type="hidden" name="rental_id" value="<?php echo $row['rental_id']; ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-primary"
                                                    onclick="return confirm('Are you sure you want to return this book?')">
                                                Return Book
                                            </button>
                                        </form>
                                    <?php } else { ?>
                                        <span class="text-muted">Returned</span>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        <?php } else { ?>
            <div class="alert alert-info">
                <p>You haven't rented any books yet.</p>
                <a href="browse_books.php" class="btn btn-primary mt-2">Browse Available Books</a>
            </div>
        <?php } ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
