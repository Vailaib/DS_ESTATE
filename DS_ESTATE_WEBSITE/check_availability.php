<?php
//arxikopoiw mia metablhth gia to message
$response = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // pairnw ola ta input apo to post poy egine sto book.php otan o xrhsths pathse to button check availability
    $check_in_date = filter_input(INPUT_POST, 'check_in_date', FILTER_SANITIZE_STRING);
    $check_out_date = filter_input(INPUT_POST, 'check_out_date', FILTER_SANITIZE_STRING);
    $price_per_night = filter_input(INPUT_POST, 'price_per_night', FILTER_VALIDATE_FLOAT);
    $listing_id = filter_input(INPUT_POST, 'listing_id', FILTER_SANITIZE_NUMBER_INT);
    $listing_title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);

    //ftianxw pali ena function poy blepei ean ena value einai empty
    function isNotEmpty($value) {
        return isset($value) && trim($value) !== '';
    }

    // checkarw ta input 
    if (!isNotEmpty($check_in_date) || !isNotEmpty($check_out_date) || !$listing_id || !isNotEmpty($listing_title) || !$price_per_night) {
        $response['error'] = 'Invalid input data.';
    } else {
        // ean eiani ola entaksei prospathw na sundethw sto database
        $servername = 'localhost';
        $username = 'root';
        $password = '';
        $dbname = 'ds_estate';

        $conn = mysqli_connect($servername, $username, $password, $dbname);
        //ean den petuxei to conection bgainei sxetiko error 
        if (!$conn) {
            $response['error'] = 'Connection failed: ' . mysqli_connect_error();
        //alliws arxizw thn logikh gia na dw ean to dwmatio poy epelekse o xrhsths einai diathesimo gia ta dates poy epelekse
        } else {
            
            // sql query poy tsekarei gia overlapping reservationss
            $sql = "SELECT * FROM reservations 
                    WHERE property_name = ? 
                    AND ((not_available_from <= ? AND not_available_until >= ?)
                    OR (not_available_from >= ? AND not_available_until <= ?)
                    OR (not_available_from <= ? AND not_available_until >= ?))";
            $stmt = mysqli_prepare($conn, $sql);

            if ($stmt === false) {
                $response['error'] = 'Error preparing statement: ' . mysqli_error($conn);
            } else {
                mysqli_stmt_bind_param($stmt, "sssssss", $listing_title, $check_in_date, $check_in_date, $check_in_date, $check_out_date, $check_out_date, $check_out_date);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                // printarei ta rows gia degub
                $num_rows = mysqli_num_rows($result);
                error_log("Number of rows returned: $num_rows");

                if ($num_rows > 0) {
                    $response['available'] = false;
                } else {
                    $response['available'] = true;
                    // ypologismos kostous me to discount
                    $nights = (strtotime($check_out_date) - strtotime($check_in_date)) / (60 * 60 * 24);
                    $discountRate = rand(10, 30) / 100;
                    $totalPrice = $price_per_night * $nights * (1 - $discountRate);
                    $response['discountRate'] = $discountRate;
                    $response['totalPrice'] = $totalPrice;
                }
            }
            mysqli_close($conn);
        }
    }
    echo json_encode($response);
}
?>
