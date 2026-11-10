<?php
    // 1. Mengambil parameter 'id' data yang dikirimkan melalui URL (metode GET)
    $id = $_GET['id'];

    // 2. Mengatur konfigurasi parameter untuk koneksi ke MySQL server
    $servername = "localhost"; 
    $username   = "root";     
    $password   = "";          
    $dbname     = "tps_magelang"; // Nama database target

    // 3. Membuat objek koneksi baru menggunakan class MySQLi
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // 4. Memeriksa status keberhasilan koneksi ke database
    if ($conn->connect_error) {
        // Jika koneksi gagal, hentikan program dan tampilkan pesan error
        die("Connection failed: " . $conn->connect_error);
    }

    // 5. Menyusun query SQL DELETE untuk menghapus baris data berdasarkan ID
    $sql = "DELETE FROM tps WHERE id=$id";

    // 6. Mengeksekusi query dan mengecek apakah proses penghapusan berhasil
    if ($conn->query($sql) === TRUE) {
        // Menutup koneksi database sebelum melakukan pengalihan halaman
        $conn->close();
        
        // Memindahkan (redirect) browser secara otomatis kembali ke halaman utama index.php
        header("Location: index.php");
        exit(); // Menghentikan eksekusi skrip secara total setelah redirect
    } else {
        // Tampilkan pesan error jika query SQL gagal dijalankan
        echo "Error: " . $sql . "<br>" . $conn->error;
        
        // Menutup koneksi database pada skenario error
        $conn->close();
    }
?>