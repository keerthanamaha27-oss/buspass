<?php
include('connection.php');
date_default_timezone_set("Asia/Kolkata");

if(isset($_POST['id'])){
    $id = $_POST['id'];
    $amt = $_POST['amt'];
    // Use a safe default if tripType is not set
    $tripType = isset($_POST['tripType']) ? $_POST['tripType'] : 'oneway';

    // Fetch passenger details
    $stmt = $conn->prepare("SELECT name, email, contact, dest, date FROM pass WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if($row = $result->fetch_assoc()){
        $name = $row['name'];
        $email = $row['email'];
        $contact = $row['contact'];
        $dest = $row['dest'];
        $validTill = $row['date'];
    } else {
        die("Invalid booking ID.");
    }
} else {
    die("No booking information received.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Invoice - Cloud Based Bus Pass</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Invoice</h2>
    <table class="table table-bordered">
        <tr>
            <th>Passenger Name</th>
            <td><?php echo htmlspecialchars($name); ?></td>
        </tr>
        <tr>
            <th>Email</th>
            <td><?php echo htmlspecialchars($email); ?></td>
        </tr>
        <tr>
            <th>Contact</th>
            <td><?php echo htmlspecialchars($contact); ?></td>
        </tr>
        <tr>
            <th>Destination</th>
            <td><?php echo htmlspecialchars($dest); ?></td>
        </tr>
        <tr>
            <th>Trip Type</th>
            <td><?php echo ($tripType == "round") ? "Round Trip" : "One Way"; ?></td>
        </tr>
        <tr>
            <th>Valid Till</th>
            <td><?php echo $validTill; ?></td>
        </tr>
        <tr>
            <th>Total Amount</th>
            <td>Rs. <?php echo $amt; ?></td>
        </tr>
    </table>
    <p>Thank you for booking your bus pass with us!</p>
</div>
</body>
</html>
