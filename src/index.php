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

    $resState=select_states($conn,$stateId);
    $resSkill=select_skills_by_learner_id($conn,$id);
    $resMark = select_marks_by_learner_id($conn,$id);

    $stateId=$resState["id"];
    $stateTitle=$resState["title"];
    $stateColor=$resState["color"];
    $stateIcon=$resState["icon"];
    
    $return = [
        "id" => $id,
        "firstName"=>$firstName,
        "lastName"=>$lastName,
        "email"=>$email,
        "team"=>$teamId,
        "state"=>[
            "id"=>$stateId,
            "title"=>$stateTitle,
            "color"=>$stateColor,
            "icon"=>$stateIcon,
        ],
        "skills"=>[],
        "marks"=>[]
    ];

    $skillArray=[];
    for ($i=0; $i < count($resSkill); $i++) { 

        $name=$resSkill[$i]["name"];
        $level=$resSkill[$i]["level"];
        $color=$resSkill[$i]["color"];
        $icon=$resSkill[$i]["icon"];

        $curentSkill=[
            "name"=>$name,
            "level"=>$level,
            "color"=>$color,
            "icon"=>$icon
        ];

        array_push($skillArray,json_encode($curentSkill));
    }
    $return['skills'] = $skillArray;

    $markArray=[];
    for ($i=0; $i < count($resMark); $i++) {
        $activityId= $resMark[$i]["activityId"];
        $mark= $resMark[$i]["mark"];

        $curentMark=[
            "activityId"=>$activityId,
            "mark"=>$mark
        ];

        array_push($markArray,json_encode($curentMark));
    }
    $return['marks']=$markArray;

        // $myObj = new stdClass();
        // $myObj->name = "John";
        // $myObj->age = 30;
        // $myObj->city = "New York";
        // 
        // $myJSON = json_encode($myObj);
        // 
        // echo $myJSON;
            // {
            //   "id": 10,
            //   "firstName": "theUser",
            //   "lastName": "John",
            //   "email": "john@email.com",
            //   "team": 1,
            //   "state": {
                // "id": 1,
                // "title": "En ligne",
                // "color": "green",
                // "icon": "check"
            //   },
            //   "skills": [
                // {
                //   "name": "agile",
                //   "level": 2,
                //   "color": "brown",
                //   "icon": "brightness_1"
                // }
            //   ],
            //   "marks": [
                // {
                //   "activityId": 1,
                //   "mark": 5
                // }
            //   ]
            // }

    echo json_encode($return);
    exit;
});

get("/states",function(){
    global $conn;
   
    return list_states($conn);

});

header("HTTP/1.0 404");
