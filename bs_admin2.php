<?php
header('Content-Type: application/json');
require_once 'api_connect_mysql.php';
if (connect_mysql()->connect_error) {
    $data = array(
        'status' => 'error',
        'message' => '数据库连接失败',
    );
    echo json_encode($data);
    exit();
}
if (isset($_POST['teacherName']) && isset($_POST['teacherAccount']) && isset($_POST['password']) && isset($_POST['checkPassword']) && !empty($_POST['teacherName']) && !empty($_POST['teacherAccount']) && !empty($_POST['password']) && !empty($_POST['checkPassword']) && $_POST['teacherName'] != '' && $_POST['teacherAccount'] != '' && $_POST['password'] != '' && $_POST['checkPassword'] != '') {
    $teacherName = $_POST['teacherName'];
    $teacherAccount = $_POST['teacherAccount'];
    $password = md5($_POST['password']);
    $checkPassword = md5($_POST['checkPassword']);
    $password_regular = '/^[a-zA-Z0-9!@#$%^&*]{8,20}$/';
    if(!preg_match($password_regular,$_POST['password'])){
        $data = array(
            'status' => 'warning',
            'message' => '密码不符合规范',
        );
        echo json_encode($data);
        mysqli_close(connect_mysql());
        exit;
    }
    if ($password == $checkPassword) {
        if (connect_mysql()->query("SELECT * FROM `php_sms`.`teacher` WHERE `account` = '$teacherAccount'")->num_rows > 0) {
            $data = array(
                'status' => 'warning',
                'message' => '该账号已存在',
            );
        } else {
            if (connect_mysql()->query("INSERT INTO `php_sms`.`teacher` (`name`, `account`, `password`,`create_time`) VALUES ('$teacherName', '$teacherAccount', '$password',NOW())")) {
                $data = array(
                    'status' => 'success',
                    'message' => '新建教师成功',
                );
            } else {
                $data = array(
                    'status' => 'error',
                    'message' => '新建教师失败',
                );
            }
        }
    }else {
        $data = array(
            'status' => 'warning',
            'message' => '两次密码不一致',
        );
    }
} else {
    $data = array(
        'status' => 'warning',
        'message' => '请填写完整信息',
    );
}
echo json_encode($data);
mysqli_close(connect_mysql());
?>