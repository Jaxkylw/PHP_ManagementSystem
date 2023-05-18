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
$subjectCode = $_POST['subjectCode'];
if($subjectCode == null){
    $data = array(
        'status' => 'error',
        'message' => '参数错误',
    );
    echo json_encode($data);
    exit();
}elseif (connect_mysql()->query("DELETE FROM `php_sms`.gradestable WHERE `subject_code` = '$subjectCode'")) {
    $data = array(
        'status' => 'success',
        'message' => '删除成功',
    );
    echo json_encode($data);
    exit();
}else{
    $data = array(
        'status' => 'error',
        'message' => '删除失败',
    );
    echo json_encode($data);
    exit();
}
?>