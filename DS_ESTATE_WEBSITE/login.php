<?php
include("header.php");
include("Database.php");

$message = ""; // arxikopoiw message metablhth

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['loginUsername']);
    $email = trim($_POST['loginEmail']);
    $password = trim($_POST['loginPassword']);
    $surname='';
    $name='';

    if (empty($email) || empty($password) || empty($username)) {
        $message = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format.";
    } else {
        // etoimazw to stemement kai kanw bind
        $stmt = $conn->prepare("SELECT password FROM user WHERE username = ? AND email = ?");
        $stmt->bind_param("ss", $username, $email);

        // to kanw execute
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // kanw bind to apotelesma
            $stmt->bind_result($db_password);
            $stmt->fetch();

            // tsekarw ama to password poy edwse o xrhsths tairiazei me ayto poy exw sto database me bash to username kai to email poy edwse
            if ($password === $db_password) {
                $message = "Login successful!";
                $stmt->close();
                $stmt = $conn->prepare("SELECT name, surname FROM user WHERE username = ? AND email = ?");
                $stmt->bind_param("ss", $username,$email);
                $stmt->execute();
                $stmt->bind_result($db_name, $db_surname);
                $stmt->fetch();
                //apothhkeyw se session metablhtes to data tou user gia mellonitkh xrhsh
                $message = "Login successful!";
                $_SESSION['login_message'] = $message;
                $_SESSION['username'] = $username;
                $_SESSION['loggedin'] = true;
                $_SESSION['email'] = $email;
                $_SESSION['name'] = $db_name; // edw apothkeuw to onoma kai to surname apo to database
                $_SESSION['surname'] = $db_surname; 
            } else {
                $message = "Incorrect password.";
            }
        } else {
            $message = "No account found with that username and email.";
        }

        // kleinw to statement
        $stmt->close();
    }
}

// kleinw to connection
$conn->close();
?>
   
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
<div class="wrapper">
    <div class="container">
        <h2>Login</h2>
        <?php
        if (!empty($message)) {
            echo '<div class="message">' . $message . '</div>';
        }
            //ean eixa epityxh sundesh kanw redirect sto index.php opoy o xrhsths einai pleon sundedemenos mesw tou session
            if($message ==='Login successful!'){
            echo '<script>
                    setTimeout(function() {
                        window.location.href = "index.php";
                    }, 1300); // 1.3 seconds delay
                </script>';
        }
        ?>
        <form id="loginForm" action="login.php" method="POST">
            <label for="loginUsername">Username:</label>
            <input type="username" id="loginUsername" name="loginUsername" required>
            <label for="loginEmail">Email:</label>
            <input type="email" id="loginEmail" name="loginEmail" required>
            <label for="loginPassword">Password:</label>
            <input type="password" id="loginPassword" name="loginPassword" required>
            <button type="submit">Login</button>
        </form>
    </div>
</div>
    </script> 
</body>
<?php include("footer.php")?>
</html>