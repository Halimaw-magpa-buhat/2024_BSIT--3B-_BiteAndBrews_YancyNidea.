<?php
include_once('../server/connection.php');

if (isset($_POST['add_product'])) {
    // Handle file upload
    $uploadOk = 1;
    $target_dir = "../img/";
    $target_file = $target_dir . basename($_FILES["product_img"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $check_img = getimagesize($_FILES["product_img"]["tmp_name"]);

    if ($check_img !== false) {
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    if ($uploadOk == 0) {
        header("location: dashboard.php?insert_status=99");
        exit();
    } else {
        if (move_uploaded_file($_FILES["product_img"]["tmp_name"], $target_file)) {
            $img = htmlspecialchars(basename($_FILES["product_img"]["name"]));
        } else {
            header("location: dashboard.php?insert_status=99");
            exit();
        }
    }

    // Insert product data
    $name = $_POST['product_name'];
    $category = $_POST['product_category'];
    $description = $_POST['product_description'];
    $price = $_POST['product_price'];
    $is_deleted = 0;  // Set is_deleted to 0

    $stmt = $conn->prepare("INSERT INTO products (product_img, product_name, product_category, product_description, product_price, is_deleted) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssdi", $img, $name, $category, $description, $price, $is_deleted);
    $stmt->execute();
    $stmt->close();

    header('Location: dashboard.php');
    exit();
}
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

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
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
            <div class="products-overview">
                <h1>Add Product</h1>
                <form action="add_product.php" method="POST" enctype="multipart/form-data">
                    <table>
                        <thead>
                            <tr>
                                <th>
                                    <label for="product_img">Product Image:</label><br>
                                    <input type="file" id="product_img" name="product_img" required><br>
                                </th>
                                <th>
                                    <label for="product_name">Product Name:</label><br>
                                    <input type="text" id="product_name" name="product_name" required><br>
                                </th>
                                <th>
                                    <label for="product_category">Product Category:</label><br>
                                    <input type="text" id="product_category" name="product_category" required><br>
                                </th>
                                <th>
                                    <label for="product_description">Product Description:</label><br>
                                    <textarea id="product_description" name="product_description" required></textarea><br>
                                </th>
                                <th>
                                    <label for="product_price">Product Price:</label><br>
                                    <input type="number" id="product_price" name="product_price" required><br><br>
                                </th>
                            </tr>
                        </thead>
                    </table>
                    <input type="submit" name="add_product" value="Add Product">
                </form>
            </div>
        </main>
    </div>
</body>
</html>
