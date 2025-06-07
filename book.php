<?php
include 'db.php';

// Get selected train from URL (if any)
$selected_train_id = isset($_GET['train_id']) ? $_GET['train_id'] : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $train_id = $_POST['train_id'];
    $user_name = $_POST['user_name'];
    $email = $_POST['email'];
    $seats = $_POST['seats'];
    $booking_date = $_POST['booking_date'];

    // Fetch train details
    $train_query = "SELECT * FROM trains WHERE id = $train_id";
    $train_result = $conn->query($train_query);
    $train = $train_result->fetch_assoc();
    $total_price = $train['price'] * $seats;

    // Generate a unique ticket ID
    $ticket_id = strtoupper(substr($user_name, 0, 3)) . rand(1000, 9999);

    // Insert booking into database
    $sql = "INSERT INTO bookings (train_id, user_name, email, seats, total_price, booking_date, ticket_id) 
            VALUES ('$train_id', '$user_name', '$email', '$seats', '$total_price', '$booking_date', '$ticket_id')";

    if ($conn->query($sql)) {
        // Redirect to payment page with ticket ID and amount
        header("Location: payment.php?ticket_id=$ticket_id&amount=$total_price");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

// Fetch available trains
$trains = $conn->query("SELECT * FROM trains");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Train</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Book Your Train Ticket</h2>
        <form method="POST">
            <label>Select Train:</label>
            <select name="train_id" required>
                <?php while ($train = $trains->fetch_assoc()) { ?>
                    <option value="<?= $train['id'] ?>" <?= ($train['id'] == $selected_train_id) ? 'selected' : '' ?>>
                        <?= $train['train_name'] ?> (<?= $train['source'] ?> → <?= $train['destination'] ?>) - ₹<?= $train['price'] ?>
                    </option>
                <?php } ?>
            </select>

            <label>Your Name:</label>
            <input type="text" name="user_name" required placeholder="Enter your full name">

            <label>Email:</label>
            <input type="email" name="email" required placeholder="Enter your email">

            <label>Seats:</label>
            <input type="number" name="seats" min="1" required placeholder="Number of seats">

            <label>Booking Date:</label>
            <input type="date" name="booking_date" required 
                   min="<?= date('Y-m-d', strtotime('+1 month')) ?>" 
                   max="<?= date('Y-m-d', strtotime('+3 months')) ?>">

            <button type="submit">Book Now</button>
        </form>
    </div>
</body>
</html>
