<?php
session_start();
include('server/connection.php');

function fetchOrders($conn) {
    // Fetch all orders from the orders table
    $orderQuery = "SELECT order_id, transaction_id, order_date FROM orders";
    $stmt = $conn->prepare($orderQuery);
    $stmt->execute();
    return $stmt->get_result();
}

$orderResult = fetchOrders($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Tracking</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e6f0f8;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .order-tracking {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            width: 800px;
            height: auto;
        }
        .order-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .order-header span {
            font-size: 14px;
        }
        .order-header .order-number {
            color: #3498db;
            font-weight: bold;
        }
        .order-header .tracking-number {
            font-weight: bold;
        }
        .tracking-progress {
            display: flex;
            align-items: center;
            margin: 20px 0;
        }
        .tracking-progress div {
            flex: 1;
            text-align: center;
            position: relative;
        }
        .tracking-progress div:before, .tracking-progress div:after {
            content: '';
            height: 4px;
            background: #3498db;
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: -1;
        }
        .tracking-progress div:after {
            background: #dcdcdc;
            width: calc(100% - 10px);
            left: 10px;
        }
        .tracking-progress div:first-child:before {
            display: none;
        }
        .tracking-progress div:last-child:after {
            display: none;
        }
        .tracking-progress div.completed:before {
            background: #3498db;
        }
        .tracking-progress div.completed .icon {
            background: #3498db;
            color: #fff;
        }
        .icon {
            width: 55px;
            height: 55px;
            border-radius: 50%;
            background: #dcdcdc;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto 10px;
        }
        .icon img {
            width: 50px;
            height: 50px;
        }
        .step-label {
            font-size: 12px;
        }
    </style>
</head>
<body>
<div class="order-tracking">
    <?php if ($paymentResult->num_rows > 7): ?>
        <?php while($paymentRow = $paymentResult->fetch_assoc()): ?>
            <div class="order-header">
                <span>Payment ID <span class="order-number"><?php echo htmlspecialchars($paymentRow['payment_id']); ?></span></span>
                <span>Transaction ID <?php echo htmlspecialchars($paymentRow['transaction_id']); ?></span>
                <span>Order ID <?php echo htmlspecialchars($paymentRow['order_id']); ?></span>
            </div>
            <div class="order-header">
                <span>Payment Date <span class="tracking-number"><?php echo htmlspecialchars($paymentRow['order_date']); ?></span></span>
            </div>

            <div class="tracking-progress">
                <div class="completed">
                    <div class="icon"><img src="assets/imgs/orderprocessed.png" alt="Processed"></div>
                    <div class="step-label">Order Processed</div>
                </div>
                <div class="completed">
                    <div class="icon"><img src="assets/imgs/pickup.png" alt="Shipped"></div>
                    <div class="step-label">Order Shipped</div>
                </div>
                <div class="completed">
                    <div class="icon"><img src="assets/imgs/otw.png" alt="En Route"></div>
                    <div class="step-label">Order En Route</div>
                </div>
                <div class="completed">
                    <div class="icon"><img src="assets/imgs/orderdelivered.png" alt="Arrived"></div>
                    <div class="step-label">Order Arrived</div>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No payments found.</p>
    <?php endif; ?>
</div>
</body>
</html>