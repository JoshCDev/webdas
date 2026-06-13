<!-- 2572001 - Joshua Christopher Gunawan -->
<?php
include_once 'koneksi.php';
$msg = "";
$pesan = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = isset($_POST["nama"]) ? trim($_POST["nama"]) : "";
    $asal = isset($_POST["asal"]) ? trim($_POST["asal"]) : "";
    $komentar = isset($_POST["komentar"]) ? trim($_POST["komentar"]) : "";

    if (empty($nama) || empty($asal) || empty($komentar)) {
        $msg = "Semua field wajib diisi.";
        $pesan = "error";
    } else {
        try {
            $stmt = $conn->prepare("INSERT INTO buku_tamu (nama, asal, komentar) VALUES (:nama, :asal, :komentar)");
            $stmt->execute([
                "nama" => $nama,
                "asal" => $asal,
                "komentar" => $komentar
            ]);
            $msg = "Komentar berhasil disimpan.";
            $pesan = "success";
        } catch (PDOException $e) {
            $msg = "Komentar gagal disimpan.";
            $pesan = "error";
        }
    }
}

$stmt = $conn->query("SELECT * FROM buku_tamu ORDER BY waktu DESC");
$totalKomentar = $stmt->rowCount();
$result = mysqli_query($conn2, "SELECT * FROM buku_tamu ORDER BY waktu DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="2572001 - Joshua Christopher Gunawan">
    <title>BukuTamu - 2572001</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main class="page">
        <h1>Buku Tamu</h1>

<?php
        if ($msg != "") {
            echo "<div class='alert alert-$pesan'>" . htmlspecialchars($msg) . "</div>";
        }
?>

        <form method="POST" action="">
            <label for="nama">Nama</label>
            <input type="text" id="nama" name="nama" placeholder="Nama lengkap kamu">

            <label for="asal">Asal Kota</label>
            <input type="text" id="asal" name="asal" placeholder="Contoh: Bandung">

            <label for="komentar">Komentar</label>
            <textarea id="komentar" name="komentar" placeholder="Tulis komentar atau kesanmu..."></textarea>

            <button name="btnSubmit" type="submit" class="button">Kirim Komentar</button>
        </form>

        <h2>Komentar Tamu <span>(<?php echo $totalKomentar; ?> komentar)</span></h2>

<?php
        if ($totalKomentar == 0) {
            echo "<p>Belum ada komentar</p>";
        } else {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='comment'>";
                echo "<div class='comment-name'>" . htmlspecialchars($row["nama"]) . "</div>";
                echo "<div class='comment-meta'>" . htmlspecialchars($row["asal"]) . " | " . htmlspecialchars($row["waktu"]) . "</div>";
                echo "<p>\"" . htmlspecialchars($row["komentar"]) . "\"</p>";
                echo "</div>";
            }
        }
?>

        <footer class="footer">2572001 - Joshua Christopher Gunawan</footer>
    </main>
</body>
</html>
