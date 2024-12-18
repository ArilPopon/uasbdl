<?php

//Products
$queryProducts = "SELECT p.product_id, p.name, c.category_name, p.brand, p.model_number, p.price, p.stock_quantity
FROM Products p
INNER JOIN Categories c ON p.category = c.category_id
";

// Categories
$queryCategories = "SELECT DISTINCT category_name FROM categories";

// Range bro
$minimum = isset($_POST['min_price']) ? (int)$_POST['min_price'] : 0;
$maximum = isset($_POST['max_price']) ? (int)$_POST['max_price'] : 0;
$queryRange = "SELECT p.product_id, p.name, c.category_name, p.brand, p.model_number, p.price, p.stock_quantity
FROM Products p
INNER JOIN Categories c ON p.category = c.category_id
WHERE p.price BETWEEN $minimum AND $maximum";


// Transactions
$queryTransactions = "SELECT 
    t.transaction_id, 
    p.name AS product_name, 
    t.transaction_type, 
    t.quantity, 
    t.transaction_date, 
    s.name AS supplier_name, 
    c.name AS client_name,
    IFNULL(s.name, '-') AS supplier_name, 
    IFNULL(c.name, '-') AS client_name
FROM 
    transactions t
INNER JOIN 
    Products p ON t.product_id = p.product_id
LEFT JOIN 
    Suppliers s ON t.supplier_id = s.supplier_id
LEFT JOIN 
    Clients c ON t.client_id = c.client_id";

// count Products
$countProducts = mysqli_query($conn, "SELECT COUNT(*) FROM products")->fetch_assoc();


//omset cuy
$queryOmset = "SELECT 
    SUM(t.quantity * p.price) AS total_omset
FROM 
    transactions t
JOIN 
    products p ON t.product_id = p.product_id
WHERE 
    t.transaction_type = 'OUT'";

//barang masuk
$countTransactionsIn = mysqli_query($conn, "SELECT COUNT(*) FROM transactions WHERE transaction_type = 'IN'")->fetch_assoc();
$countTransactionsOut = mysqli_query($conn, "SELECT COUNT(*) FROM transactions WHERE transaction_type = 'OUT'")->fetch_assoc();

$queryClients = "SELECT * FROM clients";
$querySuppliers = "SELECT * FROM suppliers";

$showProducts = $conn->query($queryProducts);
$showCategories = $conn->query($queryCategories);
$showTransactions = $conn->query($queryTransactions);
$showClients = $conn->query($queryClients);
$showSuppliers = $conn->query($querySuppliers);
$showOmset = mysqli_query($conn, $queryOmset) ->fetch_assoc();