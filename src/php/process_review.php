<?php
// Required php files
require('../php/lib/db.php');
require('../php/lib/auth.php');
require('../php/lib/helpers.php');

// Check user is logged in
checkUserLoggedIn();
// Check user is admin
isAdmin($pdo);

// Check required POSt is set
if(!isset($_POST['id']) || !isset($_POST['type'])) {
    // If not, 404
    redirect404();
}

// Set POST data to variables
$id = $_POST['id'];
$type = $_POST['type'];

// If variables not empty
if(!empty($id) && !empty($type)) {
    $response = array();
    // If type = 2 (delete review) then
    if($type == 2){
        // SQl to delete review from reviews table
        $sql = 'DELETE FROM reviews WHERE ID = :id';
        // Input data for review query
        $input_data = array(
            'id' => array(
                'data' => $id
            )
        );
        // Process data
        if(processData($pdo, $input_data, $sql)){
            // If sucess, return status as 1 = sucess
            $response = array(
                'status' => 1,
                'msg' => 'Successfully deleted review.'
            );
        } else {
            // If fails, return status as 0 = failed
            $response = array(
                'status' => 0,
                'msg' => 'Unable to delete review'
            );
        }
    } else {
        // If type = 1 (approve review) then update pending column to 0
        // SQL to upate reviews table
        $sql = 'UPDATE reviews SET pending = :pending WHERE ID = :id';
        // Input data for sql
        $input_data = array(
            'pending' => array(
                'data' => 0
            ),
            'id' => array(
                'data' => $id
            )
        );
        // Process data
        if(processData($pdo, $input_data, $sql)) {
            // If sucess status 1 = sucess
            $response = array(
                'status' => 1,
                'msg' => 'Succesfully approved review.'
            );
        } else {
            // If fails, status 0 = failed
            $response = array(
                'status' => 0,
                'msg' => 'Unable to approve review.'
            );
        }
    }

    // Add the review ID to the response
    $response['reviewID'] = $id;
    // JSON encode response
    $responseJSON = json_encode($response);
    echo $responseJSON;
} else {
    $response = array(
        'status' => 0,
        'msg' => 'Error with review'
    );
    $responseJSON = json_encode($response);
    echo $responseJSON;
}
?>
