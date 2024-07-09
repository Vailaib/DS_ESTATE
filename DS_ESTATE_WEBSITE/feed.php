<?php
//kanw include to header kai to database.php poy einai upeuthhno gia thn sundesh me to database.
include("header.php");
include("Database.php");

//arxikopoiw ena sql query poy trabaei ta listings
$sql = "SELECT id, title, location, num_rooms, price, photo FROM listings";
//kai apothhkeuw ta apotelesmata aytou tou query sto $result
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listings Feed</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <input type="text" id="search" placeholder="Search listings...">
    <div class="container_feed">
    <?php  
        //gemizw dunamika to feed me ta listings poy uparxony sto database me xrhsh php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='listing'>";
                echo "<img src='" . htmlspecialchars($row["photo"]) . "' alt='Listing Photo'>";
                echo "<h2>" . htmlspecialchars($row["title"]) . "</h2>";
                echo "<p>Location: " . htmlspecialchars($row["location"]) . "</p>";
                echo "<p>Number of Rooms: " . htmlspecialchars($row["num_rooms"]) . "</p>";
                echo "<p>Price: $" . htmlspecialchars($row["price"]) . "</p>";
        
                // passarw ayto to data me ena hidden post form kai to stelnw sto book.php wste na proxwrhsei me thn krathsh o xrhsths.
                echo "<form action='book.php' method='post' onsubmit='return checkLoginStatus(event);'>";
                echo "<input type='hidden' name='listing_id' value='" . htmlspecialchars($row['id']) . "'>";
                echo "<input type='hidden' name='listing_title' value='" . htmlspecialchars($row['title']) . "'>";
                echo "<input type='hidden' name='price' value='" . htmlspecialchars($row['price']) . "'>";
                
                echo "<button id='bookBtn' type='submit'>Book</button>";
                
                echo "</form>";
                echo "</div>";
            }
        } else {
            echo "0 results";
        }
        
        $conn->close();
        ?>
    </div>
    <!-- script poy dixnei ston xrhsth ta listings me bash ayto poy exei kanei search -->
    <script>
    document.getElementById('search').addEventListener('keyup', function() {
        let filter = this.value.toUpperCase();
        let listings = document.getElementsByClassName('listing');

        for (let i = 0; i < listings.length; i++) {
            let title = listings[i].getElementsByTagName("h2")[0];
            if (title) {
                let textValue = title.textContent || title.innerText;
                if (textValue.toUpperCase().indexOf(filter) > -1) {
                    listings[i].style.display = "";
                } else {
                    listings[i].style.display = "none";
                }
            }
        }
    });
    </script>
    <!-- script poy epitrepei ston xrhsth na pathsei to koympi book kai na paei sto book.php mono ean eiani logged in -->
<script>
function checkLoginStatus(event) {
    <?php if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) { ?>
        event.preventDefault();  // stamataei to form submission
        showLoginModal();
        return false;
    <?php } else { ?>
        return true;  // ean einai logged in tou epitrepei
    <?php } ?>
}

</script>
<div id="loginModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeLoginModal()">&times;</span>
        <p>You need to be logged in.</p>
        <button onclick="window.location.href='login.php'">Login</button>
    </div>
</div>


    <?php include("footer.php")?>
</body>
</html>