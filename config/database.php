<?php

try {
    //connect to database
    $connect = mysqli_connect("localhost", "easy", "@Khadijah24", "library_management_system", 3306);

    //check the connection
    if (!$connect) {
        throw new Exception("Connection error: " . mysqli_connect_error());
    }

    //if connection is succesful
//     echo
//     '<div style="color:green; padding: 10px; border:1px solid green; border-radius:5px; margin:10px; background-color: #e8f5e9;">
// <strong>✔ Success!</strong> Successfully connected to the database.</div>';
    error_log(message: 'Database connection established succesfully');
} catch (mysqli_sql_exception $e) {
    //handle MySQL specific errors

    error_log(message: 'MySQL Error: ' . $e->getMessage());
    echo '<div style="color:red; padding: 10px; border:1px solid green; border-radius:5px; margin:10px; background-color: #e8f5e9;">
<strong>× Error!</strong> ' . $e->getMessage() . '
</div>';
    exit();
} catch (Exception $e) {
    //Handle other types of errors

    error_log(message: 'General Error: ' . $e->getMessage());
    echo '<div style="color:red; padding: 10px; border:1px solid green; border-radius:5px; margin:10px; background-color: #e8f5e9;">
<strong> Error!</strong> ' . $e->getMessage() . '
</div>';
    exit();
} finally {
    //this block will always execute

    if (isset($connect) && $connect) {
        //mysqli_close($connect);
    }
}
