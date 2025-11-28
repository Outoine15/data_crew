<?php
include_once "bdd.php";
include_once "utility.php";
include_once "crud/*.php";
include_once "api/*.php";

get("/learners/:learnerId", function ($param) {
    $learner_id = $param['learnerId'];
});

put("/learners/:learnerId", function () {
    $_PUT = read_put();
    $user_pwd = $_PUT['password'];
});

// example working? (not tested)
// get("/learners", function($param){
//     $mail=$param['mail'];
//     $password=$param['password'];
// });

header("HTTP/1.0 404");

