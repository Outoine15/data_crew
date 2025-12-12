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

function select_sessions_by_trainer_id($conn, $trainerId){
    $sql = "SELECT * FROM `Session` WHERE trainerId=$trainerId";

    global $debeug;
    if ($debeug) echo $sql . "<br>";
    
    $res = mysqli_query($conn, $sql);
    return rs_to_tab($res);
}

function abo_session($conn, $sessionId, $teamId) {
    $sql = "INSERT INTO `TeamSession` (`sessionId`, `teamId`) VALUES ($sessionId, $teamId)";
   
    global $debeug;
    if ($debeug) echo $sql . "<br>";
    return mysqli_query($conn, $sql);
}

function desabo_session($conn, $sessionId, $teamId) {
    $sql = "DELETE FROM `TeamSession` WHERE sessionId = $sessionId AND teamId = $teamId";
   
    global $debeug;
    if ($debeug) echo $sql . "<br>";
   
    return mysqli_query($conn, $sql);
}
?>