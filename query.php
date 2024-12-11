<?php

//Products
$queryProducts = "SELECT p.product_id, p.name, c.category_name, p.brand, p.model_number, p.price, p.stock_quantity
FROM Products p
INNER JOIN Categories c ON p.category = c.category_id
";

// Categories
$queryCategories = "SELECT DISTINCT category_name FROM categories";

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
    Clients c ON t.client_id = c.client_id
LIMIT 0, 25;
";

// count Products
$getProducts = mysqli_query($conn, "select * from products");
$queryCountProducts = mysqli_num_rows($getProducts);


$queryClients = "SELECT * FROM clients";
$querySuppliers = "SELECT * FROM suppliers";

$showProducts = $conn->query($queryProducts);
$showCategories = $conn->query($queryCategories);
$showTransactions = $conn->query($queryTransactions);
$showClients = $conn->query($queryClients);
$showSuppliers = $conn->query($querySuppliers);
