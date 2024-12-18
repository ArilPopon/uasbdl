<?php
include 'koneksi.php';

// Cek apakah ada parameter 'id' yang dikirimkan
if (isset($_GET['id'])) {
    $productId = $_GET['id'];

    // Query untuk mendapatkan data produk berdasarkan product_id
    $query = "SELECT * FROM products WHERE product_id = $productId";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        echo "<script>alert('Product not found'); window.location = 'products.php';</script>";
        exit();
    }
}

// Proses ketika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $category_name = $_POST['category'];
    $brand = $_POST['brand'];
    $model_number = $_POST['model_number'];
    $price = $_POST['price'];
    $stock_quantity = $_POST['stock_quantity'];

    // Ambil category_id berdasarkan category_name
    $categoryQuery = "SELECT category_id FROM categories WHERE category_name = '$category_name'";
    $categoryResult = $conn->query($categoryQuery);

    if ($categoryResult->num_rows > 0) {
        $category = $categoryResult->fetch_assoc();
        $category_id = $category['category_id'];

        // Query untuk mengupdate data produk
        $updateQuery = "UPDATE products SET
            name = '$name',
            category = '$category_id',
            brand = '$brand',
            model_number = '$model_number',
            price = '$price',
            stock_quantity = '$stock_quantity'
            WHERE product_id = $productId";

        if ($conn->query($updateQuery) === TRUE) {
            echo "<script>alert('Product updated successfully'); window.location = 'products.php';</script>";
        } else {
            echo "<script>alert('Error updating product: " . $conn->error . "');</script>";
        }
    } else {
        echo "<script>alert('Category not found');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Edit Product</title>
    <link href="css/styles.css" rel="stylesheet" />
</head>

<body>
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand ps-3" href="index.html">Data Gudang</a>
    </nav>

    <div class="container-fluid px-4">
        <h1 class="mt-4">Edit Product</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="products.php">Products</a></li>
            <li class="breadcrumb-item active">Edit Product</li>
        </ol>

        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-edit me-1"></i>
                Edit Product
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label for="name" class="form-label">Product Name</label>
                        <input type="text" name="name" class="form-control" id="name" value="<?php echo $product['name']; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="category" class="form-label">Category</label>
                        <select name="category" class="form-select" id="category" required>
                            <?php
                            // Menampilkan kategori yang ada
                            $categoryQuery = "SELECT * FROM categories";
                            $categoryResult = $conn->query($categoryQuery);
                            while ($category = $categoryResult->fetch_assoc()):
                            ?>
                                <option value="<?php echo $category['category_name']; ?>"
                                    <?php echo ($product['category'] == $category['category_id']) ? 'selected' : ''; ?>>
                                    <?php echo $category['category_name']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="brand" class="form-label">Brand</label>
                        <input type="text" name="brand" class="form-control" id="brand" value="<?php echo $product['brand']; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="model_number" class="form-label">Model Number</label>
                        <input type="text" name="model_number" class="form-control" id="model_number" value="<?php echo $product['model_number']; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="price" class="form-label">Price</label>
                        <input type="number" step="0.01" name="price" class="form-control" id="price" value="<?php echo $product['price']; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="stock_quantity" class="form-label">Stock Quantity</label>
                        <input type="number" name="stock_quantity" class="form-control" id="stock_quantity" value="<?php echo $product['stock_quantity']; ?>" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <a href="products.php" class="btn btn-secondary ms-2">Cancel</a>
                </form>
            </div>
        </div>
    </div>

    <script src="js/scripts.js"></script>
</body>

</html>