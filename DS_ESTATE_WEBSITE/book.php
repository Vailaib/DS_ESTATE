<?php
include("header.php");
include("Database.php");

//ean to method einai
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //pernei to listing id, title , price apo to post
    $listing_id = $_POST['listing_id'];
    $listing_title =$_POST['listing_title'];
    $price2 =$_POST['price'];
        //check ean to listing id einai valid
    if (!$listing_id || $listing_id <= 0) {
        echo "Invalid listing ID";
        exit;
    }


    // fetcharw plhrofories gia ayto to listing id
    $sql = "SELECT * FROM listings WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $listing_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Check if listing exists
    if (mysqli_num_rows($result) == 0) {
        echo "Listing not found";
        exit;
    }
    
    //apothhkeyw tis plhrofories aytes sto result
    $listing = mysqli_fetch_assoc($result);

    //arxikopoiw kapoies metablhtes 
    $check_in_date = '';
    $check_out_date = '';
    $name = $_SESSION['name'] ?? '';
    $email = $_SESSION['email'] ?? '';
    $surname = $_SESSION['surname'] ?? '';
    
    //arxikopoiw epishs ena message
    $error_message = '';
    
    // apoththkeuyei ayta poy phrame apo to post se metablhtes
    $check_in_date = $_POST['check_in_date'] ?? '';
    $check_out_date = $_POST['check_out_date'] ?? '';
    $price = $_POST['totalPrice2'] ?? '';

    // function gia na blepw ean ena value einai empty
    function isNotEmpty($value) {
        return isset($value) && trim($value) !== '';
    }

    // checkarw ean einai valid ta input
    if (!isNotEmpty($check_in_date)) {
        $error_message = 'Check-in date is required.';
    } elseif (!isNotEmpty($check_out_date)) {
        $error_message = 'Check-out date is required.';
    } elseif (!isNotEmpty($name)) {
        $error_message = 'Name is required.';
    } elseif (!isNotEmpty($email)) {
        $error_message = 'Email is required.';
    } elseif (!isNotEmpty($surname)) {
        $error_message = 'Surname is required.';
    } elseif (!isNotEmpty($price) || !is_numeric($price)) {
        $error_message = 'Valid price is required.';
    } else {
        //ean ginoyn ola ta check kai einai ola ta input valid tote proxoraw

        // se eisagwgh tou reservation sto database
        $sql = "INSERT INTO reservations (property_name, name, email, surname, not_available_from, not_available_until, price) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt === false) {
            die('Error preparing statement: ' . mysqli_error($conn));
        }

        mysqli_stmt_bind_param($stmt, "ssssssd", $listing_title, $name, $email, $surname, $check_in_date, $check_out_date, $price);

        mysqli_stmt_execute($stmt);

        // telos tsekarw ean egine to insertion sto databasee kai ean egine kateuthhnw ton xrhsth se ena ksexwristo php page opoy ton
        //exaristw gia thn krathsh
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            header("Location: thankyou.php");
            exit;
        } else {
            echo "Error adding reservation: " . mysqli_error($conn);
        }

        // kleinw statement kai connectino
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Listing</title>
</head>
<body>
<div class="book_wrapper">
    <div class="listing" id="booking">
        <h2><?php echo htmlspecialchars($listing['title']); ?></h2>
        <?php echo "<img src='" . htmlspecialchars($listing["photo"]) . "' alt='Listing Photo'>"; ?>
        <p>Location: <?php echo htmlspecialchars($listing['location']); ?></p>
        <p>Number of Rooms: <?php echo htmlspecialchars($listing['num_rooms']); ?></p>
        <p>Price per Night: $<?php echo htmlspecialchars($listing['price']); ?></p>
    </div>
    <div class="notification" id="notification"></div>

    
    <form id="bookingForm" action="book.php" method="POST" class="form-container">
        <h2>Booking Information</h2>
        <div class="step-1">
            
            <label for="checkInDate">Check-In Date:</label>
            <input type="date" id="checkInDate" name="check_in_date" required>

            <label for="checkOutDate">Check-Out Date:</label>
            <input type="date" id="checkOutDate" name="check_out_date" required>
            <input type="hidden" id="property_name" name="property_name" value="<?php echo $listing_title; ?>">

            <button type="button" id="checkAvailability">Check Availability</button>
        </div>

        <div class="step-2" style="display: none;">
            
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($_SESSION['name']); ?>">

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($_SESSION['email']); ?>">

            <p>Discount Rate: <span id="discountRate"></span>%</p>
            <p>Total Price: $<span name="totalprice" id="totalPrice"></span></p>

            <input type="number" name="totalPrice2" id="totalPriceInput" hidden>

            <button type="submit" >Book Listing</button>
        </div>

        <!-- exw ayta ta hidden input wste na parw tis times tous apo to post -->
        <input type="hidden" name="listing_id" value="<?php echo htmlspecialchars($listing_id); ?>">
        <input type="hidden" id="property_name" name="listing_title" value="<?php echo $listing_title; ?>">
        <input type="hidden" id="price_per_night" name="price_per_night" value="<?php echo $price2; ?>">

    </form>
</div>

<?php include("footer.php") ?>
</body>
</html>