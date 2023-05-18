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
$tchId = $_POST['tchId'];
if (connect_mysql()->query("DELETE FROM `php_sms`.`teacher` WHERE `id` = '$tchId'")) {
    if (connect_mysql()->query("SELECT * FROM `php_sms`.`gradestable` WHERE `teacheruid` = '$tchId'")->num_rows > 0) {
        connect_mysql()->query("DELETE FROM `php_sms`.`gradestable` WHERE `teacheruid` = '$tchId'");
    }
    $data = array(
        'status' => 'success',
        'message' => '删除教师成功',
    );
} else {
    $data = array(
        'status' => 'error',
        'message' => '删除教师失败',
    );
}
echo json_encode($data);
mysqli_close(connect_mysql());
?>