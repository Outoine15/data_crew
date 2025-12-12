<?php 
include_once "utility.crud.php";

function select_session_by_activity_id_and_id($conn,$id,$activityId){
    $sql = "SELECT * FROM `Session` WHERE id = $id AND activityId=$activityId"; 
    
    global $debeug;
    if ($debeug) echo $sql . "<br>"; 
    
    $res = mysqli_query($conn, $sql); 
    return $res;
}

function select_sessions_by_activity_id($conn, $activityId) {
    $sql = "SELECT * FROM `Session` WHERE activityId = $activityId";
    
    global $debeug;
    if ($debeug) echo $sql . "<br>";
    
    $res = mysqli_query($conn, $sql);
    return rs_to_tab($res);
}

?>