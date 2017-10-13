<?php
// Required PHP files
require('../php/lib/db.php');
require('../php/lib/helpers.php');

// Check if required GET is set
if(!isset($_GET['search_term'])) {
    // If not, 404
    redirect404();
}


$search_term = '';
// If submitted search_term is not empty
if(!empty($_GET['search_term'])) {
    // Set to $search_term
    $search_term = $_GET['search_term'];
}

$response = array();
// SQL to get all places where the search term is similar to either the type, name, region or country
$sql = 'SELECT * FROM places WHERE (name LIKE :search_term OR type LIKE :search_term2 OR region LIKE :search_term3 OR country LIKE :search_term4)';
// Input data for SQL, using % at the start and end of the data to be used as wildcards.
$input_data = array(
    'search_term' => array(
        'data' => '%' . $search_term . '%'
    ),
    'search_term2' => array(
        'data' => '%' . $search_term . '%'
    ),
    'search_term3' => array(
        'data' => '%' . $search_term . '%'
    ),
    'search_term4' => array(
        'data' => '%' . $search_term . '%'
    )
);

// Fetch the data
$result = fetchData($pdo, $input_data, $sql);

// If data was returned
if(!empty($result)) {
    // Repond with status sucess = 1 and the data
    $response = array(
        'status' => 1,
        'data' => $result
    );
} else {
    // Otherwise respond with failed status and message
    $response = array(
        'status' => 0,
        'msg' => 'No search results.'
    );
}

// JSON encode response
$responseJSON = json_encode($response);
echo $responseJSON;
