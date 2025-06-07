<?php
include 'db.php';

$from = $_GET['from'];
$to = $_GET['to'];

$sql = "SELECT * FROM trains WHERE source='$from' AND destination='$to'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Trains</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="train-list">
        <h1>Available Trains from <?= $from ?> to <?= $to ?></h1>
        <?php if ($result->num_rows > 0) { ?>
            <?php while ($train = $result->fetch_assoc()) { ?>
                <div class="train-card">
                    <h3><?= $train['train_name'] ?></h3>
                    <p><strong>Route:</strong> <?= $train['source'] ?> → <?= $train['destination'] ?></p>
                    <p><strong>Departure:</strong> <?= $train['departure_time'] ?></p>
                    <p><strong>Price:</strong> ₹<?= $train['price'] ?></p>
                    <a href="book.php?train_id=<?= $train['id'] ?>" class="book-btn">Book Now</a>
                </div>
            <?php } ?>
        <?php } else { ?>
            <p>No trains found for this route.</p>
        <?php } ?>
    </div>
</body>
</html>

