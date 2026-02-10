<?php
    $db_server='localhost';
    $db_user='root';
    $db_name='farmers_market';
    $db_pass='';
    $db_port=3307;
    $conn='';

    try {
        $conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name,$db_port);
        
        
    } catch (Exception $e) {
        echo 'Could not connect! <br>';
        
    };
    

?>