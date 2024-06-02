<?php

include_once('../server/connection.php');
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}

// Retrieve products
$stmt = $conn->prepare("SELECT * FROM products WHERE is_deleted = FALSE");
$stmt->execute();
$products = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
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
            display: flex;
            align-items: center;
        }

        header .logo .site-name {
            margin-left: 20px;
            font-size: 24px;
            font-weight: bold;
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

        .edit-btn, .delete-btn {
            width: 70px;
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            text-align: center;
        }

        .edit-btn {
            background-color: #4CAF50;
            margin-right: 5px;
        }

        .delete-btn {
            background-color: #f00;
        }

        .actions {
            display: flex;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const deleteButtons = document.querySelectorAll('.delete-btn');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function (event) {
                    event.preventDefault();
                    const productId = this.getAttribute('data-id');
                    if (confirm('Are you sure you want to delete this product?')) {
                        // Make an AJAX request to delete the product
                        fetch('delete_product.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({ product_id: productId })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert('Product deleted successfully.');
                                location.reload();
                            } else {
                                alert('Error: ' + data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                    }
                });
            });
        });
    </script>
</head>
<body>
    <header>
        <div class="logo">
            Admin Dashboard
        </div>
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
                <li><a href="dashboard.php">Manage Products</a></li>
                <li><a href="orders.php">Orders</a></li>
                <li><a href="recover.php">Recover Products</a></li>
            </ul>
        </aside>
        <main>
            <div class="products-overview">
                <h1>Manage Products</h1>
                <a href="add_product.php"><button class="add-product-btn">Add New Product</button></a>
                <table>
                    <thead>
                        <tr>
                            <th>Product ID</th>
                            <th>Product Image</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($product = $products->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $product['product_id']; ?></td>
                            <td><img src="../assets/imgs/<?php echo $product['product_img']; ?>" style="width: 50px; height: 50px;"></td>
                            <td><?php echo $product['product_name']; ?></td>
                            <td><?php echo $product['product_category']; ?></td>
                            <td><?php echo $product['product_description']; ?></td>
                            <td><?php echo $product['product_price']; ?></td>
                            <td class="actions">
                                <a href="edit_product.php?id=<?php echo $product['product_id']; ?>"><button class="edit-btn">Edit</button></a>
                                <button class="delete-btn" data-id="<?php echo $product['product_id']; ?>">Delete</button>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>
