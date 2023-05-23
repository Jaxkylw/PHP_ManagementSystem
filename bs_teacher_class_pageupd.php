<?php
header('Content-Type: application/json');
require_once 'api_connect_mysql.php';
session_start();
$bsSubjectCode = $_SESSION['bsSubjectCode'];
$text = $_POST['text'];
$updid = $_POST['updid'];
//只允许0-100的整数或两位小数
$text_regular = '/^([1-9]\d?|0|100)(\.\d{1,2})?$/';
if (preg_match($text_regular, $text)) {
    connect_mysql()->query("UPDATE `php_sms`.gradestable SET `student_achievement` = '$text' WHERE `subject_code` = '$bsSubjectCode' AND `student_uid` = '$updid'");
    $achievement = connect_mysql()->query("SELECT `student_achievement` FROM `php_sms`.gradestable WHERE `subject_code` = '$bsSubjectCode' AND `student_uid` = '$updid'")->fetch_assoc()['student_achievement'];
    $data = array(
        'status' => 'success',
        'message' => '修改成功',
        'achievement' => $achievement,
    );
} else {
    $data = array(
        'status' => 'error',
        'message' => ' 格式不符合规范',
    );
}
echo json_encode($data);
?>