<?php

$host = 'localhost';
$db   = 'palm';
$user = 'palmscript';
$pass = 'NwGiz0Gq6MXAGrOzYcgf';
$charset = 'utf8';
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$opt = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
$pdb = new PDO($dsn, $user, $pass, $opt);

require("../../private/tokenstuff.php");


if($_SERVER['REQUEST_METHOD'] == 'GET' && $_GET['key'] == $secretkey)
{

    include("../../views/palm_new_user_form.php");

}

if($_SERVER['REQUEST_METHOD'] == 'POST' && $_GET['mode'] == "adduser")
{

    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $token = getToken(20);

            try {
                $create = $pdb->prepare("INSERT INTO user(email,password,name,token) VALUES(?,?,?,?)")
                            ->execute(array($_POST["email"], $password, $_POST["name"],$token));
            }

            catch (PDOException $e) {
                if ($e->getCode() == 1062) {
                // Take some action if there is a key constraint violation, i.e. duplicate name
                    print("key constraint violation somehow!");
                } 
                else {
                    throw $e;
                }
            }

            $insertid = $pdb->lastInsertId();

    print("id: " . $insertid . "<br> token: " . $token);

}


?>
