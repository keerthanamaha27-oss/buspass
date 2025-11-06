<?php
include("connection.php"); // make sure $conn is your mysqli connection
date_default_timezone_set("Asia/Kolkata");

if(isset($_POST['id'])){
    $id = (int)$_POST['id'];

    // Fetch current pass details
    $stmt = $conn->prepare("SELECT name, paid FROM pass WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if($row = $result->fetch_assoc()){
        $name = $row['name'];
        $paid = $row['paid'];

        // Refund logic: you can choose to set paid to 0 or leave it for record
        $stmt2 = $conn->prepare("UPDATE pass SET paid = 0 WHERE id = ?");
        $stmt2->bind_param("i", $id);
        $stmt2->execute();

        $refundAmount = $paid;
    } else {
        die("Invalid pass ID.");
    }

} else {
    die("No pass ID received.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pass Suspension</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style>
        .container { margin-top: 50px; }
        .alert { font-size: 18px; }
    </style>
</head>
<body>
<div class="container">
    <h2>Bus Pass Suspended</h2>
    <div class="alert alert-success">
        Pass for <strong><?php echo htmlspecialchars($name); ?></strong> has been suspended.<br>
        Refund has been initiated.<br>
        Amount: Rs. <?php echo number_format($refundAmount, 2); ?> will be credited to your account in 3-4 working days.
    </div>
    <a href="manage.php" class="btn btn-primary">Back to Manage Pass</a>
</div>
</body>
</html>
