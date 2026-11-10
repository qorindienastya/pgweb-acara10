<!DOCTYPE html>
<html>
<head>
    <title>Form Edit Data TPS</title>
</head>
<body>
<h2>Form Edit Data TPS</h2>

<?php
    // 1. Mengatur parameter koneksi ke server database MySQL
    $servername = "localhost"; 
    $username   = "root";     
    $password   = "";          
    $dbname     = "tps_magelang"; // Nama database target

    // 2. Membuka koneksi baru ke database menggunakan MySQLi
    $conn = new mysqli($servername, $username, $password, $dbname);

    // 3. Memeriksa keberhasilan koneksi database
    if ($conn->connect_error){
        // Jika gagal, hentikan eksekusi dan tampilkan pesan error
        die("Connection failed: " . $conn->connect_error);
    }

    // 4. Memeriksa apakah parameter 'id' dikirimkan melalui URL (metode GET)
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        
        // 5. Query SQL untuk mengambil data TPS spesifik berdasarkan ID
        $sql = "SELECT * FROM tps WHERE id = $id";
        $result = $conn->query($sql);

        // 6. Jika data ditemukan, cetak formulir edit dengan nilai bawaan dari database
        if ($result && $result->num_rows > 0) {
            // Form akan dikirim ke 'edit.php' (metode POST) dan menjalankan validasi JS sebelum dikirim
            echo "<form action='edit.php' onsubmit='return validateForm(event)' method='post'>";
            
            while($row = $result->fetch_assoc()) {
                // Input hidden untuk menyimpan ID TPS tanpa menampikkannya secara langsung di UI
                echo "<input type='hidden' name='id' value='".$row['id']."'><br>";
                
                // Field input untuk Nama TPS
                echo "<label for='nama_tps'>Nama TPS:</label><br>";
                echo "<input type='text' id='nama_tps' name='nama_tps' value='".$row['Nama TPS']."' required><br><br>";
                
                // Field input untuk Kategori
                echo "<label for='kategori'>Kategori:</label><br>";
                echo "<input type='text' id='kategori' name='kategori' value='".$row['Kategori']."' required><br><br>";
                
                // Field input untuk koordinat Latitude
                echo "<label for='lat'>Latitude:</label><br>";
                echo "<input type='text' id='lat' name='latitude' value='".$row['Latitude']."' required><br><br>";
                
                // Field input untuk koordinat Longitude
                echo "<label for='long'>Longitude:</label><br>";
                echo "<input type='text' id='long' name='longitude' value='".$row['Longitude']."' required><br><br>";
                
                // Field input untuk Tahun Berdiri
                echo "<label for='tahun_berdiri'>Tahun Berdiri:</label><br>";
                echo "<input type='text' id='tahun_berdiri' name='tahun_berdiri' value='".$row['Tahun Berdiri']."'><br><br>";
                
                // Field textarea untuk Alamat
                echo "<label for='alamat'>Alamat:</label><br>";
                echo "<textarea id='alamat' name='alamat' rows='3' cols='30'>".$row['Alamat']."</textarea><br><br>";
            }
            // Tombol submit untuk menyimpan perubahan data
            echo "<input type='submit' value='Simpan Perubahan'>";
            echo "</form>";
        } else {
            echo "Data TPS tidak ditemukan.";
        }
    } else {
        echo "ID tidak ditemukan.";
    }

    // 7. Menutup koneksi database
    $conn->close();
?>

<!-- Paragraf khusus tempat pesan error validasi JavaScript akan ditampilkan -->
<p id="informasi" style="color: red;"></p>

<!-- Skrip JavaScript untuk validasi input sebelum form dikirimkan -->
<script>
    function validateForm(event){
        // 1. Mengambil nilai dari field Latitude, Longitude, dan Tahun Berdiri berdasarkan ID
        let lat = document.getElementById("lat").value;
        let long = document.getElementById("long").value;
        let tahun = document.getElementById("tahun_berdiri").value;
        let text = "";
        
        // 2. Mengecek apakah nilai Latitude, Longitude, atau Tahun Berdiri mengandung karakter selain angka (isNaN)
        if (isNaN(lat) || isNaN(long) || isNaN(tahun)) {
            text = "Data Latitude, Longitude, dan Tahun Berdiri harus berupa angka!";
            
            // 3. Batalkan proses pengiriman form jika nilai input tidak valid
            event.preventDefault();
        }
        
        // 4. Cetak pesan kesalahan ke elemen HTML berpenebak ID 'informasi'
        document.getElementById("informasi").innerHTML = text;
    }
</script>
</body>
</html>