<?php
include_once "utility.crud.php";

function delte_comment($conn,$id){
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

?>