<?php
// Start PHP sessions
session_start();

// Check user is logged in, if not redirect to home page.
function checkUserLoggedIn() {
    if(!isset($_SESSION['user'])) {
        $_SESSION['msg'] = 'You must be logged in to view this page.';
        header('Location: ./index.php');
        exit();
    }
}

// Remove old sessions that are only required for a short period of time
function clearOldSessions() {
    unset($_SESSION['msg']);
    unset($_SESSION['auth_response']);
    unset($_SESSION['error']);
    unset($_SESSION['form_response']);
}

// Fetch the users id from their user session
function getUserId() {
    return $_SESSION['user']['id'];
}

// Get the name of a user from their ID
function getUserName($pdo, $id) {
    $sql = 'SELECT username FROM users WHERE id = :id';
    $input_data = array(
        'id' => array(
            'data' => $id
        )
    );
    $result = fetchData($pdo, $input_data, $sql, true);
    return $result['username'];
}

// Check if the user is logged in, but only return true or false, does not redirect
function isLoggedIn() {
    if(isset($_SESSION['user'])) {
        return true;
    } else {
        return false;
    }
}

// Check if user is an administrator, redirects them to home if not administrator
function isAdmin($pdo) {
    $id = $_SESSION['user']['id'];
    $sql = 'SELECT isadmin FROM users WHERE id = :id';
    $input_data = array(
        'id' => array(
            'data' => $id
        )
    );
    $result = fetchData($pdo, $input_data, $sql, true);

    if($result['isadmin'] == 1) {
        return true;
    } else {
        $_SESSION['msg'] = 'You must be an administrator to view this page.';
        header('Location: ./index.php');
        exit();
    }
}

// Log out the user by unsetting the user session
function logoutUser() {
    unset($_SESSION['user']);
}
