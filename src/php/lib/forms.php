<?php
// Check data is valid
function checkDataValid($input_data) {
    // Response array
    $r = array(
        'valid' => true, // if the data is valid
        'failed_fields' => array(), // fields that failed validation
        'data' => $input_data // input data
    );

    // Loop each input data to check if valid
    foreach($input_data as $key => $data) {
        // Only validate form data
        if($data['type'] == 'form') {
            // If required is true, and the input is empty
            if($data['required'] == true && empty($data['data'])) {
                // Validation fails
                $r['valid'] = false;
                // Required message
                $msg = ucfirst($key) . ' is required!';
                // Add to failed fields array
                array_push($r['failed_fields'], array($key, $msg));
            }
            // Check if there is a max-length on the field
            if(!is_null($data['max-length'])) {
                // Cast input data to string incase only numbers are entered
                $data_string = (string) $data['data'];
                // Check if length of string is greater than max-length
                if(strlen($data_string) > $data['max-length']) {
                    // If true, then validation fails
                    $r['valid'] = false;
                    // Max length error message
                    $msg = ucfirst($key) . ' must be a maximum of ' . $data['max-length'] . ' characters.';
                    // Add to failed fields array
                    array_push($r['failed_fields'], array($key, $msg));
                }
            }
            // Check if there is a min-length on the field
            if(!is_null($data['min-length'])) {
                //  Cast input data to string incase only numbers are entered
                $data_string = (string) $data['data'];
                // Check if length of string is less than min-length
                if(strlen($data_string) < $data['min-length']) {
                    // Validation fails
                    $r['valid'] = false;
                    // Min-length error message
                    $msg = ucfirst($key) . ' must be a minimum of ' . $data['min-length']  . ' characters.';
                    // Add to failed fields array
                    array_push($r['failed_fields'], array($key, $msg));
                }
            }
        }
    }

    return $r;
}

function exitWithError($checkedData, $page) {
    // Set the form_response session variable to the checkeddata which includes the failed field validation
    $_SESSION['form_response'] = $checkedData;

    // Redirect to given page
    header('Location: ' . $page);
    exit();
}

// Check inputs are set
function checkInputs($field_names) {
    // Loop through field names
    foreach($field_names as $field) {
        // if field name is not set in POST
        if(!isset($_POST[$field])) {
            // redirect to 404
            redirect404();
        }
    }
    return true;
}
