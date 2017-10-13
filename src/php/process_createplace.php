<?php
// Required php
require('../php/lib/db.php');
require('../php/lib/auth.php');
require('../php/lib/forms.php');
require('../php/lib/helpers.php');

// Check user is logged in to create the place.
checkUserLoggedIn();

// Field names of the form
$field_names = array('name', 'type', 'description', 'country', 'region');

// Check required field names were submmited, otherwise 404.
if(!checkInputs($field_names)) {
    redirect404();
} else {
    // If pass, call init
    init($pdo);
}

function init($pdo) {
    // Form data with the form name, data, type and validation
    // Type can be either form or server, form data needs to be validated, while server data does not.
    $form_data = array(
        'name' => array(
            'data' => $_POST['name'],
            'type' => 'form',
            'required' => true,
            'max-length' => 255,
            'min-length' => 3
        ),
        'type' => array(
            'data' => $_POST['type'],
            'type' => 'form',
            'required' => true,
            'max-length' => 255,
            'min-length' => 2
        ),
        'description' => array(
            'data' => $_POST['description'],
            'type' => 'form',
            'required' => true,
            'max-length' => 1500,
            'min-length' => 50
        ),
        'country' => array(
            'data' => $_POST['country'],
            'type' => 'form',
            'required' => true,
            'max-length' => 255,
            'min-length' => 2
        ),
        'region' => array(
            'data' => $_POST['region'],
            'type' => 'form',
            'required' => false,
            'max-length' => 255,
            'min-length' => 0
        ),
        'userid' => array(
            'data' => getUserId(),
            'type' => 'server'
        )
    );

    // Validate form data
    $checkedData = checkDataValid($form_data);

    // If form data was valid then
    if($checkedData['valid']) {
        // Insert the place details into the database
        $sql = 'INSERT INTO places (name, type, description, country, region, userID) VALUES (:name, :type, :description, :country, :region, :userid)';
        if(processData($pdo, $form_data, $sql)) {
            // Message to inform user
            $_SESSION['msg'] = 'Successfully submitted!';
            // Redirect to the last page
            redirectLastPage();
            exit();
        }
    } else {
        // If validation fails, exit with an error message
        exitWithError($checkedData, $_SESSION['last_page']);
    }
}
