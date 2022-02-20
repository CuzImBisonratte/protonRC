<?php 

    // Get login form input
    $email = $_POST['email'];
    $password = $_POST['password'];
    $db_username = $_POST['username'];

    // Check if all fields are filled
    if (empty($email) || empty($password) || empty($db_username)) {
        header("Location: ./register.html?error=emptyfields");
        exit();
    } else {

        // Get the database credentials
        require_once './credentials.php';

        // Start the PHP_session
        session_start();

        // Create connection
        $con = new mysqli($db_host, $db_user, $db_pass, $db_db);

        // Check connection
        if ($con->connect_error) {

            // Show error message
            die("Connection failed: " . $con->connect_error);
        } else {

            // Prepare the SQL
            if ($stmt = $con->prepare('SELECT id, password FROM accounts WHERE email = ?')) {

                // Bind email param
                $stmt->bind_param('s', $email);

                // Execute the SQL
                $stmt->execute();

                // Get the result
                $stmt->store_result();

                // Hash the password
                $password = password_hash($password, PASSWORD_DEFAULT);

                // Check if an account with the email exists
                if ($stmt->num_rows == 1) {
                    
                    // Show error message
                    header("Location: ./login.html?error=alreadyexists");
                    exit();
                } else {

                    // Prepare the SQL
                    if ($stmt = $con->prepare('INSERT INTO accounts (email, password, username) VALUES (?, ?, ?)')) {

                        // Bind email param
                        $stmt->bind_param('sss', $email, $password, $db_username);

                        // Execute the SQL
                        $stmt->execute();

                        // Check if the account was created
                        if ($stmt->affected_rows == 1) {

                            // Redirect to the dashboard
                            header("Location: ./login.html");
                            exit();
                        } else {

                            // Show error message
                            header("Location: ./login.html?error=sqlerror");
                            exit();
                        }
                    }
                }

            }
        }
    }

?>