<?php
include 'koneksi.php';

// Pastikan ID produk ada di URL
if (isset($_GET['id'])) {
    $productId = $_GET['id'];

    // Query untuk menghapus produk berdasarkan ID
    $query = "DELETE FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $productId);  // Menggunakan parameter binding untuk menghindari SQL Injection
    $stmt->execute();

    // Setelah produk berhasil dihapus, arahkan kembali ke halaman products.php
    header('Location: products.php');
    exit();
} else {
    echo "Product ID not found.";
}
