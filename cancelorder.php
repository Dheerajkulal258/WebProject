<?php
session_start();
error_reporting(E_ALL);
include_once('includes/dbconnection.php');

if (!isset($_SESSION['fosuid'])) {
    header("Location: logout.php");
    exit();
}

if (!isset($_GET['oid'])) {
    die("Order ID not specified.");
}

$orderid = $_GET['oid'];
$userid = $_SESSION['fosuid'];

// Check if the order belongs to the logged-in user
$query = mysqli_query($con, "SELECT * FROM tblorderaddresses WHERE Ordernumber='$orderid' AND UserId='$userid'");
if (mysqli_num_rows($query) == 0) {
    die("Order not found or access denied.");
}

$row = mysqli_fetch_array($query);
$status = $row['OrderFinalStatus'];
$cancelSuccess = false;

if (isset($_POST['submit'])) {
    $remark = mysqli_real_escape_string($con, $_POST['restremark']);
    $ressta = "Order Cancelled";
    $canclbyuser = 1;

    $insertTrack = mysqli_query($con, "INSERT INTO tblfoodtracking(OrderId, remark, status, OrderCanclledByUser) VALUES ('$orderid', '$remark', '$ressta', '$canclbyuser')");
    $updateOrder = mysqli_query($con, "UPDATE tblorderaddresses SET OrderFinalStatus='$ressta' WHERE Ordernumber='$orderid'");

    if ($insertTrack && $updateOrder) {
        $status = $ressta;
        $cancelSuccess = true;
    } else {
        die("Error cancelling order: " . mysqli_error($con));
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Cancel Order</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f7f7f7; padding: 20px; }
        .container { max-width: 700px; margin: auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: center; }
        textarea { width: 100%; padding: 10px; margin-top: 10px; }
        .btn { padding: 10px 20px; background-color: #d6336c; color: white; border: none; cursor: pointer; border-radius: 5px; }
        .btn:hover { background-color: #c82350; }
        .success, .error { text-align: center; font-size: 16px; margin-top: 10px; }
        .success { color: green; }
        .error { color: red; }
    </style>
</head>
<body>

<div class="container">
    <h2>Cancel Order #<?php echo htmlspecialchars($orderid); ?></h2>

    <table>
        <tr>
            <th>Order Number</th>
            <th>Current Status</th>
        </tr>
        <tr>
            <td><?php echo htmlspecialchars($orderid); ?></td>
            <td><?php echo $status == "" ? "Waiting for confirmation" : htmlspecialchars($status); ?></td>
        </tr>
    </table>

    <?php if ($cancelSuccess): ?>
        <p class="success">✅ Order has been cancelled successfully.</p>
    <?php elseif ($status == 'Order Cancelled'): ?>
        <p class="error">⚠️ Order Already Cancelled.</p>
    <?php elseif ($status == '' || $status == 'Order Accept'): ?>
        <form method="post" action="">
            <label for="restremark"><strong>Reason for cancellation:</strong></label>
            <textarea name="restremark" id="restremark" rows="4" required></textarea><br><br>
            <button type="submit" name="submit" class="btn">Cancel Order</button>
        </form>
    <?php else: ?>
        <p class="error">❌ You can't cancel this order. It's either on the way or delivered.</p>
    <?php endif; ?>
</div>

</body>
</html>
