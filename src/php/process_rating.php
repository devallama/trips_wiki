<?php
// Required php files
require('../php/lib/db.php');
require('../php/lib/auth.php');
require('../php/lib/helpers.php');

// Check required fields are set
if(!isset($_GET['placeid']) || !isset($_GET['rating'])) {
    // if not, 404
    redirect404();
}

// Put GET data into variables
$id = $_GET['placeid'];
$rating = $_GET['rating'];

// If not empty
if(!empty($id) && !empty($rating)) {
    $response = array();
    // SQL to get avg_rating and num_raters from database
    $sql = 'SELECT avg_rating, num_raters FROM places WHERE ID = :id';
    // Input data for SQL
    $input_data = array(
        'id' => array(
            'data' => $id
        )
    );
    // Fetch result
    $result = fetchData($pdo, $input_data, $sql, true);

    if(!empty($result)) {
        // Times the avg_rating and num_raters to get the overall rating
        $total_average_rating = $result['avg_rating'] * $result['num_raters'];
        // Add the users rating to the overall rating
        $new_total_average_rating = $total_average_rating + $rating;
        // Increase the num_raters by 1
        $new_num_raters = $result['num_raters'] + 1;
        // Get the new average rating by dividing the overall rating by number of new raters
        $new_average_rating = $new_total_average_rating / $new_num_raters;
        // Round the average to two decimal places
        $average_rating = round($new_average_rating, 2);

        // SQL to update places table with new avg_rating and num_raters
        $sql = 'UPDATE places SET avg_rating = :avg_rating, num_raters = :num_raters WHERE ID = :id';
        // input data for the SQl query
        $input_data = array(
            'avg_rating' => array(
                'data' => $average_rating
            ),
            'num_raters' => array(
                'data' => $new_num_raters
            ),
            'id' => array(
                'data' => $id
            )
        );
        // Process the update query
        if(processData($pdo, $input_data, $sql)) {
            // If passes, respond with status 1 = sucess, along with success message, users rating, new avg_rating and new num_raters
            $response = array(
                'status' => 1,
                'msg' => 'Succesfully updated rating',
                'user_rating' => $rating,
                'avg_rating' => $average_rating,
                'num_raters' => $new_num_raters
            );
        };
    } else {
        // If place does nto exist, set status to 0 = fail with error message
        $response = array(
            'status' => 0,
            'msg' => 'Place does not exists.'
        );
    }

    // Encode response with JSON
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
