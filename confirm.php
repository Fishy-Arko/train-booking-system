<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $train_id = $_POST['train_id'];
    $user_name = $_POST['user_name'];
    $email = $_POST['email'];
    $seats = $_POST['seats'];

    $train_query = "SELECT * FROM trains WHERE id=$train_id";
    $train_result = $conn->query($train_query);
    $train = $train_result->fetch_assoc();
    
    $total_price = $seats * $train['price'];

    $sql = "INSERT INTO bookings (train_id, user_name, email, seats, total_price, payment_status) 
            VALUES ('$train_id', '$user_name', '$email', '$seats', '$total_price', 'Unpaid')";

    if ($conn->query($sql)) {
        $booking_id = $conn->insert_id; // Get the ID of the inserted booking
        header("Location: payment.php?booking_id=$booking_id");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>


