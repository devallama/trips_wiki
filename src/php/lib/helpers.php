<?php
// Output text into html by using htmlspecialchars
function output($str) {
    // Use htmlspecialchars so user submitted data is safe to be outputted
    $outputStr = htmlspecialchars($str, ENT_QUOTES);
    // Array of the <p></p> tags in HTML entities
    $from = array('&lt;p&gt;', '&lt;/p&gt;');
    // The <p></p> Tags
    $to = array('<p>', '</p>');
    // Replace the html entities with the p tags to allow for paragrams in outputted text
    $outputStr = str_replace($from, $to, $outputStr);
    echo $outputStr;
}

// Rediret user to 404 error page
function redirect404() {
    header('HTTP/1.1 404 Not Found');
    exit();
}

// Redirect to the users last visited page
function redirectLastPage() {
    header('Location: ' . $_SESSION['last_page']);
    exit();
}

// Sets the users current page, used when redirecting
function newPage() {
    $uri = $_SERVER['REQUEST_URI'];
    $query = $_SERVER['QUERY_STRING'];
    $_SESSION['last_page'] = $uri . $query;
}
