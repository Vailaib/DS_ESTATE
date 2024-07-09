<?php   session_start(); 
        session_destroy(); //kanw destroy to session wste na ginei logout
        //sthn sunexeia ton kanw redirect sto index.php enw pleon den einai sundedemons
        echo '<script>
                    setTimeout(function() {
                        window.location.href = "index.php";
                    }, 0);
                </script>';
        exit();
?>
