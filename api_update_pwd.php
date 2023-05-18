<?php
header('Content-Type: application/json');
require_once 'api_connect_mysql.php';
session_start();
$account = $_SESSION['account'];
$password = $_SESSION['password'];
if (connect_mysql()->connect_error) {
    $data = array(
        'status' => 'error_mysql',
        'message' => '数据库连接失败',
    );
    echo json_encode($data);
    exit();
}
if (isset($_POST['pripwd']) && isset($_POST['updatepwd']) && !empty($_POST['pripwd']) && !empty($_POST['updatepwd']) && $_POST['pripwd'] != '' && $_POST['updatepwd'] != '') {
    $pripwd = md5($_POST['pripwd']);
    $newpwd = md5($_POST['updatepwd']);
    $password_regular = '/^[a-zA-Z0-9!@#$%^&*]{8,20}$/';
    if (!preg_match($password_regular, $_POST['updatepwd'])) {
        $data = array(
            'status' => 'error',
            'message' => '密码不符合规范',
        );
    } else {
        if (connect_mysql()->query("SELECT * FROM `php_sms`.student WHERE `account` = '$account' AND `password` = '$pripwd'")->num_rows > 0) {
            if (connect_mysql()->query("UPDATE `php_sms`.student SET `password` = '$newpwd' WHERE `account` = '$account'")) {
                $data = array(
                    'status' => 'success',
                    'message' => '修改成功',
                );
            } else {
                $data = array(
                    'status' => 'error',
                    'message' => '修改失败',
                );
            }
        } else {
            $data = array(
                'status' => 'error_pripwd',
                'message' => '原密码错误',
            );
        }
    }
} else {
    $data = array(
        'status' => 'warning',
        'message' => '密码不能为空',
    );
}
echo json_encode($data);
mysqli_close(connect_mysql());
?>