<?php
// Required php
require('../php/lib/db.php');
require('../php/lib/auth.php');
require('../php/lib/forms.php');
require('../php/lib/helpers.php');

// Check user is logged in
checkUserLoggedIn();

// Field names of the form
$field_names = array('review', 'placeid');

// Check required input
if(!checkInputs($field_names)) {
    redirect404();
} else {
    init($pdo);
}

// Init php
function init($pdo) {
    $response = array();
    // Check place the user is reviewing exists
    if(!checkPlaceExists($pdo, $_POST['placeid'])) {
        // If not, return with failed status and error message.
        $response = array(
            'status' => 0,
            'msg' => 'The place you are reviewing does not exist'
        );
    } else {
        // If it does then create form data
        $form_data = array(
            'review' => array(
                'data' => $_POST['review'],
                'type' => 'form',
                'required' => true,
                'max-length' => 500,
                'min-length' => 5
            ),
            'placeid' => array(
                'data' => $_POST['placeid'],
                'type' => 'form',
                'required' => true,
                'max-length' => null,
                'min-length' => null
            ),
            'userid' => array(
                'data' => getUserId(),
                'type' => 'server'
            )
        );

        // Valid form data
        $checkedData = checkDataValid($form_data);

        // If data valid
        if($checkedData['valid']) {
            // Insert data into review table
            $sql = 'INSERT INTO reviews (review, placeID, userID) VALUES (:review, :placeid, :userid)';
            if(processData($pdo, $form_data, $sql)) {
                // Give response with success status and message
                $response = array(
                    'status' => 1,
                    'msg' => "Your review has been submitted! It is now awaiting approval."
                );
            }
        } else {
            // If failed, set form_response to failed data to pass back to form
            $_SESSION['form_response'] = $checkedData;
            // Fail with failed status 2, and the invalid data
            $response = array(
                'status' => 2,
                'data' => $checkedData
            );
        }
    }

    // Encode response to json
    $responseJSON = json_encode($response);
    echo $responseJSON;
}


// Check place exists
function checkPlaceExists($pdo, $place) {
    // SQL to select place by id
    $sql = 'SELECT * FROM places WHERE ID = :place';
    // Input data to select place
    $input = array(
        'place' => array(
            'data' => $place
        )
    );

    // Returns true if place exists, false if does not
    return checkExists($pdo, $input, $sql);
}
