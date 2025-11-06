<?php
include("connection.php"); // mysqli connection
date_default_timezone_set("Asia/Kolkata");

$amt = 0;

if(isset($_POST['id']) && isset($_POST['new_date'])){
    $id = (int)$_POST['id'];
    $new_date = $_POST['new_date'];

    // Fetch current pass details
    $stmt = $conn->prepare("SELECT * FROM pass WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if($row){
        // Fetch destination price
        $stmt2 = $conn->prepare("SELECT price FROM destination WHERE name = ?");
        $stmt2->bind_param("s", $row['dest']);
        $stmt2->execute();
        $priceResult = $stmt2->get_result()->fetch_assoc();
        $price = $priceResult['price'] ?? 0;

        // Renewal amount is **same as original pass**, ignore number of days
        $amt = ($row['tripType'] === 'round') ? $price * 2 : $price;

        // Update pass validity date
        $stmt3 = $conn->prepare("UPDATE pass SET date = ? WHERE id = ?");
        $stmt3->bind_param("si", $new_date, $id);
        $stmt3->execute();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bus Pass Renewal Payment</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style>
        input[type=text], input[type=password] {
            width: 100%; padding: 12px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px;
        }
        .btn { background-color: #007bff; color: white; padding: 12px; border: none; width: 100%; border-radius: 4px; cursor: pointer; font-size: 16px; }
        .btn:hover { background-color: #0056b3; }
    </style>
</head>
<body>
<div class="container mt-5">

<?php if(isset($row)): ?>
    <h3>Payment for Pass Renewal</h3>
    <p><strong>ID:</strong> <?php echo htmlspecialchars($row['id']); ?> | 
       <strong>Name:</strong> <?php echo htmlspecialchars($row['name']); ?> | 
       <strong>From - To:</strong> VIT - <?php echo htmlspecialchars($row['dest']); ?></p>
    <p><strong>Total Amount:</strong> Rs. <?php echo $amt; ?></p>

    <form action="invoice.php" method="post">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <input type="hidden" name="amt" value="<?php echo $amt; ?>">

        <label for="cardname">Name on Card</label>
        <input type="text" id="cardname" name="cardname" value="<?php echo htmlspecialchars($row['name']); ?>" required>

        <label for="cardnumber">Card Number</label>
        <input type="text" id="cardnumber" name="cardnumber" placeholder="1111-2222-3333-4444" required>

        <div class="row">
            <div class="col">
                <label for="expmonth">Exp Month</label>
                <input type="text" id="expmonth" name="expmonth" placeholder="MM" required>
            </div>
            <div class="col">
                <label for="expyear">Exp Year</label>
                <input type="text" id="expyear" name="expyear" placeholder="YYYY" required>
            </div>
        </div>

        <label for="cvv">CVV</label>
        <input type="password" id="cvv" name="cvv" placeholder="***" required>

        <input type="submit" value="Continue to Checkout" class="btn">
    </form>
<?php else: ?>
    <p class="text-danger">Pass not found or invalid request.</p>
<?php endif; ?>

</div>
</body>
</html>
