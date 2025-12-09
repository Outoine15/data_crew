<?php
include_once "bdd.php";
include_once "utility.php";
foreach (glob("crud/*.crud.php") as $filename) {
    require_once $filename;
}
foreach (glob("REST/*.rest.php") as $filename) {
    require_once $filename;
}

$conn = db();

get("/learners/:learnerId", function ($param) {
    $learner_id = $param['learnerId'];
    echo $learner_id;
    exit;

});


put("/learners/:learnerId", function ($param) {
    exit;
});

post("/learners",function(){
    global $conn;
    $user_pwd = $_POST['password'];
    $user_mail= $_POST['mail'];
    $res=connect_learner($conn,$user_mail,$user_pwd);
    $id=$res["id"];
    $firstName=$res["firstName"];
    $lastName=$res["lastName"];
    $email=$res["email"];
    $pwd=$res["password"];
    $stateId=$res["stateId"];
    $teamId=$res["teamId"];

    return $id;
});

get("/states",function(){
    global $conn;
   
    return list_states($conn);

});

header("HTTP/1.0 404");
