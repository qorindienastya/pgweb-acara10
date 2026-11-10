<?php
// 1. Koneksi ke Database
$servername = "localhost"; 
$username   = "root";     
$password   = "";          
$dbname     = "tps_magelang";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error){
    die("Connection failed: " . $conn->connect_error);
}

// 2. Query Data dari tabel 'tps'
$sql = "SELECT * FROM tps"; 
$result = $conn->query($sql);

$locations = array();
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $locations[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<!-- Metadata -->
<meta charset="utf-8">
<title>Latihan Web GIS</title>
<!-- Leaflet CSS Library -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.5.1/dist/leaflet.css">
<style>
/* Tampilan peta fullscreen */
html, body, #map {
    height: 100%;
    width: 100%;
    margin: 0px;
}

/* Wadah Utama Tabel Mengambang (Transparan) */
#table-container {
    position: absolute;
    bottom: 25px;
    left: 15px;
    z-index: 1000;
    width: 620px; /* Lebar ditambah sedikit agar colspan aksi tidak tertekan */
    background-color: rgba(255, 255, 255, 0.45);
    padding: 12px;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    border: 1px solid rgba(255, 255, 255, 0.6);
}

/* Bagian Judul/Input: Latar Putih Diberi Padding Agar Teks Tidak Mepet */
.input-header {
    background-color: #ffffff;
    padding: 10px 15px;
    margin-bottom: 10px;
    border-radius: 6px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

/* Area Scroll: Vertikal Aktif */
.table-scroll-area {
    max-height: 300px;    
    overflow-y: scroll;   
    overflow-x: hidden;   
}

#table-container table {
    width: 100%;
    font-size: 12px;
    background-color: #fff;
    border-collapse: collapse;
}

#table-container th, #table-container td {
    padding: 6px 8px;
    word-wrap: break-word;
    text-align: center;
}

#table-container td.text-left {
    text-align: left;
}
</style>
</head>
<body>
<!-- Leaflet JavaScript Library -->
<script src="https://unpkg.com/leaflet@1.5.1/dist/leaflet.js"></script>
<div id="map"></div>

<!-- Tabel Mengambang -->
<div id="table-container">
    <!-- Header Input: Posisi Tetap Diam dengan Latar Putih Lega -->
    <div class="input-header">
        <a href='input/index.html' style="font-weight: bold; color: blue; text-decoration: underline;">Tambah Data TPS</a>
    </div>

    <!-- Area Scroll khusus Atas-Bawah -->
    <div class="table-scroll-area">
        <?php if (count($locations) > 0): ?>
            <table border='1' cellpadding='6' cellspacing='0'>
                <thead>
                    <tr style="background-color: #f2f2f2;">
                        <th style="width: 30px;">No</th>
                        <th>Nama TPS</th>
                        <th>Kategori</th>
                        <th style="width: 55px;">Latitude</th>
                        <th style="width: 55px;">Longitude</th>
                        <th style="width: 40px;">Tahun</th>
                        <th>Alamat</th>
                        <th colspan="2" style="width: 90px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    foreach ($locations as $row): 
                    ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td class="text-left"><?php echo htmlspecialchars($row["Nama TPS"]); ?></td>
                            <td class="text-left"><?php echo htmlspecialchars($row["Kategori"]); ?></td>
                            <td><?php echo htmlspecialchars($row["Latitude"]); ?></td>
                            <td><?php echo htmlspecialchars($row["Longitude"]); ?></td>
                            <td><?php echo htmlspecialchars($row["Tahun Berdiri"]); ?></td>
                            <td class="text-left"><?php echo htmlspecialchars($row["Alamat"]); ?></td>
                            <td style="white-space: nowrap;"><a href='edit/index.php?id=<?php echo $row["id"]; ?>'>Edit</a></td>
                            <td style="white-space: nowrap;"><a href='delete.php?id=<?php echo $row["id"]; ?>')">Hapus</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>0 results</p>
        <?php endif; ?>
    </div>
</div>

<script>
/* Initial Map (Fokus Magelang) */
var map = L.map('map').setView([-7.479, 110.209], 13);

/* Tile Basemap */
var basemap1 = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
    attribution: 'Tiles &copy; Esri'
});
var basemap2 = L.tileLayer('https://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
    maxZoom: 20,
    subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
    attribution: 'Map data &copy; Google'
});
basemap1.addTo(map);

/* Layer Group Marker Database */
var markerGroup = L.layerGroup().addTo(map);
var dbData = <?php echo json_encode($locations); ?>;

dbData.forEach(function(item) {
    // mengubah inputnya dari text menjadi koordinat lat, long
    var lat = parseFloat(item["Latitude"]);
    var lng = parseFloat(item["Longitude"]);

    if (!isNaN(lat) && !isNaN(lng)) {
        /* Marker standar Leaflet (biru) */
        var marker = L.marker([lat, lng]);
        
        marker.bindPopup(
            "<b>" + item["Nama TPS"] + "</b><br>" +
            "Kategori: " + item["Kategori"] + "<br>" +
            "Alamat: " + item["Alamat"]
        );

        marker.addTo(markerGroup);
    }
});

/* Control Layer */
var baseMaps = {
    "Esri Imagery": basemap1,
    "Google Street": basemap2
};
var overlayMaps = {
    "Titik TPS Magelang": markerGroup
};
L.control.layers(baseMaps, overlayMaps, {collapsed: false}).addTo(map);

/* Scale Bar */
L.control.scale({
    maxWidth: 150, 
    position: 'bottomright'
}).addTo(map);
</script>
</body>
</html>
<?php 
$conn->close(); 
?>