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
        echo "<script>alert('Produk tidak ditemukan'); window.location = 'products.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('Parameter ID tidak ditemukan'); window.location = 'products.php';</script>";
    exit();
}

// Ambil data client untuk dropdown
$clients = $conn->query("SELECT * FROM clients");

// Proses ketika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $deleteQuantity = $_POST['delete_quantity'];
    $clientId = $_POST['client']; // Ambil client_id dari form

    // Kurangi stok produk
    $newStock = $product['stock_quantity'] - $deleteQuantity;

    if ($newStock >= 0) {
        // Insert transaksi keluar
        $transactionType = 'OUT';
        $transactionDate = date("Y-m-d H:i:s");
        $transactionQuery = "INSERT INTO transactions (product_id, transaction_type, quantity, transaction_date, client_id)
                             VALUES ('$productId', '$transactionType', '$deleteQuantity', '$transactionDate', '$clientId')";

        if ($conn->query($transactionQuery) === TRUE) {
            // Jika stok menjadi 0, hapus produk
            if ($newStock == 0) {
                $deleteQuery = "DELETE FROM products WHERE product_id = $productId";
                if ($conn->query($deleteQuery) === TRUE) {
                    echo "<script>alert('Produk berhasil dihapus sepenuhnya'); window.location = 'products.php';</script>";
                } else {
                    echo "<script>alert('Kesalahan saat menghapus produk: " . $conn->error . "');</script>";
                }
            } else {
                // Update stok produk
                $updateQuery = "UPDATE products SET stock_quantity = $newStock WHERE product_id = $productId";
                if ($conn->query($updateQuery) === TRUE) {
                    echo "<script>alert('Stok produk berhasil diperbarui'); window.location = 'products.php';</script>";
                } else {
                    echo "<script>alert('Kesalahan saat memperbarui stok: " . $conn->error . "');</script>";
                }
            }
        } else {
            echo "<script>alert('Kesalahan saat menyimpan transaksi keluar: " . $conn->error . "');</script>";
        }
    } else {
        echo "<script>alert('Jumlah yang dihapus melebihi stok saat ini');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hapus Produk</title>
    <link href="css/styles.css" rel="stylesheet">
</head>

<body>
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand ps-3 fw-bold" href="index.php">PT. RUNGKUT ELECTRONICS</a>
    </nav>

    <div id="layoutSidenav">
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
                        <a class="nav-link" href="products.php">
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

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Hapus Produk</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="products.php">Products</a></li>
                        <li class="breadcrumb-item active">Hapus Produk</li>
                    </ol>

                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-trash me-1"></i>
                            Hapus Produk
                        </div>
                        <div class="card-body">
                            <form method="POST">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama Produk</label>
                                    <input type="text" name="name" class="form-control" id="name" value="<?php echo htmlspecialchars($product['name']); ?>" readonly>
                                </div>

                                <div class="mb-3">
                                    <label for="stock_quantity" class="form-label">Jumlah Stok Saat Ini</label>
                                    <input type="number" name="stock_quantity" class="form-control" id="stock_quantity" value="<?php echo htmlspecialchars($product['stock_quantity']); ?>" readonly>
                                </div>

                                <div class="mb-3">
                                    <label for="delete_quantity" class="form-label">Jumlah yang Dihapus</label>
                                    <input type="number" name="delete_quantity" class="form-control" id="delete_quantity" min="1" max="<?php echo htmlspecialchars($product['stock_quantity']); ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="client" class="form-label">Pilih Client</label>
                                    <select id="client" name="client" class="form-select" required>
                                        <option value="">Pilih Client</option>
                                        <?php while ($client = $clients->fetch_assoc()): ?>
                                            <option value="<?php echo $client['client_id']; ?>">
                                                <?php echo $client['name']; ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>

                                <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus stok produk ini?')">Hapus</button>
                                <a href="products.php" class="btn btn-secondary ms-2">Kembali</a>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Validasi jumlah stok yang dihapus
        document.getElementById('delete_quantity').addEventListener('input', function() {
            const maxStock = parseInt(document.getElementById('stock_quantity').value, 10);
            if (this.value > maxStock) {
                alert('Jumlah yang dihapus tidak boleh lebih besar dari stok saat ini.');
                this.value = maxStock;
            }
        });
    </script>
</body>

</html>