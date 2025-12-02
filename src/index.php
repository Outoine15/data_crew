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

});


put("/learners/:learnerId", function () {
    $_PUT = read_put();
    $user_pwd = $_PUT['password'];
});

post("/learners",function(){
    echo "OK";
    exit;
});

get("/states",function(){
    global $conn;
   
    return list_states($conn);

});





// example working? (not tested)
// get("/learners", function($param){
//     $mail=$param['mail'];
//     $password=$param['password'];
// });

header("HTTP/1.0 404");


// <?php
// include_once "bdd.php";
// include_once "utility.php";
// foreach (glob("crud/*.crud.php") as $filename) {
//     require_once $filename;
// }
// foreach (glob("REST/*.rest.php") as $filename) {
//     require_once $filename;
// }


// get("/learners/:learnerId", function ($param) {
//     $learner_id = $param['learnerId'];
// });

// put("/learners/:learnerId", function () {
//     $_PUT = read_put();
//     $user_pwd = $_PUT['password'];
// });

// post("/learners",function(){
//     echo "OK";
//     exit;
// });

// header("HTTP/1.0 404");

