<?php
// Menyimpan pesan sukses/gagal
$message = "";

if (isset($_POST['submit'])) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    
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
    $file_to_delete = "../" . $_GET['delete'];
    if (file_exists($file_to_delete)) {
        unlink($file_to_delete); // Menghapus file
        $message = "File has been deleted.";
    } else {
        $message = "File not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload and Manage Files</title>
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

    <!-- Tabel untuk daftar file -->
    <table>
        <tr>
            <th>No</th>
            <th>File Name</th>
            <th>Actions</th>
        </tr>

        <?php
        // Menampilkan daftar file di folder 'uploads'
        $dir = "../";
        if (is_dir($dir)) {
            $files = array_diff(scandir($dir), array('.', '..'));
            $no = 1;
            foreach ($files as $file) {
                echo "<tr>
                        <td>$no</td>
                        <td>$file</td>
                        <td class='actions'>
                            <a href='uploads/$file' class='btn-download' download>Download</a>
                            <a href='../file' class='btn-delete'>Delete</a>
                        </td>
                      </tr>";
                $no++;
            }
        }
        ?>
    </table>

    <script>
        // Menghilangkan pesan sukses/gagal setelah beberapa detik
        setTimeout(function() {
            var message = document.querySelector('.message');
            if (message) {
                message.style.display = 'none';
            }
        }, 5000); // 5 detik
    </script>

</body>
</html>
