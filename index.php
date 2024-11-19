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

    <h1 class="text-3xl font-semibold mb-6">Data Buku</h1>

    <div class="mb-4 space-x-4">
      <select class="border border-gray-600 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-800 text-white">
        <option value="">Urutkan</option>
        <option value="">Terbaru</option>
        <option value="">A-Z</option>
        <option value="">Z-A</option>
      </select>
      <select class="border border-gray-600 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-800 text-white">
        <option value="">Kategori</option>
        <option value="">Horror</option>
        <option value="">Komedi</option>
        <option value="">Romantis</option>
      </select>
      <input type="text" placeholder="search..." class="border border-gray-600 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-800 text-white">
    </div>

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
          <th class="px-4 py-2 font-medium text-gray-300">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php
        include 'koneksi.php';

        // Konfigurasi Pagination
        $limit = 10; // Jumlah data per halaman
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Halaman saat ini
        $offset = ($page - 1) * $limit; // Hitung data mulai

        // Hitung total data
        $result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM products");
        $row = mysqli_fetch_assoc($result);
        $total_data = $row['total'];
        $total_pages = ceil($total_data / $limit);

        // Query data dengan limit dan offset
        $data = mysqli_query($conn, "SELECT * FROM products LIMIT $limit OFFSET $offset");

        // Tampilkan data
        while ($row = mysqli_fetch_array($data)) {
        ?>
          <tr class="border-t">
            <td class="px-4 py-2"><?php echo $row['product_id']; ?></td>
            <td class="px-4 py-2"><?php echo $row['name']; ?></td>
            <td class="px-4 py-2"><?php echo $row['category']; ?></td>
            <td class="px-4 py-2"><?php echo $row['brand']; ?></td>
            <td class="px-4 py-2"><?php echo $row['model_number']; ?></td>
            <td class="px-4 py-2"><?php echo $row['price']; ?></td>
            <td class="px-4 py-2"><?php echo $row['stock_quantity']; ?></td>
            <td class="px-4 py-2">
              <a href="#" class="text-blue-400 hover:underline">edit</a>
              <a href="#" class="text-red-400 hover:underline ml-4">hapus</a>
            </td>
          </tr>
        <?php
        }
        ?>
      </tbody>
    </table>

    <!-- Tampilkan informasi halaman -->
    <div class="mb-4 text-gray-400">
      <p>Halaman <?php echo $page; ?> dari <?php echo $total_pages; ?></p>
    </div>

    <!-- Navigasi Pagination -->
    <div class="flex justify-center space-x-2">
      <!-- Tombol "Pertama" dan "Sebelumnya" -->
      <?php if ($page > 1): ?>
        <a href="?page=1" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Pertama</a>
        <a href="?page=<?php echo $page - 1; ?>" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Sebelumnya</a>
      <?php else: ?>
        <span class="px-4 py-2 bg-gray-700 text-gray-400 rounded-md">Pertama</span>
        <span class="px-4 py-2 bg-gray-700 text-gray-400 rounded-md">Sebelumnya</span>
      <?php endif; ?>

      <!-- Menampilkan halaman 1, 2, 3 dan seterusnya -->
      <?php
      $start_page = max(1, $page - 1);
      $end_page = min($total_pages, $page + 1);

      if ($page == 1) {
        $end_page = min($total_pages, 3);
      } elseif ($page == $total_pages) {
        $start_page = max(1, $total_pages - 2);
      }

      for ($i = $start_page; $i <= $end_page; $i++):
      ?>
        <a href="?page=<?php echo $i; ?>" class="px-4 py-2 rounded-md text-white bg-blue-600 hover:bg-blue-700 <?php if ($i == $page) echo 'font-semibold'; ?>"><?php echo $i; ?></a>
      <?php endfor; ?>

      <!-- Tombol "Berikutnya" dan "Terakhir" -->
      <?php if ($page < $total_pages): ?>
        <a href="?page=<?php echo $page + 1; ?>" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Berikutnya</a>
        <a href="?page=<?php echo $total_pages; ?>" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Terakhir</a>
      <?php else: ?>
        <span class="px-4 py-2 bg-gray-700 text-gray-400 rounded-md">Berikutnya</span>
        <span class="px-4 py-2 bg-gray-700 text-gray-400 rounded-md">Terakhir</span>
      <?php endif; ?>
    </div>

  </div>

</body>

</html>