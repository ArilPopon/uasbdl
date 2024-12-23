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
    <title>Dashboard</title>
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
        <a class="navbar-brand ps-3 fw-bold" href="index.php">PT. RUNGKUT ELECTRONICS</a>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Dashboard</div>
                        <a class="nav-link active" href="index.php">
                            <div class="sb-nav-link-icon">
                                <i class="fas fa-tachometer-alt"></i>
                            </div>
                            Dashboard
                        </a>
                        <div class="sb-sidenav-menu-heading">Menu</div>
                        <a
                            class="nav-link"
                            href="products.php">
                            <div class="sb-nav-link-icon">
                                <i class="fas fa-boxes-stacked"></i>
                            </div>
                            Products
                        </a>
                        <a
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
                    <h1 class="mt-4">Dashboard</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                    <div class="row">
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-primary text-white mb-4">
                                <div class="card-body">Total Barang</div>
                                <div
                                    class="card-footer align-items-center justify-content-between">
                                    <div class="d-flex">
                                        <span class="small"><?php echo $countProducts['COUNT(*)']; ?></span>
                                        <div class="d-flex ms-auto">
                                            <a class="small text-white stretched-link" href="products.php">View Details</a>
                                            <div class="small text-white">
                                                <i class="ms-1 fas fa-angle-right"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-warning text-white mb-4">
                                <div class="card-body">Omset</div>
                                <div
                                    class="card-footer align-items-center justify-content-between">
                                    <div class="d-flex">
                                        <span class="small"><?php echo "Rp. " . $showOmset['total_omset']; ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-success text-white mb-4">
                                <div class="card-body">Total Barang Masuk</div>
                                <div
                                    class="card-footer align-items-center justify-content-between">
                                    <div class="d-flex">
                                        <span class="small"><?php echo $countTransactionsIn['COUNT(*)']; ?></span>
                                        <div class="d-flex ms-auto">
                                            <a class="small text-white stretched-link" href="products.php">View Details</a>
                                            <div class="small text-white">
                                                <i class="ms-1 fas fa-angle-right"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-danger text-white mb-4">
                                <div class="card-body">Total Barang Keluar</div>
                                <div
                                    class="card-footer align-items-center justify-content-between">
                                    <div class="d-flex">
                                        <span class="small"><?php echo $countTransactionsOut['COUNT(*)']; ?></span>
                                        <div class="d-flex ms-auto">
                                            <a class="small text-white stretched-link" href="products.php">View Details</a>
                                            <div class="small text-white">
                                                <i class="ms-1 fas fa-angle-right"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Barang Terlaris
                            <div class="card-body">
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
                                            <th>Total Terjual</th>
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
                                            <th>Total Terjual</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php
                                        $categoryFilter = isset($_GET['category']) && $_GET['category'] != 'all' ? $_GET['category'] : '';

                                        if ($categoryFilter) {
                                            $queryProducts .= " WHERE c.category_name = '$categoryFilter'";
                                        }

                                        while ($row = $showProductsTerlaris->fetch_assoc()):
                                        ?>
                                            <tr>
                                                <td><?php echo $row['product_id']; ?></td>
                                                <td><?php echo $row['name']; ?></td>
                                                <td><?php echo $row['category_name']; ?></td>
                                                <td><?php echo $row['brand']; ?></td>
                                                <td><?php echo $row['model_number']; ?></td>
                                                <td><?php echo $row['price']; ?></td>
                                                <td><?php echo $row['stock_quantity']; ?></td>
                                                <td><?php echo $row['total_sold_stock']; ?></td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
            </main>
            <footer class="py-4 bg-light mt-auto">
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
        src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"
        crossorigin="anonymous"></script>
    <script src="assets/demo/chart-area-demo.js"></script>
    <script src="assets/demo/chart-bar-demo.js"></script>
    <script
        src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
        crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
</body>

</html>