<?php
include_once("../server/connection.php");

// Handle the update request for order status
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['order_id']) && isset($_POST['status'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    $update_query = "UPDATE orders SET order_status = ? WHERE order_id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("si", $status, $order_id);
    $stmt->execute();
}

// Handle the update request for payment status
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['payment_order_id']) && isset($_POST['payment_status'])) {
    $order_id = $_POST['payment_order_id'];
    $payment_status = $_POST['payment_status'];

    $update_query = "UPDATE orders SET payment_status = ? WHERE order_id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("si", $payment_status, $order_id);
    $stmt->execute();
}

// Number of orders to display per page
$orders_per_page = 10;

// Get the current page or set the default to 1
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

// Calculate the starting row for the query
$offset = ($page - 1) * $orders_per_page;

// Get the total number of orders
$total_orders_query = "SELECT COUNT(*) as total FROM orders";
$total_orders_result = $conn->query($total_orders_query);
$total_orders = $total_orders_result->fetch_assoc()['total'];

// Calculate the total number of pages
$total_pages = ceil($total_orders / $orders_per_page);

// Get the orders for the current page
$stmt = $conn->prepare("
    SELECT oi.*, o.order_status, o.payment_status, u.fname
    FROM order_items oi
    JOIN orders o ON oi.order_id = o.order_id
    JOIN users u ON o.user_id = u.user_id
    LIMIT ?, ?
");
$stmt->bind_param("ii", $offset, $orders_per_page);
$stmt->execute();
$orders = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        /* Styles for the Admin Dashboard */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            height: 100vh;
        }

        header {
            background-color: #333;
            color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
        }

        header .logo {
            font-size: 24px;
        }

        header nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
        }

        header nav ul li {
            margin: 0 10px;
        }

        header nav ul li a {
            color: #fff;
            text-decoration: none;
        }

        header .logout button {
            background-color: #f00;
            color: #fff;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }

        .container {
            display: flex;
            flex: 1;
        }

        aside {
            width: 200px;
            background-color: #f4f4f4;
            padding: 20px;
        }

        aside ul {
            list-style: none;
            padding: 0;
        }

        aside ul li {
            margin: 10px 0;
        }

        aside ul li a {
            text-decoration: none;
            color: #333;
        }

        main {
            flex: 1;
            padding: 20px;
        }

        .products-overview {
            width: 100%;
        }

        .products-overview h1 {
            margin-bottom: 20px;
        }

        .add-product-btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            margin-bottom: 20px;
            border: none;
            cursor: pointer;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .pagination li {
            display: inline;
            margin: 0 5px;
        }

        .pagination li a {
            text-decoration: none;
            color: #333;
            padding: 8px 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .pagination li a:hover {
            background-color: #f0f0f0;
        }

        .pagination .active a {
            background-color: #4CAF50;
            color: white;
            border: 1px solid #4CAF50;
        }

        .pagination .disabled a {
            color: #999;
            pointer-events: none;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">Admin Dashboard</div>
        <nav>
            <ul>
                <li><span class="site-name">BiteAndBrews</span></li>
            </ul>
        </nav>
        <div class="logout">
            <a href="logout.php"><button>Logout</button></a>
        </div>
    </header>
    <div class="container">
        <aside>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="manage_product.php">Manage Products</a></li>
                <li><a href="orders.php">Orders</a></li>
                <li><a href="recover.php">Recover Products</a></li>
            </ul>
        </aside>
        <main>
            <div class="products-overview">
                <h1>Orders</h1>
                <table>
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Product Image</th>
                            <th>Order Status</th>
                            <th>Payment Status</th>
                            <th>User ID</th>
                            <th>First Name</th>
                            <th>Product Price</th>
                            <th>Order Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($order = $orders->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $order['product_name']; ?></td>
                            <td><img src="../assets/imgs/<?php echo $order['product_img']; ?>" alt="<?php echo $order['product_name']; ?>" width="50"></td>
                            <td>
                                <form method="post" action="">
                                    <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                                    <select name="status" onchange="this.form.submit()">
                                        <option value="Order Processed" <?php if ($order['order_status'] == 'Order Processed') echo 'selected'; ?>>Order Processed</option>
                                        <option value="Order Shipped" <?php if ($order['order_status'] == 'Order Shipped') echo 'selected'; ?>>Order Shipped</option>
                                        <option value="Order En Route" <?php if ($order['order_status'] == 'Order En Route') echo 'selected'; ?>>Order En Route</option>
                                        <option value="Order Arrived" <?php if ($order['order_status'] == 'Order Arrived') echo 'selected'; ?>>Order Arrived</option>
                                    </select>
                                </form>
                            </td>
                            <td>
                                <form method="post" action="">
                                    <input type="hidden" name="payment_order_id" value="<?php echo $order['order_id']; ?>">
                                    <select name="payment_status" onchange="this.form.submit()">
                                        <option value="not paid" <?php if ($order['payment_status'] == 'not paid') echo 'selected'; ?>>Not Paid</option>
                                        <option value="Paid" <?php if ($order['payment_status'] == 'Paid') echo 'selected'; ?>>Paid</option>
                                    </select>
                                </form>
                            </td>
                            <td><?php echo $order['user_id']; ?></td>
                            <td><?php echo $order['fname']; ?></td>
                            <td><?php echo $order['product_price']; ?></td>
                            <td><?php echo $order['order_date']; ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>

                <div class="pagination">
                    <ul>
                        <?php if ($page > 1): ?>
                        <li><a href="?page=<?php echo $page - 1; ?>">&laquo; Prev</a></li>
                        <?php else: ?>
                        <li class="disabled"><a href="#">&laquo; Prev</a></li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="<?php if ($i == $page) echo 'active'; ?>"><a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                        <?php endfor; ?>

                        <?php if ($page < $total_pages): ?>
                        <li><a href="?page=<?php echo $page + 1; ?>">Next &raquo;</a></li>
                        <?php else: ?>
                        <li class="disabled"><a href="#">Next &raquo;</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
