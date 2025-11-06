<?php
include('connection.php');
date_default_timezone_set("Asia/Kolkata");

if(isset($_POST['name'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $date = $_POST['date'];
    $dest = $_POST['dest'];
    $password = $_POST['password'];
    $tripType = $_POST['tripType']; // "oneway" or "round"

    // 1️⃣ Fetch price for selected destination
    $stmt2 = $conn->prepare("SELECT price FROM destination WHERE name = ?");
    $stmt2->bind_param("s", $dest);
    $stmt2->execute();
    $result = $stmt2->get_result();

    if($row = $result->fetch_assoc()){
        $price = $row['price'];
        $amt = ($tripType === "round") ? $price * 2 : $price;
    } else {
        $amt = 0;
    }

    // 2️⃣ Insert pass into database with paid amount
    $stmt = $conn->prepare("INSERT INTO pass (name, email, contact, date, dest, password, tripType, paid) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssd", $name, $email, $contact, $date, $dest, $password, $tripType, $amt);

    if($stmt->execute()){
        $lid = $stmt->insert_id; // Last inserted ID
    } else {
        die("Error inserting pass: " . $conn->error);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Cloud Based Bus Pass System - Payment</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Payment</h2>
    <p><strong>Destination:</strong> <?php echo htmlspecialchars($dest); ?></p>
    <p><strong>Trip Type:</strong> <?php echo ($tripType === "round") ? "Round Trip" : "One Way"; ?></p>
    <p><strong>Total Amount:</strong> Rs. <?php echo number_format($amt, 2); ?></p>

    <form action="invoice.php" method="post">
        <input type="hidden" name="id" value="<?php echo $lid; ?>">
        <input type="hidden" name="amt" value="<?php echo $amt; ?>">
        <input type="hidden" name="tripType" value="<?php echo $tripType; ?>">

        <div class="mb-3">
            <label>Name on Card</label>
            <input type="text" name="cardname" class="form-control" value="<?php echo htmlspecialchars($name); ?>" required>
        </div>
        <div class="mb-3">
            <label>Card Number</label>
            <input type="text" name="cardnumber" class="form-control" placeholder="1111-2222-3333-4444" required>
        </div>
        <div class="row">
            <div class="col">
                <label>Exp Month</label>
                <input type="text" name="expmonth" class="form-control" placeholder="MM" required>
            </div>
            <div class="col">
                <label>Exp Year</label>
                <input type="text" name="expyear" class="form-control" placeholder="YYYY" required>
            </div>
        </div>
        <div class="mb-3">
            <label>CVV</label>
            <input type="password" name="cvv" class="form-control" placeholder="***" required>
        </div>
        <button type="submit" class="btn btn-primary">Continue to Checkout</button>
    </form>
</div>
</body>
</html>
