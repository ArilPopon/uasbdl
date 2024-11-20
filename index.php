<?php
include 'koneksi.php';
include 'functions.php';

// Konfigurasi pagination
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Query dasar untuk produk
$query_showProducts = "FROM Products p INNER JOIN Categories c ON p.category = c.category_id WHERE 1=1";

// Filter kategori
if (isset($_GET['category']) && $_GET['category'] != '') {
  $query_showProducts .= " AND p.category = " . (int)$_GET['category'];
}

// Filter pencarian
if (isset($_GET['search']) && $_GET['search'] != '') {
  $search = $_GET['search'];
  $query_showProducts .= " AND (
        LOWER(p.name) LIKE '%" . $search . "%' OR
        LOWER(p.brand) LIKE '%" . $search . "%' OR
        LOWER(c.category_name) LIKE '%" . $search . "%' OR
        LOWER(p.model_number) LIKE '%" . $search . "%' OR
        CAST(p.price AS CHAR) LIKE '%" . $search . "%' OR
        CAST(p.stock_quantity AS CHAR) LIKE '%" . $search . "%'
    )";
}

// Sorting
$order_by = getOrderBy(isset($_GET['sort']) ? $_GET['sort'] : '');

// Total data dan jumlah halaman
$total_data = getTotalData($conn, $query_showProducts);
$total_pages = ceil($total_data / $limit);

// Data produk dan kategori
$data = getProducts($conn, $query_showProducts, $order_by, $limit, $offset);
$categories = getCategories($conn);

// String query untuk pagination
$query_string = buildQueryString($_GET);

// Rentang halaman pagination
$max_pages_to_display = 3;
$start_page = max(1, $page - floor($max_pages_to_display / 2));
$end_page = min($total_pages, $start_page + $max_pages_to_display - 1);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-900 text-white">

  <div class="max-w-6xl mx-auto p-6">

    <h1 class="text-3xl font-semibold mb-6">Data Gudang</h1>

    <!-- Filter, Search dan Sorting -->
    <div class="mb-4">
      <form method="GET" class="flex justify-between items-center w-full space-x-4">

        <!-- Sorting-->
        <div class="flex space-x-4">
          <div class="flex items-center space-x-2">
            <select name="sort" onchange="this.form.submit()" class="border border-gray-600 rounded-md px-3 py-2 bg-gray-800 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
              <option value="">Urutkan</option>
              <option value="a-z" <?php if (isset($_GET['sort']) && $_GET['sort'] == 'a-z') echo 'selected'; ?>>Nama (A-Z)</option>
              <option value="z-a" <?php if (isset($_GET['sort']) && $_GET['sort'] == 'z-a') echo 'selected'; ?>>Nama (Z-A)</option>
            </select>
          </div>

          <!-- Kategori -->
          <div class="flex items-center space-x-2">
            <select name="category" onchange="this.form.submit()" class="border border-gray-600 rounded-md px-3 py-2 bg-gray-800 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
              <option value="">Pilih Kategori</option>
              <?php while ($cat = mysqli_fetch_array($categories)) { ?>
                <option value="<?php echo $cat['category_id']; ?>" <?php if (isset($_GET['category']) && $_GET['category'] == $cat['category_id']) echo 'selected'; ?>>
                  <?php echo $cat['category_name']; ?>
                </option>
              <?php } ?>
            </select>
          </div>
        </div>

        <!-- Search -->
        <div class="flex items-center ml-auto space-x-2">
          <input type="text" name="search" placeholder="Cari..." class="px-4 py-2 bg-gray-800 text-white rounded-md" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
          <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Cari</button>
        </div>
      </form>
    </div>

    <!-- Tabel Data -->
    <table class="w-full table-auto border-collapse mb-6 bg-gray-800 rounded-md shadow-md">
      <thead>
        <tr class="bg-gray-700 text-left">
          <th class="px-4 py-2 font-medium text-gray-300">ID</th>
          <th class="px-4 py-2 font-medium text-gray-300">NAMA</th>
          <th class="px-4 py-2 font-medium text-gray-300">KATEGORI</th>
          <th class="px-4 py-2 font-medium text-gray-300">MERK</th>
          <th class="px-4 py-2 font-medium text-gray-300">TIPE</th>
          <th class="px-4 py-2 font-medium text-gray-300">HARGA</th>
          <th class="px-4 py-2 font-medium text-gray-300">STOK</th>
          <!-- <th class="px-4 py-2 font-medium text-gray-300">Aksi</th> -->
        </tr>
      </thead>
      <tbody>
        <?php while ($row = mysqli_fetch_array($data)) { ?>
          <tr class="border-t">
            <td class="px-4 py-2"><?php echo $row['product_id']; ?></td>
            <td class="px-4 py-2"><?php echo $row['name']; ?></td>
            <td class="px-4 py-2"><?php echo $row['category_name']; ?></td>
            <td class="px-4 py-2"><?php echo $row['brand']; ?></td>
            <td class="px-4 py-2"><?php echo $row['model_number']; ?></td>
            <td class="px-4 py-2"><?php echo $row['price']; ?></td>
            <td class="px-4 py-2"><?php echo $row['stock_quantity']; ?></td>
            <!-- <td class="px-4 py-2">
              <a href="#" class="text-blue-400 hover:underline">edit</a>
              <a href="#" class="text-red-400 hover:underline ml-4">hapus</a>
            </td> -->
          </tr>
        <?php } ?>
      </tbody>
    </table>

    <!--  Pagination -->
    <div class="mb-4 text-gray-400">
      <p>Halaman <?php echo $page; ?> dari <?php echo $total_pages; ?></p>
    </div>

    <div class="flex justify-center space-x-2">
      <?php
      // First and Previous links
      if ($page > 1) {
        echo "<a href='?page=1&$query_string' class='px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700'>Pertama</a>";
        echo "<a href='?page=" . ($page - 1) . "&$query_string' class='px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700'>Sebelumnya</a>";
      } else {
        // Disable First and Previous buttons on the first page
        echo "<span class='px-4 py-2 bg-gray-700 text-gray-400 rounded-md'>Pertama</span>";
        echo "<span class='px-4 py-2 bg-gray-700 text-gray-400 rounded-md'>Sebelumnya</span>";
      }

      // Page numbers
      for ($i = $start_page; $i <= $end_page; $i++) {
        // Add 'active' class for the current page
        $active_class = ($i == $page) ? 'bg-slate-700' : 'bg-blue-600';
        echo "<a href='?page=$i&$query_string' class='px-4 py-2 $active_class text-white rounded-md hover:bg-blue-700'>$i</a>";
      }

      // Next and Last links
      if ($page < $total_pages) {
        echo "<a href='?page=" . ($page + 1) . "&$query_string' class='px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700'>Berikutnya</a>";
        echo "<a href='?page=$total_pages&$query_string' class='px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700'>Terakhir</a>";
      } else {
        // Disable Next and Last buttons on the last page
        echo "<span class='px-4 py-2 bg-gray-700 text-gray-400 rounded-md'>Berikutnya</span>";
        echo "<span class='px-4 py-2 bg-gray-700 text-gray-400 rounded-md'>Terakhir</span>";
      }
      ?>
    </div>


  </div>
</body>

</html>