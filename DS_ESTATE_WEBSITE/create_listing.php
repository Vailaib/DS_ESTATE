<?php
include 'header.php';
include 'Database.php';

// ean exw POST 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //travaei ta input tou xrhsth apo to form kai ta apothhkeuei
    $title = $_POST['title'];
    $location = $_POST['location'];
    $num_rooms = $_POST['num_rooms'];
    $price = $_POST['price'];

    $msg = ""; //metabhth gia ta errors
    // elegxoi gia valid times
    if (!preg_match("/^[a-zA-Z\s]+$/", $title)) {
        $msg= "Title must contain only letters.";
    }

    elseif (!preg_match("/^[a-zA-Z\s]+$/", $location)) {
        $msg = "Location must contain only letters.";
    }


    elseif (!filter_var($num_rooms, FILTER_VALIDATE_INT) || (int)$num_rooms <= 0) {
        $msg = "Number of rooms must be a positive integer.";
    }

    elseif (!filter_var($price, FILTER_VALIDATE_INT) || (int)$price <= 0) {
        $msg = "Price must be a positive integer.";
    }
    else{
    // edw kanei handle to file upload
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $photo = $_FILES['photo'];
        $photoName = basename($photo['name']);
        $targetDir = 'images/';
        $targetFilePath = $targetDir . $photoName;
        
        // tsekarw ean to file uparxei hdh
        if (!file_exists($targetFilePath)) {
            move_uploaded_file($photo['tmp_name'], $targetFilePath);
        }
        //telos kanw insert sto database to kainourgio listing
        $sql = "INSERT INTO listings (title, location, num_rooms, price, photo) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssids", $title, $location, $num_rooms, $price, $targetFilePath);
        if(empty($msg)) {
        if ($stmt->execute()=== TRUE ) {
            $msg = "New listing created successfully";
            //afou ginei to insertion sto database anakateythhnw ton xrhsth sto feed.php wste na mporesei na dei to listing tou
            echo '<script>
                    setTimeout(function() {
                        window.location.href = "feed.php";
                    }, 1300); // 1.3 seconds delay
                </script>';
        } else {
            echo "Error: " . $stmt->error;
        }
    }
        $stmt->close();
    } else {
        $msg= "Error uploading photo";
    }

    $conn->close();
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Listing</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="create_listing_wrapper">
        <div class="form-container">
            <h2>Create New Listing</h2>
            <?php
            if (!empty($msg)) {
                echo '<div class="message">' . $msg . '</div>';
            }
            ?>
            <form action="create_listing.php" method="post" enctype="multipart/form-data">
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" required>
                
                <label for="location">Location:</label>
                <input type="text" id="location" name="location" required>
                
                <label for="num_rooms">Number of Rooms:</label>
                <input type="number" id="num_rooms" name="num_rooms" required>
                
                <label for="price">Price:</label>
                <input type="number" id="price" name="price" required>
                
                <label for="photo">Photo:</label>
                <input type="file" id="photo" name="photo" required>
                
                <button type="submit">Create Listing</button>
            </form>
        </div>
    </div>
</body>
<?php include"footer.php";?>
</html>