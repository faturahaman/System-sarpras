<?php
require '../config/dbConn.php';  // Include database connection file

// Process if the 'kembalikan' button is clicked
if (isset($_POST['kembalikan'])) {
    $id = $_POST['id'];  // Get the ID of the item to be returned
    $queryUpdate = "UPDATE tb_peminjaman SET status='Telah dikembalikan' WHERE id=$id";  // Update the status in the database

    mysqli_query($conn, $queryUpdate);  // Execute the update query
    // Show success message with SweetAlert and redirect to the page
    echo "<!DOCTYPE html>
    <html lang='en'>
    <head>
      <meta charset='UTF-8'>
      <meta name='viewport' content='width=device-width, initial-scale=1.0'>
      <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    </head>
    <body>
      <script>
        Swal.fire({
          icon: 'success',
          title: 'Barang Dikembalikan',
          text: 'Berhasil mengembalikan barang',
          confirmButtonText: 'Ok'
        }).then(function() {
            // Redirect to the items list page after success
            window.location.href = 'datapinjam.php'; 
        });
      </script>
    </body>
    </html>";
}

// Pagination logic
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;  // Get current page number
$limit = 5;  // Set the number of items per page
$offset = ($page - 1) * $limit;  // Calculate the offset for pagination

// Query to count the total number of records for pagination
$queryCount = "SELECT COUNT(*) AS total FROM tb_peminjaman";
$resultCount = mysqli_query($conn, $queryCount);
$totalRows = mysqli_fetch_assoc($resultCount)['total'];  // Get total rows
$totalPages = ceil($totalRows / $limit);  // Calculate total pages

// Fetch data for the current page
$query = "SELECT * FROM tb_peminjaman LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Dashboard Peminjaman Sarpras</title>
    <script src="https://cdn.tailwindcss.com"></script> <!-- TailwindCSS for styling -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" /> <!-- FontAwesome for icons -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet" /> <!-- Google Fonts -->
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            /* Set font family */
        }

        .sidebar {
            height: 100vh;
            /* Full screen height for sidebar */
        }
    </style>
</head>

<body>
    <div class="flex flex-col md:flex-row min-h-screen bg-gradient-to-r from-purple-400">
        <?php include '../asset/sidebar.php';  // Include sidebar 
        ?>

        <!-- Main content -->
        <div class="container p-6">
            <h1 class="text-3xl font-semibold mb-6">Data Peminjaman Sarpras</h1>
            <div class="flex justify-end gap-4 mb-4">
                <a href="export_excel.php" class="bg-green-500 text-white px-4 py-2 rounded-lg">Export Excel</a> <!-- Button to export data to Excel -->
            </div>

            <!-- Table to display loan data -->
            <table class="min-w-full table-auto bg-white shadow-lg rounded-lg">
                <thead class="bg-[#2E285B] text-white">
                    <tr>
                        <th class="py-3 px-4 text-left">No</th>
                        <th class="py-3 px-4 text-left">Nama Peminjam</th>
                        <th class="py-3 px-4 text-left">Kelas</th>
                        <th class="py-3 px-4 text-left">Nama Barang</th>
                        <th class="py-3 px-4 text-left">Waktu Peminjaman</th>
                        <th class="py-3 px-4 text-left">Status</th>
                        <th class="py-3 px-4 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Display the fetched records
                    if ($result && mysqli_num_rows($result) > 0) {
                        $no = $offset + 1;  // Adjust the numbering based on the offset
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td class='py-2 px-4'>" . $no++ . "</td>";  // Display row number
                            echo "<td class='py-2 px-4'>" . htmlspecialchars($row['namaPeminjam']) . "</td>";  // Display borrower name
                            echo "<td class='py-2 px-4'>" . htmlspecialchars($row['kelas']) . "</td>";  // Display class
                            echo "<td class='py-2 px-4'>" . htmlspecialchars($row['namaBarang']) . "</td>";  // Display item name
                            echo "<td class='py-2 px-4'>" . htmlspecialchars($row['WaktuPeminjaman']) . "</td>";  // Display borrowing time
                            echo "<td class='py-2 px-4'>" . ($row['status'] == 'Telah dikembalikan' ? '<span class=\"text-green-600\">Telah dikembalikan</span>' : 'Dipinjam') . "</td>";  // Display status
                            echo "<td class='py-2 px-4'>";  // Display action buttons
                            if ($row['status'] != 'Telah dikembalikan') {
                                echo "<button class='bg-red-500 text-white px-4 py-2 rounded-lg' onclick='openModal(" . $row['id'] . ")'>Kembalikan</button>";  // Button to return the item
                            } else {
                                echo "<div class='bg-gray-500 text-white px-4 py-2 rounded-lg'>Selesai</div>";  // Indicating completed return
                            }
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7' class='py-2 px-4 text-center'>Data tidak ditemukan</td></tr>";  // If no data found
                    };
                    ?>
                </tbody>
            </table>

            <!-- Pagination Controls -->
            <div class="flex justify-center mt-6">
                <nav>
                    <ul class="flex gap-2">
                        <!-- Previous Button -->
                        <?php if ($page > 1): ?>
                            <li><a href="?page=<?php echo $page - 1; ?>" class="bg-gray-500 text-white px-4 py-2 rounded-lg">Prev</a></li>
                        <?php else: ?>
                            <li><span class="bg-gray-300 text-gray-500 px-4 py-2 rounded-lg">Prev</span></li>
                        <?php endif; ?>

                        <!-- Page Numbers -->
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li>
                                <a href="?page=<?php echo $i; ?>" class="<?php echo $i == $page ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700'; ?> px-4 py-2 rounded-lg">
                                    <?php echo $i; ?>
                                </a>
                            </li>
                        <?php endfor; ?>

                        <!-- Next Button -->
                        <?php if ($page < $totalPages): ?>
                            <li><a href="?page=<?php echo $page + 1; ?>" class="bg-gray-500 text-white px-4 py-2 rounded-lg">Next</a></li>
                        <?php else: ?>
                            <li><span class="bg-gray-300 text-gray-500 px-4 py-2 rounded-lg">Next</span></li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        </div>

        <!-- Modal Konfirmasi (Confirmation Modal for item return) -->
        <div id="modal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center">
            <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm w-full">
                <h2 class="text-xl font-semibold mb-4">Konfirmasi Pengembalian</h2>
                <p>Apakah Anda yakin ingin mengembalikan barang ini?</p>
                <form action="" method="POST">
                    <input type="hidden" id="itemId" name="id">
                    <div class="mt-4 flex justify-end gap-4">
                        <button type="button" class="bg-gray-400 px-4 py-2 rounded-lg" onclick="closeModal()">Batal</button>
                        <button type="submit" name="kembalikan" class="bg-green-500 text-white px-4 py-2 rounded-lg">Kembalikan</button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            function openModal(id) {
                document.getElementById('itemId').value = id; // Set item ID for modal
                document.getElementById('modal').classList.remove('hidden'); // Show the modal
            }

            function closeModal() {
                document.getElementById('modal').classList.add('hidden'); // Hide the modal
            }
        </script>
</body>

</html>