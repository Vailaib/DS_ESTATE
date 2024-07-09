<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You</title>
    <style>
        /* aplo style gia to conatiner */
        .thankyou-container {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin: 50px auto;
            max-width: 600px;
            padding: 20px;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center; 
        }

        .thankyou-container h2 {
            color: #333;
            font-size: 2em;
            margin-bottom: 1em;
        }

        .thankyou-container p {
            color: #555;
            font-size: 1.2em;
            margin-bottom: 2em;
        }

        .thankyou-container button {
            background-color: #FF5A5F;
            border: none;
            border-radius: 5px;
            color: white;
            cursor: pointer;
            font-size: 1em;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
        }

        .thankyou-container button:hover {
            background-color: #e14a4e;
        }

        /*gia mobile */
        @media (max-width: 600px) {
            .thankyou-container {
                width: 90%;
                padding: 10px;
            }

            .thankyou-container h2 {
                font-size: 1.5em;
            }

            .thankyou-container p {
                font-size: 1em;
            }
        }
    </style>
</head>
<body>
    <div class="thankyou-container">
        <h2>Thank You!</h2>
        <?php
           //me bash to name poy eixame apothhkeusei prwhgoymenos sto SESSION euxaristei ton xrhsth me to onoma toy gia thn kraths pyo ekane
                echo "<p>Thank you, " . htmlspecialchars($_SESSION['name']) . ", for booking with us. We appreciate you choosing us for your stay.</p>";
        ?>
        <!-- patwntas to koympi home kanei redirect sto index.php -->
        <button onclick="window.location.href='index.php'">Go to Home</button>
    </div>
</body>
</html>