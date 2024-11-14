<?php
// Menyimpan pesan sukses/gagal
$message = "";

if (isset($_POST['submit'])) {
    $target_file = basename($_FILES["fileToUpload"]["name"]); // Menyimpan file di direktori yang sama
    
    // Cek apakah file sudah ada
    if (file_exists($target_file)) {
        $message = "File already exists.";
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            $message = "File uploaded successfully.";
        } else {
            $message = "Error uploading file.";
        }
    }
}

// Proses delete file
if (isset($_GET['delete'])) {
    $file_to_delete = basename($_GET['delete']); // Hanya ambil nama file saja untuk keamanan
    if (file_exists($file_to_delete)) {
        unlink($file_to_delete); // Menghapus file
        $message = "File has been deleted.";
    } else {
        $message = "File not found.";
    }
}
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File List</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; }
        table, th, td { border: 1px solid #ddd; }
        th, td { padding: 10px; text-align: center; }
        th { background-color: #f2f2f2; }
        .download-btn { background-color: #4CAF50; color: white; padding: 5px 10px; border: none; border-radius: 3px; cursor: pointer; text-decoration: none; }
        .download-btn:hover { background-color: #45a049; }
        .delete-btn { background-color: #f44336; color: white; padding: 5px 10px; border: none; border-radius: 3px; cursor: pointer; text-decoration: none; }
        .delete-btn:hover { background-color: #e53935; }
        .message { margin: 10px 0; padding: 10px; border-radius: 5px; background-color: #f0f0f0; color: #333; }
        .message.error { background-color: #f8d7da; color: #721c24; }
        .actions { display: flex; gap: 10px; justify-content: center; } /* Menambahkan flexbox untuk tata letak tombol */
    </style>
</head>
<body>

    <h2>Upload File</h2>

    <!-- Form untuk upload file -->
    <form class="upload-form" action="index.php" method="post" enctype="multipart/form-data">
        <input type="file" name="fileToUpload" id="fileToUpload">
        <input type="submit" value="Upload File" name="submit">
    </form>

    <!-- Menampilkan pesan sukses/gagal -->
    <?php if (!empty($message)): ?>
        <div class="message <?= strpos($message, 'Error') !== false ? 'error' : '' ?>">
            <?= $message ?>
        </div>
    <?php endif; ?>
    
    <table>
        <tr>
            <th>No</th>
            <th>File Name</th>
            <th>Actions</th>
        </tr>
        <?php
        // Mendapatkan semua file di direktori yang sama, kecuali . dan .. serta upload.php
        $files = array_diff(scandir(__DIR__), array('.', '..', 'index.php'));
        $i = 1;
        foreach ($files as $file):
            if (!is_dir($file)): 
        ?>
        <tr>
            <td><?php echo $i++; ?></td>
            <td><?php echo htmlspecialchars($file); ?></td>
            <td class="actions">
                <a href="<?php echo urlencode($file); ?>" class="download-btn" download>Download</a>
                <a href="?delete=<?php echo urlencode($file); ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this file?')">Delete</a>
            </td>
        </tr>
        <?php
            endif;
        endforeach;
        ?>
    </table>

</body>
</html>
