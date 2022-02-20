<?php 

    // Get login form input
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if all fields are filled
    if (empty($email) || empty($password)) {
        header("Location: ./login.html?error=emptyfields");
        exit();
    } else {

        // Get the database credentials
        require_once './credentials.php';

        // Start the PHP_session
        session_start();

        // Create connection
        $con = new mysqli($host, $user, $password, $database);

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

                // Check if an account with the email exists
                if ($stmt->num_rows == 1) {

                    // Bind the result
                    $stmt->bind_result($id, $hashed_password);

                    // Fetch the result
                    $stmt->fetch();

                    // Check if the password matches
                    if (password_verify($password, $hashed_password)) {

                        session_regenerate_id();
                        $_SESSION['loggedin'] = TRUE;
                        $_SESSION['name'] = $_POST['username'];
                        $_SESSION['id'] = $id;

                        // Redirect to the dashboard
                        header("Location: ./dashboard.php");
                        exit();
                    } else {

                        // Show error message
                        header("Location: ./login.html?error=wrongpassword");
                        exit();
                    }
                } else {

                    // Show error message
                    header("Location: ./login.html?error=nouser");
                    exit();
                }
            } else {
                echo "Error: " . $sql . "<br>" . $con->error;
            }
        }
    }

?>