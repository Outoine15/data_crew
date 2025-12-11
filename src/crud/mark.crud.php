<?php 
include_once "utility.crud.php";

function select_marks_by_learner_id($conn,$learnerId) {
    $sql = "SELECT * FROM `Mark` WHERE learnerId = $learnerId"; 
    
    global $debeug;
    if ($debeug) echo $sql . "<br>"; 
    
    $res = mysqli_query($conn, $sql); 
    $tab = rs_to_tab($res);
    
    return $tab;

}

function set_activity_mark($conn, $activityId, $learnerId, $mark) {
    $checkSql = "SELECT * FROM `Mark` WHERE activityId = $activityId AND learnerId = $learnerId";
    $checkRes = mysqli_query($conn, $checkSql);

    if (mysqli_num_rows($checkRes) > 0) {
        $sql = "UPDATE `Mark` SET mark = $mark WHERE activityId = $activityId AND learnerId = $learnerId";
    } else {
        
        $sql = "INSERT INTO `Mark` (`activityId`, `learnerId`, `mark`) VALUES ($activityId, $learnerId, $mark)";
    }

    global $debeug;
    if ($debeug) echo $sql . "<br>";

    
    $res = mysqli_query($conn, $sql);
    return $res;
}

?>