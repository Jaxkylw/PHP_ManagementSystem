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
if (isset($_POST['account']) && isset($_POST['password']) && isset($_POST['check_password']) && isset($_POST['name']) && isset($_POST['selectGenderTags']) && !empty($_POST['account']) && !empty($_POST['password']) && !empty($_POST['check_password']) && !empty($_POST['name']) && !empty($_POST['selectGenderTags']) && $_POST['account'] != '' && $_POST['password'] != '' && $_POST['check_password'] != '' && $_POST['name'] != '' && $_POST['selectGenderTags'] != '') {
    $account = $_POST['account'];
    $password = $_POST['password'];
    $check_password = $_POST['check_password'];
    $name = $_POST['name'];
    if($_POST['selectGenderTags'] == "boy"){
        $gender = "男";
    } else {
        $gender = "女";
    }

    $account_regular = '/^[a-zA-Z0-9]{5,10}$/';
    $password_regular = '/^[a-zA-Z0-9!@#$%^&*]{8,20}$/';

    if ($password != $check_password) {
        $data = array(
            'status' => 'error',
            'message' => ' 两次密码不一样',
        );
    } elseif (!preg_match($account_regular, $account) || !preg_match($password_regular, $password)) {
        $data = array(
            'status' => 'error',
            'message' => '账号或密码不符合规范',
        );
    } else if (connect_mysql()->query("SELECT * FROM `php_sms`.student WHERE `account` = '$account'")->num_rows > 0) {
        $data = array(
            'status' => 'error',
            'message' => '该账号已被注册',
        );
    } else {
        $password = md5($password);
        if (connect_mysql()->query("INSERT INTO `php_sms`.student (`account`, `password`,`name`,`gender`,`create_time`) VALUES ('$account', '$password','$name','$gender',NOW())")) {
            $data = array(
                'status' => 'success',
                'message' => ' 注册成功,正在跳转到登录页面...',
            );
        } else {
            $data = array(
                'status' => 'error',
                'message' => ' 注册失败',
            );
        }
    }
} else {
    $data = array(
        'status' => 'warning',
        'message' => '请填写完整的注册信息',
    );
}
echo json_encode($data);
mysqli_close(connect_mysql());
?>