<?php
include 'koneksi.php';

// Cek apakah ada parameter 'id' yang dikirimkan
if (isset($_GET['id'])) {
    $productId = $_GET['id'];

    // Query untuk mendapatkan data produk berdasarkan product_id dan supplier_id dari transaksi
    $query = "
        SELECT p.*, t.supplier_id 
        FROM products p
        LEFT JOIN transactions t ON p.product_id = t.product_id
        WHERE p.product_id = $productId
        LIMIT 1
    ";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        $supplier_id = $product['supplier_id'];  // Menyimpan supplier_id dari transaksi yang relevan
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
    $supplier_id = $_POST['supplier'];  // Supplier yang dipilih dari form

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
            // Update supplier pada transaksi
            $updateTransactionQuery = "UPDATE transactions SET
                supplier_id = '$supplier_id'
                WHERE product_id = $productId";

            if ($conn->query($updateTransactionQuery) === TRUE) {
                echo "<script>alert('Product updated successfully'); window.location = 'products.php';</script>";
            } else {
                echo "<script>alert('Error updating transaction: " . $conn->error . "');</script>";
            }
        } else {
            echo "<script>alert('Error updating product: " . $conn->error . "');</script>";
        }
    } else {
        echo "<script>alert('Category not found');</script>";
    }
}

// Ambil kategori dan suplier untuk dropdown
$categories = $conn->query("SELECT * FROM categories");
$suppliers = $conn->query("SELECT * FROM suppliers");
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

<body class="sb-nav-fixed">
    <!-- Navbar -->
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand ps-3 fw-bold" href="index.php">PT. RUNGKUT ELECTRONICS</a>
    </nav>

    <div id="layoutSidenav">
        <!-- Sidebar -->
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Dashboard</div>
                        <a class="nav-link" href="index.php">
                            <div class="sb-nav-link-icon">
                                <i class="fas fa-tachometer-alt"></i>
                            </div>
                            Dashboard
                        </a>
                        <div class="sb-sidenav-menu-heading">Menu</div>
                        <a class="nav-link active" href="products.php">
                            <div class="sb-nav-link-icon">
                                <i class="fas fa-boxes-stacked"></i>
                            </div>
                            Products
                        </a>
                        <a class="nav-link" href="transactions.php">
                            <div class="sb-nav-link-icon">
                                <i class="fas fa-money-bill-transfer"></i>
                            </div>
                            Transactions
                        </a>
                        <a class="nav-link" href="clients.php">
                            <div class="sb-nav-link-icon">
                                <i class="fas fa-user"></i>
                            </div>
                            Clients
                        </a>
                        <a class="nav-link" href="suppliers.php">
                            <div class="sb-nav-link-icon">
                                <i class="fas fa-truck-field"></i>
                            </div>
                            Suppliers
                        </a>
                    </div>
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Edit Produk</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="products.php">Products</a></li>
                        <li class="breadcrumb-item active">Edit Product</li>
                    </ol>

                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-box me-1"></i> Form Edit Produk
                        </div>
                        <div class="card-body">
                            <form method="POST">
                                <div class="row">
                                    <!-- Left side of the form -->
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">Nama Produk</label>
                                        <input type="text" id="name" name="name" class="form-control" value="<?php echo $product['name']; ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="category" class="form-label">Kategori</label>
                                        <select id="category" name="category" class="form-select" required>
                                            <option value="">Pilih Kategori</option>
                                            <?php while ($category = $categories->fetch_assoc()): ?>
                                                <option value="<?php echo $category['category_name']; ?>"
                                                    <?php echo ($product['category'] == $category['category_id']) ? 'selected' : ''; ?>>
                                                    <?php echo $category['category_name']; ?>
                                                </option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Right side of the form -->
                                    <div class="col-md-6 mb-3">
                                        <label for="brand" class="form-label">Merek</label>
                                        <input type="text" id="brand" name="brand" class="form-control" value="<?php echo $product['brand']; ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="model_number" class="form-label">Tipe</label>
                                        <input type="text" id="model_number" name="model_number" class="form-control" value="<?php echo $product['model_number']; ?>" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Left side of the form -->
                                    <div class="col-md-6 mb-3">
                                        <label for="price" class="form-label">Harga</label>
                                        <input type="number" id="price" name="price" class="form-control" value="<?php echo $product['price']; ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="stock_quantity" class="form-label">Stok</label>
                                        <input type="number" id="stock_quantity" name="stock_quantity" class="form-control" value="<?php echo $product['stock_quantity']; ?>" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="supplier" class="form-label">Suplier</label>
                                    <select id="supplier" name="supplier" class="form-select" required>
                                        <option value="">Pilih Suplier</option>
                                        <?php while ($supplier = $suppliers->fetch_assoc()): ?>
                                            <option value="<?php echo $supplier['supplier_id']; ?>"
                                                <?php echo ($supplier_id == $supplier['supplier_id']) ? 'selected' : ''; ?>>
                                                <?php echo $supplier['name']; ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>

                                <div class="d-flex gap-3">
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                    <a href="products.php" class="btn btn-secondary ms-auto">Kembali</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
</body>

</html>