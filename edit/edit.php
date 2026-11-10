<?php
    // 1. Mengatur konfigurasi parameter koneksi ke database MySQL
    $servername = "localhost"; 
    $username   = "root";     
    $password   = "";          
    $dbname     = "tps_magelang"; // Database tujuan

    // 2. Membuat objek koneksi baru menggunakan class MySQLi
    $conn = new mysqli($servername, $username, $password, $dbname);

    // 3. Memeriksa apakah terjadi kesalahan saat menyambungkan ke database
    if ($conn->connect_error){
        // Jika gagal, hentikan eksekusi skrip dan tampilkan pesan kesalahan
        die("Connection failed: " . $conn->connect_error);
    }

    // 4. Memeriksa apakah form dikirim menggunakan metode POST
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        // 5. Menangkap data yang dikirimkan dari form edit/index.php melalui $_POST
        $id            = $_POST['id'];
        $nama_tps      = $_POST['nama_tps'];
        $kategori      = $_POST['kategori'];
        $latitude      = $_POST['latitude'];
        $longitude     = $_POST['longitude'];
        $tahun_berdiri = $_POST['tahun_berdiri'];
        $alamat        = $_POST['alamat'];

        // 6. Menyusun query SQL UPDATE untuk memperbarui data TPS berdasarkan ID
        $sql = "UPDATE tps SET 
                    `Nama TPS` = '$nama_tps',
                    `Kategori` = '$kategori',
                    `Latitude` = '$latitude',
                    `Longitude` = '$longitude',
                    `Tahun Berdiri` = '$tahun_berdiri',
                    `Alamat` = '$alamat'
                WHERE id = $id";

        // 7. Mengeksekusi query dan mengecek apakah proses pembaruan berhasil
        if ($conn->query($sql) === TRUE) {
            // Jika berhasil, alihkan lokasi browser kembali ke index.php utama (di luar folder edit)
            header("Location: ../index.php");
            
            // Hentikan eksekusi skrip secara total setelah melakukan redirect
            exit();
        } else {
            // Tampilkan pesan error jika query SQL gagal dijalankan
            echo "Error updating record: " . $conn->error;
        }
    }

    // 8. Menutup jalur koneksi ke database untuk menghemat sumber daya server
    $conn->close();
?>