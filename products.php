<?php
include 'koneksi.php';
include 'query.php'
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Dashboard | Products</title>
    <link
        href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css"
        rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script
        src="https://use.fontawesome.com/releases/v6.3.0/js/all.js"
        crossorigin="anonymous"></script>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="index.html">Data Gudang</a>
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
                        <a
                            class="nav-link active"
                            href="products.php">
                            <div class="sb-nav-link-icon">
                                <i class="fas fa-boxes-stacked"></i>
                            </div>
                            Products
                        </a><a
                            class="nav-link"
                            href="transactions.php">
                            <div class="sb-nav-link-icon">
                                <i class="fas fa-money-bill-transfer"></i>
                            </div>
                            Transactions
                        </a>
                        <a
                            class="nav-link"
                            href="clients.php">
                            <div class="sb-nav-link-icon">
                                <i class="fas fa-user"></i>
                            </div>
                            Clients
                        </a>
                        <a
                            class="nav-link"
                            href="suppliers.php">
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
                    <h1 class="mt-4">Products</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item">
                            <a href="index.html">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">Products</li>
                    </ol>
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Data Products
                        </div>
                        <div class="card-body">
                            <form method="GET">
                                <div class="mb-3 d-flex flex-wrap align-items-center gap-3">
                                    <div class="d-flex align-items-center">
                                        <label for="categoryFilter" class="form-label me-2 mb-0">Filter by Category:</label>
                                        <select id="categoryFilter" name="category" onchange="this.form.submit()" class="form-select w-auto">
                                            <option value="all">All</option>
                                            <?php while ($category = $showCategories->fetch_assoc()): ?>
                                                <option value="<?php echo $category['category_name']; ?>"
                                                    <?php echo (isset($_GET['category']) && $_GET['category'] == $category['category_name']) ? 'selected' : ''; ?>>
                                                    <?php echo $category['category_name']; ?>
                                                </option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <label for="priceRange" class="form-label me-2 mb-0">Price Range:</label>
                                        <input type="number" name="min_price" class="form-control w-auto" placeholder="Harga Minimal" value="<?php echo $minPrice; ?>">
                                        <input type="number" name="max_price" class="form-control w-auto ms-2" placeholder="Harga Maksimal" value="<?php echo $maxPrice; ?>">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Terapkan</button>
                                    <a href="addProducts.php" class="btn btn-success ms-auto">Tambahkan</a>
                                </div>


                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Nama</th>
                                            <th>Kategori</th>
                                            <th>Merek</th>
                                            <th>Tipe</th>
                                            <th>Harga</th>
                                            <th>Stok</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Id</th>
                                            <th>Nama</th>
                                            <th>Kategori</th>
                                            <th>Merek</th>
                                            <th>Tipe</th>
                                            <th>Harga</th>
                                            <th>Stok</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php
                                        $categoryFilter = isset($_GET['category']) && $_GET['category'] != 'all' ? $_GET['category'] : '';
                                        $minPrice = isset($_GET['min_price']) ? (int)$_GET['min_price'] : 0;
                                        $maxPrice = isset($_GET['max_price']) ? (int)$_GET['max_price'] : 0;

                                        if ($minPrice && $maxPrice) {
                                            $queryProducts .= $categoryFilter ? " AND" : " WHERE";
                                            $queryProducts .= " p.price BETWEEN $minPrice AND $maxPrice";
                                        }

                                        if ($categoryFilter) {
                                            $queryProducts .= " WHERE c.category_name = '$categoryFilter'";
                                        }

                                        $showProducts = $conn->query($queryProducts);
                                        while ($row = $showProducts->fetch_assoc()):
                                        ?>
                                            <tr>
                                                <td><?php echo $row['product_id']; ?></td>
                                                <td><?php echo $row['name']; ?></td>
                                                <td><?php echo $row['category_name']; ?></td>
                                                <td><?php echo $row['brand']; ?></td>
                                                <td><?php echo $row['model_number']; ?></td>
                                                <td><?php echo $row['price']; ?></td>
                                                <td><?php echo $row['stock_quantity']; ?></td>
                                                <td>
                                                    <a href="editProducts.php?id=<?php echo $row['product_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                                    <a href="deleteProducts.php?id=<?php echo $row['product_id']; ?>" class="btn btn-danger btn-sm">Keluarkan</a>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </form>
                        </div>

                    </div>
                </div>
            </main>
            <footer class=" py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div
                        class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; Icikiwir 2024</div>
                        <div>
                            <a href="#">Privacy Policy</a>
                            &middot;
                            <a href="#">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>


    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script
        src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
        crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
    <script src="js/scripts.js"></script>
</body>

</html>