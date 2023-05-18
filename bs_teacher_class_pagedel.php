<?php
header('Content-Type: application/json');
require_once 'api_connect_mysql.php';
session_start();
$delid = $_POST['delid'];
$subject_code = $_SESSION['bsSubjectCode'];
if (connect_mysql()->query("DELETE FROM `php_sms`.gradestable WHERE `student_uid` = '$delid' AND `subject_code` = '$subject_code'")) {
    $data = array(
        'status' => 'success',
        'message' => '删除成功',
    );
}else{
    $data = array(
        'status' => 'error',
        'message' => '删除失败',
    );
}
echo json_encode($data);
?>