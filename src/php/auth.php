<?php
// Require DataBase file
require('../php/lib/db.php');
// Require helpers file
require('../php/lib/helpers.php');

// Check post is set otherwise exit
if(!isset($_POST['auth_type'])) {
    redirect404();
}
else {
    session_start();
}

// Check type of auth
// If register then
if($_POST['auth_type'] == 'register') {
    // Check required register fields have been submitted
    if(!checkInputsRegister()) {
        // Set username to blank
        $username = '';
        // Check if they have submitted at least a username, if not, keeep it blank
        if(checkUsernameInput()) {
            $username = $_POST['username'];
        }
        // Register data to process
        $reg_data = array(
            'username' => $username
        );
        // Call process of 1 - inputs empty
        process(1, $reg_data, 0);
    }

    // If the required data has been submitted, save it to an array to use.
    $reg_data = array(
        'username' => $_POST['username'],
        'password' => $_POST['password'],
        'repeat_password' => $_POST['repeat_password']
    );

    // Check if the first password matches the second
    if(!checkPasswordsMatch($reg_data['password'], $reg_data['repeat_password'])) {
        // Call process with status of 2 if fails.
        process(2, $reg_data, 0);
    }

    // Check if the username is available
    if(usernameFree($reg_data['username'], $pdo)) {
        // Call process with a status of 3 if it fails
        process(3, $reg_data, 0);
    }

    // Attempt to register the user
    if(!tryRegister($reg_data, $pdo)) {
        // If succeeds, call process with status 4
        process(4, $reg_data, 0);
    } else {
        // If fails, call process with status 0
        process(0, $reg_data, 0);
    }

// If user is requesting to login then
} else if($_POST['auth_type'] == 'login') {
    // Check required inputs
    if(!checkInputsLogin()) {
        $username = '';
        if(checkUsernameInput()) {
            $username = $_POST['username'];
        }
        $reg_data = array(
            'username' => $username
        );
        process(1, $reg_data, 1);
    }

    // Save data to an array
    $reg_data = array(
        'username' => $_POST['username'],
        'password' => $_POST['password']
    );

    // Check the login details of the user is correct
    $userData = checkLoginDetails($reg_data, $pdo);

    // Check if the login details were correct, if not, call process with status 5
    if(!$userData) {
        process(5, $reg_data, 1);
    }

    // If the details were correct, create the user session
    createSession($userData);

    // Call process with status 0
    process(0, $reg_data, 1);
}

// Check the required inputs have been submitted for register
function checkInputsRegister() {
    // Register requires username, password and repeat_password fields
    if(!isset($_POST['username']) || !isset($_POST['password']) || !isset($_POST['repeat_password'])) {
        // If one of them is not set, it fails
        return false;
    } else if(empty($_POST['username']) || empty($_POST['password']) || empty($_POST['repeat_password'])) {
        // If one is empty, it fails
        return false;
    }
    // Otherwise inputs are correct.
    return true;
}

// Check required inputs have been submitted for login
function checkInputsLogin() {
    // Login requires username and password
    if(!isset($_POST['username']) || !isset($_POST['password'])) {
        // Fail if one is not set
        return false;
    } else if(empty($_POST['username']) || empty($_POST['password'])) {
        // Fail is one is empty
        return false;
    }
    // Otherwise inputs are correct
    return true;
}

// Check if username has been submitted
function checkUsernameInput() {
    // Check if username has been set
    if(isset($_POST['username'])) {
        // Check if it is not empty
        if(!empty($_POST['username'])) {
            // If so return true and pass
            return true;
        }
    }

    // Otherwise return false and fail
    return false;
}

// Check passwords match
function checkPasswordsMatch($p1, $p2) {
    // Check password 1 is equals to password 2
    if($p1 == $p2) {
        return true;
    } else {
        return false;
    }
}

// Check that the username isn't taken
function usernameFree($username, $pdo) {
    // SQL query to get any users with the submitted username
    $sql = 'SELECT id FROM users WHERE username = :username';
    // Array of data to process
    $input = array(
        'username' => array(
            'data' => $username
        )
    );
    // Return the result, which will either be true if it exists, false if not.
    return checkExists($pdo, $input, $sql);
}

// Attempt to register the user
function tryRegister($reg_data, $pdo) {
    // Create a secure password hash
    $passwordHash = createPasswordHash($reg_data['password']);

    // SQL to insert user data
    $sql = 'INSERT INTO users (username, password) VALUES (:username, :password)';
    // Input data for the query
    $input_data = array(
        'username' => array(
            'data' => $reg_data['username']
        ),
        'password' => array(
            'data' => $passwordHash
        )
    );
    // Run the database query
    return processData($pdo, $input_data, $sql);
}

// WAS creating a hash, but it seems you guys aren't running 5.5
function createPasswordHash($password) {
    // Creates a secure password hash using BCRYPT
    // $hash = password_hash($password, PASSWORD_BCRYPT);
    // return $hash;
    return $password;
}

// Check login details of user
function checkLoginDetails($data, $pdo) {
    // SQL query to get the users data by username
    $sql = 'SELECT * FROM users WHERE username = :username';
    // Input data for the query
    $input_data = array(
        'username' => array(
            'data' => $data['username']
        )
    );

    // Fetch the line of data from the database
    $result = fetchData($pdo, $input_data, $sql, true);

    // If empty, username does not exist so fail
    if(empty($result)) {
        return false;
    } else {
        // Check if passwords match
        // if(password_verify($data['password'], $result['password'])) {
        if($data['password'] == $result['password']) {
            // If they do, return users data
            return $result;
        } else {
            // Otherwise return false
            return false;
        }
    }
}

// Create a user session for logged in user
function createSession($data) {
    // User data, with their ID and whether they are admin
    $userData = array(
        'id' => $data['id'],
        'admin' => $data['isadmin']
    );

    // Session to store the user data
    $_SESSION['user'] = $userData;
}

// Process results
function process($id, $data, $type) {
    $response;
    switch($id) {
        // Register or login was sucess
        case 0:
            if($type == 0) {
                $_SESSION['msg'] = 'You have successfully registered! You can now log in.';
                header('Location: ../login.php');
                exit();
            } else {
                $_SESSION['msg'] = 'Successfully logged in!';
                header('Location: ../index.php');
                exit();
            }
            break;
        // Inputs empty
        case 1:
            $response = 'Please fill in the required fields.';
            break;
        // Passwords do not match
        case 2:
            $response = 'Passwords do not match';
            break;
        // Username taken
        case 3:
            $response = 'Username has already been taken';
            break;
        // Error registering
        case 4:
            $response = 'There was an error registering, please try again later';
            break;
        // Incorrect login details
        case 5:
            $response = 'Incorrect login details';
            break;
        default:
            $response = 'Unknown error';
            break;
    }

    // Response with status message, username of user and the type of auth they were attempting
    $_SESSION['auth_response'] = array(
        'response' => $response,
        'username' => $data['username'],
        'auth_type' => $type
    );

    // Return to login page
    header('Location: ../login.php');
    exit();
}
