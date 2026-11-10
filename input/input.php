<?php
    // 1. Menangkap data yang dikirimkan dari form (input/index.html) melalui metode POST
    // Nama di dalam [''] disesuaikan dengan atribut 'name' pada form input TPS
    $nama_tps      = $_POST['nama_tps'];
    $kategori      = $_POST['kategori'];
    $latitude      = $_POST['latitude'];
    $longitude     = $_POST['longitude'];
    $tahun_berdiri = $_POST['tahun_berdiri'];
    $alamat        = $_POST['alamat'];

    // 2. Menyiapkan parameter konfigurasi koneksi ke MySQL
    $servername = "localhost";
    $username   = "root";
    $password   = "";
    $dbname     = "tps_magelang"; // Disesuaikan dari 'latihan' ke 'tps_magelang'

    // 3. Membuka koneksi baru ke database
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Mengecek apakah koneksi database berhasil
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // 4. Menyusun query SQL INSERT INTO untuk memasukkan data baru ke tabel 'tps'
    // Catatan: Nama kolom ber-spasi seperti `Nama TPS` dan `Tahun Berdiri` dibungkus backtick (`)
    $sql = "INSERT INTO tps (`Nama TPS`, `Kategori`, `Latitude`, `Longitude`, `Tahun Berdiri`, `Alamat`)
            VALUES ('$nama_tps', '$kategori', $latitude, $longitude, $tahun_berdiri, '$alamat')";

    // 5. Eksekusi query dan cek keberhasilannya
    if ($conn->query($sql) === TRUE) {
        // Tampilkan pesan sukses ke layar (tanpa redirect) sesuai pola modul
        echo "New record created successfully";
        $conn->close();
    } else {
        // Jika gagal simpan, tampilkan pesan error SQL
        echo "Error: " . $sql . "<br>" . $conn->error;
        $conn->close();
    }
?>