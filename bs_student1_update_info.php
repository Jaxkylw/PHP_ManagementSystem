<?php
header('Content-Type: application/json');
require_once 'api_connect_mysql.php';
if (isset($_POST['updname']) && isset($_POST['updmajor']) && isset($_POST['updclass']) && $_POST['updname'] != '' && $_POST['updmajor'] != '' && $_POST['updclass'] != '') {
    $updname = $_POST['updname'];
    $updmajor = $_POST['updmajor'];
    $updclass = $_POST['updclass'];
    session_start();
    $uid = $_SESSION['pri_uid'];
    $username = $_SESSION['pri_username'];
    $major = $_SESSION['pri_major'];
    $classname = $_SESSION['pri_class'];
    if (connect_mysql()->query("SELECT * FROM `php_sms`.studenttmp WHERE `id` = '$uid' AND `tag` = '0'")->num_rows > 0) {
        $data = array(
            'status' => 'error',
            'message' => '提交失败:你已经提交过了，请等待教务老师审核',
        );
    } else {
        if (connect_mysql()->query("INSERT INTO `php_sms`.studenttmp (`id`,`name`,`major`,`priname`,`class`,`primajor`,`priclass`) VALUES ('$uid','$updname','$updmajor','$username','$updclass','$major','$classname')")) {
            $data = array(
                'status' => 'success',
                'message' => '提交成功',
            );
            // 删除session
            unset($_SESSION['pri_username']);
            unset($_SESSION['pri_uid']);
            unset($_SESSION['pri_major']);
            unset($_SESSION['pri_class']);
        } else {
            $data = array(
                'status' => 'error',
                'message' => '提交失败',
            );
        }
    }
} else {
    $data = array(
        'status' => 'warning',
        'message' => '请输入完整的信息',
    );
}

echo json_encode($data);
?>