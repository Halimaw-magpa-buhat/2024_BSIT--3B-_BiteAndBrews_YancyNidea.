<?php 
include_once "../server/connection.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/bootstrap.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            height: 100vh;
            background-color: #f8f9fa;
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

        .card {
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: bold;
            background-color: #007bff;
            color: white;
            padding: 10px;
            border-top-left-radius: 0.25rem;
            border-top-right-radius: 0.25rem;
            margin: 0;
        }

        .card-body {
            padding: 20px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table, .table th, .table td {
            border: 1px solid #ddd;
        }

        .table th, .table td {
            padding: 8px;
            text-align: left;
        }

        .table th {
            background-color: #f2f2f2;
        }

        .bg-light {
            background-color: #e9ecef !important;
        }

        .display-1 {
            font-size: 2.5rem;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">Admin Dashboard</div>
        <nav>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="#">Products</a></li>
                <li><a href="orders.php">Orders</a></li>
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
                <li><a href="#">Settings</a></li>
            </ul>
        </aside>
        <main>
            <div class="card">
                <h3 class="card-title">Search Items or Orders</h3>
                <div class="card-body">
                    <form method="GET" action="admin_search_results.php">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="query" placeholder="Search by item name or order reference number" required>
                            <button class="btn btn-primary" type="submit">Search</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <h3 class="card-title">Earnings Per Order Date</h3>
                <div class="card-body">
                    <?php 
                    $sql_get_earnings = "
                    SELECT order_date, SUM(product_quantity * product_price) AS total_earnings 
                    FROM order_items 
                    GROUP BY order_date";
                    
                    $earnings_result = mysqli_query($conn, $sql_get_earnings); ?>
                    
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Order Date</th>
                                <th>Total Earnings</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $total = 0.00;
                            while($rec = mysqli_fetch_assoc($earnings_result)){
                               $total += $rec['total_earnings']; ?>
                            <tr>
                                <td><?php echo $rec['order_date'];?></td>
                                <td><?php echo "Php " . number_format($rec['total_earnings'],2);?></td>
                            </tr>      
                            <?php } ?>
                            <tr>
                                <td colspan="2" class="bg-light"> <small class="float-end"><?php echo "Php " . number_format($total,2);?></small> </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card">
                <h3 class="card-title">Top 5 Most Ordered Products</h3>
                <div class="card-body">
                    <?php 
                    $sql_get_top_products = "
                    SELECT product_name, SUM(product_quantity) AS total_quantity 
                    FROM order_items 
                    GROUP BY product_name 
                    ORDER BY total_quantity DESC 
                    LIMIT 5";
                    
                    $top_products_result = mysqli_query($conn, $sql_get_top_products); ?>
                    
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Total Quantity Ordered</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            while($rec = mysqli_fetch_assoc($top_products_result)){ ?>
                            <tr>
                                <td><?php echo $rec['product_name'];?></td>
                                <td><?php echo $rec['total_quantity'];?></td>
                            </tr>      
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
    <script src="../js/bootstrap.js"></script>
</body>
</html>
