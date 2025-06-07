<?php
include 'db.php';

// Get ticket ID from URL
$ticket_id = isset($_GET['ticket_id']) ? $_GET['ticket_id'] : '';

if (!$ticket_id) {
    die("Invalid Ticket ID!");
}

// Fetch booking details
$sql = "SELECT b.*, t.train_name, t.source, t.destination 
        FROM bookings b 
        JOIN trains t ON b.train_id = t.id 
        WHERE b.ticket_id = '$ticket_id'";

$result = $conn->query($sql);
if ($result->num_rows == 0) {
    die("Ticket not found!");
}

$booking = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Train Ticket</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="ticket-container">
        <h2>Train Ticket</h2>
        <div class="ticket">
            <p><strong>Ticket ID:</strong> <?= $booking['ticket_id'] ?></p>
            <p><strong>Passenger Name:</strong> <?= $booking['user_name'] ?></p>
            <p><strong>Email:</strong> <?= $booking['email'] ?></p>
            <p><strong>Train:</strong> <?= $booking['train_name'] ?></p>
            <p><strong>Route:</strong> <?= $booking['source'] ?> → <?= $booking['destination'] ?></p>
            <p><strong>Date of Journey:</strong> <?= $booking['booking_date'] ?></p>
            <p><strong>Seats:</strong> <?= $booking['seats'] ?></p>
            <p><strong>Total Price:</strong> ₹<?= $booking['total_price'] ?></p>
        </div>
        <button onclick="window.print()">Print Ticket</button>
    </div>
</body>
</html>
