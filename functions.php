<?php
// Fungsi untuk membangun query string tanpa parameter 'page'
function buildQueryString($params)
{
    unset($params['page']);
    return http_build_query($params);
}


// Fungsi untuk menentukan pengurutan data
function getOrderBy($sort)
{
    switch ($sort) {
        case 'a-z':
            return "ORDER BY p.name ASC";
        case 'z-a':
            return "ORDER BY p.name DESC";
        default:
            return "ORDER BY p.product_id";
    }
}

// Fungsi untuk mengambil daftar produk
function getProducts($conn, $query_showProducts, $order_by, $limit, $offset)
{
    $sql = "SELECT p.product_id, p.name, c.category_name, p.brand, p.model_number, p.stock_quantity, p.price 
            $query_showProducts $order_by LIMIT $limit OFFSET $offset";
    return mysqli_query($conn, $sql);
}

// Fungsi untuk mendapatkan jumlah total produk
function getTotalData($conn, $query_showProducts)
{
    $sql = "SELECT COUNT(*) AS total $query_showProducts";
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_assoc($result)['total'];
}

// Fungsi untuk mengambil daftar kategori
function getCategories($conn)
{
    $sql = "SELECT category_id, category_name FROM Categories";
    return mysqli_query($conn, $sql);
}
