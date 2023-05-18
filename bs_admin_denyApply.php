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
$uid = $_POST['uid'];
if (connect_mysql()->query("SELECT * FROM `php_sms`.studenttmp WHERE `id` = '$uid' AND `tag` = '0'")->num_rows != 1) {
    $data = array(
        'status' => 'error',
        'message' => '操作失败：该用户已受理',
    );
    echo json_encode($data);
    exit();
}
if (connect_mysql()->query("UPDATE `php_sms`.`studenttmp` SET `tag` = '2' WHERE `studenttmp`.`id` = '$uid'")) {
    $data = array(
        'status' => 'success',
        'message' => '操作成功',
    );
} else {
    $data = array(
        'status' => 'error',
        'message' => '操作失败：请求失败',
    );
}
echo json_encode($data);
?>