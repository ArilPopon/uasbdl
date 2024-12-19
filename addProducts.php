<?php
include 'koneksi.php';

// Proses penyimpanan data ke database
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $category_name = $_POST['category'];
    $brand = $_POST['brand'];
    $model_number = $_POST['model_number'];
    $price = $_POST['price'];
    $stock_quantity = $_POST['stock_quantity'];
    $supplier_id = $_POST['supplier'];
    $transaction_type = 'IN'; // Selalu diatur ke "IN"
    $transaction_date = date("Y-m-d H:i:s"); // Waktu transaksi saat ini

    // Ambil category_id berdasarkan category_name
    $categoryQuery = "SELECT category_id FROM categories WHERE category_name = '$category_name'";
    $categoryResult = $conn->query($categoryQuery);

    if ($categoryResult->num_rows > 0) {
        $category = $categoryResult->fetch_assoc();
        $category_id = $category['category_id']; // Ambil category_id

        // Query untuk menyimpan produk
        $query = "INSERT INTO products (name, category, brand, model_number, price, stock_quantity)
                  VALUES ('$name', '$category_id', '$brand', '$model_number', '$price', '$stock_quantity')";

        if ($conn->query($query) === TRUE) {
            // Ambil product_id terakhir
            $product_id = $conn->insert_id;

            // Simpan data transaksi
            $transactionQuery = "INSERT INTO transactions (product_id, transaction_type, quantity, transaction_date, supplier_id)
                                 VALUES ('$product_id', '$transaction_type', '$stock_quantity', '$transaction_date', '$supplier_id')";

            if ($conn->query($transactionQuery) === TRUE) {
                echo "<script>alert('Produk Berhasil Ditambahkan'); window.location = 'products.php';</script>";
            } else {
                echo "<script>alert('Gagal Menambahkan Transaksi: " . $conn->error . "');</script>";
            }
        } else {
            echo "<script>alert('Gagal Menambahkan Produk: " . $conn->error . "');</script>";
        }
    } else {
        echo "<script>alert('Kategori Tidak Ditemukan');</script>";
    }
}

// Ambil kategori dan supplier untuk dropdown
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
    <title>Tambah Produk</title>
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
                    <h1 class="mt-4">Tambah Produk</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="products.php">Products</a></li>
                        <li class="breadcrumb-item active">Add Product</li>
                    </ol>

                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-box me-1"></i> Form Input Produk
                        </div>
                        <div class="card-body">
                            <form method="POST">
                                <div class="row">
                                    <!-- Left side of the form -->
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">Nama Produk</label>
                                        <input type="text" id="name" name="name" class="form-control" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="category" class="form-label">Kategori</label>
                                        <select id="category" name="category" class="form-select" required>
                                            <option value="">Pilih Kategori</option>
                                            <?php while ($category = $categories->fetch_assoc()): ?>
                                                <option value="<?php echo $category['category_name']; ?>">
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
                                        <input type="text" id="brand" name="brand" class="form-control" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="model_number" class="form-label">Tipe</label>
                                        <input type="text" id="model_number" name="model_number" class="form-control" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Left side of the form -->
                                    <div class="col-md-6 mb-3">
                                        <label for="price" class="form-label">Harga</label>
                                        <input type="number" id="price" name="price" class="form-control" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="stock_quantity" class="form-label">Stok</label>
                                        <input type="number" id="stock_quantity" name="stock_quantity" class="form-control" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="supplier" class="form-label">Suplier</label>
                                    <select id="supplier" name="supplier" class="form-select" required>
                                        <option value="">Pilih Suplier</option>
                                        <?php while ($supplier = $suppliers->fetch_assoc()): ?>
                                            <option value="<?php echo $supplier['supplier_id']; ?>">
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


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
</body>

</html>