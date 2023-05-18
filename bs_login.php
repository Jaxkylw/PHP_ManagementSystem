<?php
header('Content-Type: application/json');
require_once 'api_connect_mysql.php';
if(connect_mysql()->connect_error)
{
    $data = array(
        'status' => 'error',
        'message' => '数据库连接失败',
    );
    echo json_encode($data);
    exit();
}
$permission = $_POST['permission'];
if (isset($_POST['account']) && isset($_POST['password']) && !empty($_POST['account']) && !empty($_POST['password']) && $_POST['account'] != '' && $_POST['password'] != '') {
    $account = $_POST['account'];
    $password = md5($_POST['password']);
    if (connect_mysql()->query("SELECT * FROM `php_sms`.$permission WHERE `account` = '$account' AND `password` = '$password'")->num_rows > 0) {
        session_start();
        $_SESSION['account'] = $account;
        $_SESSION['password'] = $password;
        $_SESSION['permission'] = $permission;
        $data = array(
            'status' => 'success',
            'permission' => $permission,
            'message' => '登录成功,正在跳转2s...',
        );
    } else {
        $data = array(
            'status' => 'error',
            'message' => '账号或密码错误',
        );
    }
} else {
    $data = array(
        'status' => 'warning',
        'message' => '账号或密码不能为空',
    );
}
echo json_encode($data);
mysqli_close(connect_mysql());
