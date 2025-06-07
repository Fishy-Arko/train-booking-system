<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Train Booking</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="search-container">
        <h1>Train Booking Management System</h1>
        <form action="search.php" method="GET">
            <label>From:</label>
            <input type="text" name="from" required placeholder="Enter departure city">
            <label>To:</label>
            <input type="text" name="to" required placeholder="Enter destination city">
            <button type="submit">Search Trains</button>
        </form>
    </div>
</body>
</html>
