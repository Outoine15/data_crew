<?php
include_once "utility.crud.php";

function select_role_by_trainer_id($conn,$trainerId){
    $sql = "SELECT * FROM `Role` WHERE trainerId=$trainerId";

    global $debeug;
    if ($debeug) echo $sql . "<br>"; 
    $res = mysqli_query($conn, $sql); 
    $tab = rs_to_tab($res);
    return $tab;
}


?>