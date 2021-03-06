<?php 

    // Start session
    session_start();

    // Check if user is logged in
    if(!isset($_SESSION['loggedin'])) {
        header("Location: login.html");
    }

    // Get the database credentials
    require_once './credentials.php';

    // Get user id
    $id = $_SESSION['id'];

    // Create connection
    $con = new mysqli($db_host, $db_user, $db_pass, $db_db);

    // Check connection
    if ($con->connect_error){
        die("SQL connection failed: " . $con->connect_error);
    } else {

        // Prepare the SQL to get users name and mail
        if ($stmt = $con->prepare('SELECT username, email FROM accounts WHERE id = ?')) {

            // Bind user id param
            $stmt->bind_param('i', $id);

            // Execute the SQL
            $stmt->execute();

            // Get the result
            $stmt->store_result();

            // Bind the result
            $stmt->bind_result($name, $email);

            // Fetch the result
            $stmt->fetch();

            // Close the SQL
            $stmt->close();
        }
    }
?>


<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link rel="stylesheet" href="dashboard_style.css">
    <script src="https://kit.fontawesome.com/b5c383da68.js" crossorigin="anonymous"></script>
</head>

<body>
    <div class="container">
        <div id="name" class="user">
            <div class="icon">
                <img src="https://upload.wikimedia.org/wikipedia/commons/9/99/Sample_User_Icon.png?20200919003010">
            </div>
            <div id="name_field" class="name">
                <?= $name ?>
            </div>
            <div id="email_field" class="email">
                <?= $email ?>
            </div>
        </div>
        <div class="tabs">
            <ul>
                <a href="#orders">
                    <li>
                        <i class="fa-solid fa-cart-shopping"></i> Orders
                    </li>
                </a>
                <a href="#account">
                    <li>
                        <i class="fa-solid fa-user-gear"></i> Account
                    </li>
                </a>
                <a href="#links">
                    <li>
                        <i class="fa-solid fa-link"></i> Links
                    </li>
                </a>
            </ul>

        </div>
        <div class="settings">
            <div id="orders">
                <h1>Orders</h1>
                You have no open orders.
            </div>
            <div id="account">
                <h1>Account</h1>
                <a href="#">Change Username</a><br>
                <a href="#">Change Password</a><br>
                <a href="#">Change Email</a><br>
            </div>
            <div id="links">
                <h1>Links</h1>
                <h3>Discord:</h3>
                <a href="https://discord.gg/R2Sn4ZzZeY" target="_blank"><img src="https://discord.com/api/guilds/883376676237635644/widget.png?style=banner3"></a>
                <a href="https://discord.gg/sy8mTCw4ns" target="_blank"><img src="https://discord.com/api/guilds/798309889394212884/widget.png?style=banner3"></a>

            </div>
        </div>
    </div>

    <script>
        user_element = document.getElementById("name");
        name_element = document.getElementById("name_field");

        // Add event listener hover
        user_element.addEventListener("mouseover", function() {

            // Change the text of the element
            name_element.innerHTML = "<span style='text-decoration: underline;'>Logout</span>";

        });

        user_element.addEventListener("mouseout", function() {

            // Change the text of the element
            name_element.innerHTML = '<?PHP echo $name; ?>';
        });

        name_element.addEventListener("click", function() {

            // Change the text of the element
            window.location.href = 'logout.php';

        });
    </script>
    
</body>

</html>