<?php
include_once "utility.crud.php";

function delete_comment($conn,$id){
    $sql = "DELETE FROM `Comment` WHERE id = $id"; 
    
    global $debeug;
    if ($debeug) echo $sql . "<br>"; 
    
    $res = mysqli_query($conn, $sql); 
    return $res;

}

function select_comments_by_activity_id($conn, $activityId) {
    // Join pour avoir le message du commentaire + le prenom/nom de l'etudiant
    $sql = "SELECT C.*, L.firstName, L.lastName
            FROM `Comment` C
            JOIN `Learner` L ON C.learnerId = L.id
            WHERE C.activityId = $activityId";

    global $debeug;
    if ($debeug) echo $sql . "<br>";

    $res = mysqli_query($conn, $sql);
    return rs_to_tab($res);
}

function add_comment($conn, $learnerId, $activityId, $message){
    $sql = "INSERT INTO `Comment`(`date`, `message`, `learnerId`, `activityId`) VALUES (CURRENT_TIME,'$message',$learnerId,$activityId)";

    global $debeug;
    if ($debeug) echo $sql . "<br>";

    return mysqli_query($conn,$sql);
}
?>