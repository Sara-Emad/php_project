<?php


require_once 'Database.php';

$db = new Database();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $ext = trim($_POST['ext']);
    $room = trim($_POST['room']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = trim($_POST['role']); 

    $users = $db->select('users');
    foreach ($users as $user) {
        if ($user['email'] === $email) {
            echo "<div class='alert alert-danger text-center'>Email is already logged in </div>";
            exit;
        }
    }

    $data = [
        "name" => $name,
        "email" => $email,
        "room" => $room,
        "ext" => $ext,
        "password" => $password,
        "role" => $role
    ];

    if ($db->insert("users", $data)) {
        echo "<div class='alert alert-success text-center'>تم التسجيل بنجاح!</div>";
        header('Location: login_form.php');
        exit; // 🔹 ضروري لضمان التوجيه الصحيح
    } else {
        echo "<div class='alert alert-danger text-center'>حدث خطأ أثناء التسجيل!</div>";
    }
}












// require_once 'Database.php';


// $db = new Database();
// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $name = $_POST['name'];
//     $email = $_POST['email'];
//     $ext = $_POST['ext'];
//     $room = $_POST['room'];
//     $password = password_hash($_POST['password'], PASSWORD_BCRYPT); 
//     $role = $_POST['role'];
//     $data = [
//         "name" => $name,
//         "email" => $email,
//         "room" => $room,
//         "ext" => $ext,
//         "password" => $password,
//         "role" => $role
//     ];

//     $db->insert("users", $data);

//     echo "<div class='alert alert-success text-center'>Registration Successful!</div>";
//     header('Location: login_form.php');
// }
?>
