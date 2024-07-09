<?php
include 'Database.php';

// php kwdikas gia ton xhrismo image 
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT photo FROM listings WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($photo);
    $stmt->fetch();

    var_dump($photo);
    header("Content-Type: image/jpeg");
    echo $photo;

    $stmt->close();
}

$conn->close();
?>
