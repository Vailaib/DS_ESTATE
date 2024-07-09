<!-- landing page tou site -->
<?php include("header.php")?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DS estate</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <main>
        <section id="home" class="hero">
            <h1>Welcome to Ds estate</h1>
            <p>Discover unique places that U can stay.</p>
        </section>
        
        <section class="explore" id="explore">
            <h2>Why Choose DS estate?</h2>
            <div class="benefits">
                <ul>
                    <li>Explore a wide range of unique accommodations tailored to your preferences.</li>
                    <li>Discover hidden gems and local experiences that make your trip unforgettable.</li>
                    <li>Enjoy competitive pricing with no hidden fees for a budget-friendly stay.</li>
                    <li>Experience seamless booking with our user-friendly interface and secure payment options.</li>
                    <li>Receive exceptional customer support from our team, ensuring a hassle-free experience.</li>
                </ul>
            </div>
            <button type="button" name="exploreBtn" id="exploreBtn">Start Exploring</button>
        </section>
        <!-- me javascript anakateuhtynw ton xrhsht sto feed otan pathsei to koympi start exploring -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let exploreBtn = document.getElementById('exploreBtn');
                exploreBtn.addEventListener('click', function() {
                    window.location.href = 'feed.php';
                });
            });
        </script>
    </main>

<?php include("footer.php")?>
</body>
</html>