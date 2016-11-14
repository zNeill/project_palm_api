<?php

require("../../private/tokenstuff.php");

//validate json -- returns true if no errors
function isValidJSON($str) {
    json_decode($str);
    return json_last_error() == JSON_ERROR_NONE;
}


//add users
if ($_SERVER["REQUEST_METHOD"] == "POST" && $_GET["mode"] == "newuser") {

    $nuinput =  file_get_contents('php://input');

    if (strlen($nuinput) > 0 && isValidJSON($nuinput))
    {
        $nudata = json_decode($nuinput, true);
    } else {
        http_response_code(422);
        echo "ERROR INVALID JSON IN BODY OF REQUEST";
        exit;
    }

    $nuname = $nudata["user"]["name"];
    $nuemail = $nudata["user"]["email"];

    if($nudata["user"]["password"] == $nudata["user"]["password_confirmation"])
    {
        $nupassword = password_hash($nudata["user"]["password"], PASSWORD_DEFAULT);
    }else {
        http_response_code(423);
        echo "ERROR PASSWORDS DO NOT MATCH";
        exit;
    }

    $nutoken = getToken(rand(30,100));

    try {
        $create = $pdb->prepare("INSERT INTO user(email,password,name,token) VALUES(?,?,?,?)")
                    ->execute(array($nuemail, $nupassword, $nuname,$nutoken));
    }
    catch (PDOException $e) {

            http_response_code(424);
            print("ERROR EMAIL ALREADY EXISTS");
            exit;
    }
    print("$nutoken");

}



//authenticate users
if ($_SERVER["REQUEST_METHOD"] == "POST" && $_GET["mode"] == "auth") {

    // echo "Post:";
    // var_dump($_POST);

    // echo "stuff:";
    // var_dump($stuff);


    $json_params =  file_get_contents('php://input');

    if (strlen($json_params) > 0 && isValidJSON($json_params))
    {
        $authdata = json_decode($json_params, true);
    } else {
        http_response_code(422);
        echo "INVALID JSON";
        exit;
    }
    // print("authdata");
    // print_r($authdata);

    $theEmail = $authdata['email'];

    $verifylogin = $pdb->prepare("SELECT * FROM user WHERE email = ?");
    $verifylogin->execute(array($theEmail));

    $result = $verifylogin->fetch(PDO::FETCH_ASSOC);

    // echo "<BR> result:";
    // print_r($result);
    if(!empty($result)) {

        if(password_verify($authdata['password'],$result['password'])) {
            $newtoken = getToken(rand(20,100));

            $updateuser = $pdb->prepare("UPDATE user SET token = ? WHERE id = ?");
            $updateuser->execute(array($newtoken,$result['id']));

            print($newtoken);

        }else{
            http_response_code(423);
            print("ERROR INVALID PASSWORD");
            exit;
        }

    }else{
        http_response_code(423);
        print("ERROR INVALID EMAIL");
    }

}

?>