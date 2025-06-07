<?php
include 'db.php';

$ticket_id = $_GET['ticket_id'] ?? '';

// Validate ticket ID and fetch booking info
$booking_query = "SELECT id, total_price FROM bookings WHERE ticket_id = '$ticket_id'";
$booking_result = mysqli_query($conn, $booking_query);
$booking = mysqli_fetch_assoc($booking_result);
$booking_id = $booking['id'] ?? null;
$amount = $booking['total_price'] ?? 0;

if (!$booking_id) {
    die("Invalid ticket or booking not found.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $method = $_POST['method'];

    // Insert into payments
    $insert = "INSERT INTO payments (booking_id, amount, payment_method, payment_status)
               VALUES ('$booking_id', '$amount', '$method', 'completed')";

    // Update bookings table
    $update = "UPDATE bookings SET payment_status = 'paid' WHERE id = '$booking_id'";

    if (mysqli_query($conn, $insert) && mysqli_query($conn, $update)) {
        echo "<script>alert('Demo Payment Successful!'); window.location.href='ticket.php?ticket_id=$ticket_id';</script>";
        exit;
    } else {
        echo "Payment error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Demo Payment</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #4b0082, #8a2be2);
            color: white;
            text-align: center;
            padding-top: 60px;
        }
        form {
            background: rgba(255, 255, 255, 0.1);
            display: inline-block;
            padding: 30px;
            border-radius: 12px;
        }
        select, input, input[type="submit"], input[type="text"], input[type="password"], input[type="month"] {
            padding: 10px;
            margin: 10px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            width: 250px;
        }
        input[type="submit"] {
            background-color: #ff5722;
            color: white;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #e64a19;
        }
        .hidden {
            display: none;
        }
    </style>
    <script>
        function togglePaymentFields() {
            const method = document.getElementById("method").value;
            document.getElementById("upi").classList.add("hidden");
            document.getElementById("card").classList.add("hidden");

            if (method === "UPI") {
                document.getElementById("upi").classList.remove("hidden");
            } else if (method === "Credit Card" || method === "Debit Card") {
                document.getElementById("card").classList.remove("hidden");
            }
        }

        function validateForm() {
            const method = document.getElementById("method").value;

            if (!method) {
                alert("Please select a payment method.");
                return false;
            }

            if (method === "UPI") {
                const upi = document.querySelector("[name='upi_id']").value;
                if (!upi.trim()) {
                    alert("Enter UPI ID.");
                    return false;
                }
            }

            if (method === "Credit Card" || method === "Debit Card") {
                const card = document.querySelector("[name='card_number']").value;
                const cvv = document.querySelector("[name='cvv']").value;
                const expiry = document.querySelector("[name='expiry']").value;

                if (!card || !cvv || !expiry) {
                    alert("Enter all card details.");
                    return false;
                }
            }

            return true;
        }
    </script>
</head>
<body>
    <h1>Secure Demo Payment</h1>
    <form method="POST" onsubmit="return validateForm()">
        <label>Payment Method:</label><br>
        <select name="method" id="method" onchange="togglePaymentFields()" required>
            <option value="">--Select--</option>
            <option value="UPI">UPI</option>
            <option value="Credit Card">Credit Card</option>
            <option value="Debit Card">Debit Card</option>
            <option value="Net Banking">Net Banking</option>
        </select><br>

        <div id="upi" class="hidden">
            <input type="text" name="upi_id" placeholder="Enter UPI ID">
        </div>

        <div id="card" class="hidden">
            <input type="text" name="card_number" maxlength="16" placeholder="Card Number"><br>
            <input type="password" name="cvv" maxlength="3" placeholder="CVV"><br>
            <input type="month" name="expiry" placeholder="Expiry Date"><br>
        </div>

        <label>Amount (₹):</label><br>
        <input type="text" value="₹<?= $amount ?>" readonly><br>

        <input type="submit" value="Simulate Payment">
    </form>
</body>
</html>

