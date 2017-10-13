<?php
require('dbinfo.php');

$db_info = returnInfo();

$conn = "mysql:host=" . $db_info['servername'] . ";dbname=" . $db_info['dbname'] . ";charset=" . $db_info['charset'];

// try connectng
try {
    $pdo = new PDO($conn, $db_info['username'], $db_info['password']);
} catch (PDOException $e) {
    exit('Connection failed: ' . $e->getMessage());
}

// Function to update and insert data
// $pdo = the db variables
// $input_data = $data to be binded and used in query
// $sql = sql query
function processData($pdo, $input_data, $sql) {
    $stmt = $pdo->prepare($sql);
    if(!$stmt) {
        databaseError(0);
    }

    foreach($input_data as $key => $data) {
        $stmt->bindParam(':' . $key, $data['data']);
    }

    try {
        $stmt->execute();
    } catch (PDOException $e) {
        databaseError(1, $e->getMessage());
    }
    return true;
}

// Function to fetchData
// $pdo = the db variables
// $input_data = $data to be binded and used in query
// $sql = sql query
// $single = whether to return only one row
function fetchData($pdo, $input_data, $sql, $single = false) {
    $stmt = $pdo->prepare($sql);
    if(!$stmt) {
        databaseError(0);
    }

    foreach($input_data as $key => $data) {
        $stmt->bindParam(':' . $key, $data['data']);
    }

    try {
        $stmt->execute();
    } catch (PDOException $e) {
        databaseError(1, $e->getMessage());
    }

    if($single) {
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    return $result;
}

// Check if the summited data already exists
// $pdo = the db variables
// $input_data = $data to be binded and used in query
// $sql = sql query
// $single = whether to return only one row
function checkExists($pdo, $input_data, $sql) {
    $stmt = $pdo->prepare($sql);
    if(!$stmt) {
        databaseError(0);
    }

    foreach($input_data as $key => $data) {
        $stmt->bindParam(':' . $key, $data['data']);
    }

    try{
        $stmt->execute();
    } catch(PDOException $e) {
        databaseError(1, $e->getMessage());
    }

    if($stmt->rowCount() > 0) {
        return true;
    } else {
        return false;
    }
}

// Exit if database receives an error
function databaseError($id, $info = null) {
    if($id == 0) {
        echo 'There is an error with database query, this will be fixed as soon as possible. <a href="/index.php">Return home</a>';
        exit();
    } else if($id == 1) {
        echo 'There is an error with the database, this will be fixed as soon as possible. <a href="/index.php">Return home</a>';
        exit();
    }
}
