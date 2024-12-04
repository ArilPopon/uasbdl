<?php

//Products
$queryProducts = "SELECT p.product_id, p.name, c.category_name, p.brand, p.model_number, p.price, p.stock_quantity
FROM Products p
INNER JOIN Categories c ON p.category = c.category_id
";

// Categories
$queryCategories = "SELECT DISTINCT category_name FROM categories";

$showProducts = $conn->query($queryProducts);
$showCategories = $conn->query($queryCategories);
