<?php
include_once("koneksi.php");

    $firstName = FILTER_INPUT(INPUT_POST, "nama");
    $email = FILTER_INPUT(INPUT_POST, "email");
    $btnSubmit = FILTER_INPUT(INPUT_POST, "btnSubmit");
    
    if($btnSubmit){
        try {
            $sql = "INSERT INTO pengguna (first_name, email) VALUES (:fname, :email)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
            'fname' => $firstName,
            'email' => $email
            ]);
            $msg = "New record created successfully";
            } catch(PDOException $e) {
            $msg = $sql . "<br>" . $e->getMessage();
            }
            $conn = null;
            header("location:index.php?msg=".$msg);
            exit;
        }
?>
