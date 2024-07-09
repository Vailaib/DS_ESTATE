<?php
include("header.php");
include("Database.php");

// arxikopoiw message metablhth
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //ean exw post apothhkeuw ta input tou xrhsth se metablhtes
    $name = trim($_POST['name']);
    $surname = trim($_POST['surname']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // elegxos gia to ean ta name kai surname apoteloynte apo xaraxthres mono
    if (!ctype_alpha($name)) {
        $message = "Your name should only contain letters.";
    } elseif (!ctype_alpha($surname)) {
        $message = "Your surname should only contain letters.";
    }
    
    // elexgos gia to ean to password einai <4 kai >10 kai >=1 arithmo
    if (strlen($password) < 4 || strlen($password) > 10) {
        $message = "Password should be between 4 and 10 characters.";
    } elseif (!preg_match('/\d/', $password)) {
        $message = "Password should contain at least one number.";
    }
    
    // tsekarei ean exei to swsto format sto email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "email should be a valid format.";
    } else {
        // tsekarei ean to email einai hdh egeggrameno
        $stmt = $conn->prepare("SELECT * FROM user WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $message = "Email is already registered.";
        }
    }

    // blepei ean ta passwrods poy edwse einai idia 
    if ($password != $confirm_password) {
        $message = "Passwords do not match.";
    }

    //elegxos gia to ean to username einai monadiko
    $stmt = $conn->prepare("SELECT * FROM user WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $message = "Username is already taken.";
    }

    //ean oloi oi elegxoi einai okay tote to message tha einai empty kai tote proxwraei sthn eisagwgh tou neou xrhsth sto database
    if (empty($message)) {
        // eisagw ton user sto database
        $stmt = $conn->prepare("INSERT INTO user (name, surname, username, email, password) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $surname, $username, $email, $password);
        if ($stmt->execute()) {
            $message = "Registration successful!";
        } else {
            $message = "Error registering user.";
        }
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
    <title>Register - Ustay</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <main>
        <div class="container">
        <h2>Register</h2>
        <?php
        if (!empty($message)) {
            echo '<div class="message">' . $message . '</div>';
        }
            // ean htan epituxes to registration kanei redirect meta apo 1.3 secs sto login.php wste o xrhsths na sundethei
            if($message ==='Registration successful!'){
            echo '<script>
                    setTimeout(function() {
                        window.location.href = "login.php";
                    }, 1300); // 1.3 secs 
                </script>';
        }
        ?>
            <form id="registerForm" action="Register.php" method="POST">
                <label for="registername">Name:</label>
                <input type="name" id="name" name="name" required>
                <label for="registersurname">Surname:</label>
                <input type="surname" id="surname" name="surname" required>
                <label for="registerusername">Username:</label>
                <input type="username" id="username" name="username" required>
                <label for="registerEmail">Email:</label>
                <input type="email" id="email" name="email" required>
                <label for="registerPassword">Password:</label>
                <input type="password" id="password" name="password" required>
                <label for="registerConfirmPassword">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
                <button type="submit">Register</button>
            </form>
        </div>
    </main>
</body>
</html>

<?php include("footer.php"); ?>

