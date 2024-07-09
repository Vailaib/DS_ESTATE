<!-- aplo html gia to header -->
<?php session_start();?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DS estate</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <nav>
            <a class="logo" id="homeBtn" href="index.php">DS estate</a>
            <div class="menu-icon" id="menuIcon">&#9776;</div> <!-- menu gia mobile -->
            <ul class="nav-links" id="navLinks">
                <li><a id="homeBtn" href="index.php">Home</a></li>
                <li><a id="feedBtn" href="feed.php">Feed</a></li>
                <!-- ean o xrhths einai sundedemenos tote ton kanei redirect sto create_listing.php  -->
                <?php if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true){
                    echo "<li><a href='create_listing.php'>Become a Host</a></li>";  
                }
                else{
                    // alliws tou emfanizei mynhma oti prepei na einai logged in
                    echo "<li><a href='#' onclick='showLoginModal()'>Become a Host</a></li>";
                }
                ?>
                <?php
                // ean o xrhsths einai sundedemenos tote tou emfanizei sto navbar thn epilogh logout kai to username tou
                if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
                    echo "<li><a id='logout' href='#' onclick='showModal()'>Logout</a></li>";
                    echo "<li><a id='username'>" . htmlspecialchars($_SESSION['username']) . "</a></li>";
                    
                }
                //alliws tou emfanizei tis epiloges login kai register           
                else {
                    echo "<li><a id='loginBtn' href='login.php'>Login</a></li>";
                    echo "<li><a id='registerBtn' href='Register.php'>Register</a></li>";
                     }
                ?>
            </ul>
             <!-- to modal tou login -->

             <div id="loginModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeLoginModal()">&times;</span>
        <p>You need to be logged in.</p>
        <button onclick="window.location.href='login.php'">Login</button>
    </div>
</div>

        <div id="myModal" class="modal">
        <!-- to modal tou logout  -->
             <div class="modal-content">
                <span class="close">&times;</span>
                <p>Are you sure you want to logout?</p>
                <button id="confirmLogout">Yes, Logout</button>
                <button id="cancelLogout">Cancel</button>
        </div>
    </div>
        </nav>

<script src="script.js"></script>
    </header>
    