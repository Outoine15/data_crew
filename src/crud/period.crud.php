<?php 
include_once "utility.crud.php";

function select_period_by_name($conn, $name) {
    // $sql = 'SELECT * FROM `Period` WHERE `name` = $name';
    $sql = "SELECT * FROM `Period` WHERE `name` = '$name'";
    
    global $debeug;
    if ($debeug) echo $sql . "<br>"; 
    
    $res = mysqli_query($conn, $sql); 
    $tab = rs_to_tab($res);
    
    return $tab[0];
}

?>