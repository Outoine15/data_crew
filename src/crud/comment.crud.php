<?php
include_once "utility.crud.php";

function delte_comment($conn,$id){
    $sql = "DELETE FROM `Comment` WHERE id = $id"; 
    
    global $debeug;
    if ($debeug) echo $sql . "<br>"; 
    
    $res = mysqli_query($conn, $sql); 
    return $res;

}

?>