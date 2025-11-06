<?php
include('connection.php');

// Fetch destinations and prices from DB
$sql = "SELECT name, price FROM destination";
$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Cloud Based Bus Pass System</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Book Your Pass</h2>
    <form action="billing.php" method="post">
        <div class="mb-3">
            <label>Name</label>
            <input required type="text" name="name" class="form-control">
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input required type="email" name="email" class="form-control">
        </div>
        <div class="mb-3">
            <label>Mobile Number</label>
            <input required type="text" name="contact" class="form-control">
        </div>
        <div class="mb-3">
            <label>Password</label>
            <input required type="password" name="password" class="form-control">
        </div>
        <div class="mb-3">
            <label>Valid Till</label>
            <input required type="date" name="date" class="form-control">
        </div>
        <div class="mb-3">
            <label>Destination</label>
            <select name="dest" class="form-control" required>
                <?php while($row = $result->fetch_assoc()): ?>
                    <option value="<?= htmlspecialchars($row['name']) ?>">
                        <?= htmlspecialchars($row['name']) ?> (Rs. <?= $row['price'] ?>)
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label>Trip Type</label>
            <select name="tripType" class="form-control" required>
                <option value="oneway">One Way</option>
                <option value="round">Round Trip (Up & Down)</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Proceed To Checkout</button>
    </form>
</div>
</body>
</html>
