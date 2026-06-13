<!-- 2572001 - Joshua Christopher Gunawan -->
<?php
include_once("koneksi.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ini PHP</title>
    <style>
        table, th, td,tr{
        border-collapse: collapse;
        border: 1px solid black;
        }

        table{
            margin: 3px;
            padding: 3px;
        }
    </style>
</head>
<body>
    <h1>ini halaman PHP pertamaku</h1>
    <?php
    $name = "Joshua";
    $age = 19;

    echo "Ini dari PHP.";
    echo "<p>Halo, nama saya " . $name . " dan saya " . $age . " tahun.</p>";
    ?>


    <fieldset>
        <legend>Isian Data</legend>
        <form action="proses.php" method="POST">
            <input type="text" name="nama" placeholder="First name"><br>
            <input type="email" name="email" placeholder="Email"><br>
            <input type="submit" name="btnSubmit" value="Simpan">    
        </form>
    </fieldset>
<br>
<?php
$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
try {
    if ($keyword != '') {
        $sql = "SELECT user_id, first_name, email FROM pengguna WHERE first_name LIKE :keyword OR email LIKE :keyword";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'keyword' => '%' . $keyword . '%'
        ]);
    } else {
        $sql = "SELECT user_id, first_name, email FROM pengguna";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
    }
?>
    <form action="index.php" method="GET">
        <input type="text" name="keyword" value="<?= htmlspecialchars($keyword) ?>">
        <button type="submit">Cari</button>
    </form>


<?php
$msg = isset($_GET['msg']) ? trim($_GET['msg']) : '';
echo "<span style='color:red'>" . $msg . "</span>";
    if ($stmt->rowCount() > 0) {
        echo "<table><tr><th>ID</th><th>Firstname</th><th>Email</th></tr>";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . $row['user_id'] . "</td>";
            echo "<td>" . $row['first_name'] . "</td>";
            echo "<td>" . $row['email'] . "</td>";
            echo "</tr>";
    }
    echo "</table>";
    unset($result);
    } else {
        echo "No records found.";
    }
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    $conn = null;
?>
</body>
</html>
