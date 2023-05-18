<?php
header('Content-Type: application/json');
require_once 'api_connect_mysql.php';
session_start();
$bsTeacherAccount = $_SESSION['bsTeacherAccount'];
$bsSubjectCode = $_SESSION['bsSubjectCode'];
$addid = $_POST['addid'];
$studata = connect_mysql()->query("SELECT * FROM `php_sms`.student WHERE `id` = '$addid'")->fetch_assoc();
$stu_name = $studata['name'];
$stu_major = $studata['major'];
$stu_class = $studata['class'];
$tchdata = connect_mysql()->query("SELECT * FROM `php_sms`.teacher WHERE `account` = '$bsTeacherAccount'")->fetch_assoc();
$tch_name = $tchdata['name'];
$tch_id = $tchdata['id'];
$subjectdata = array();
$subjectdata = connect_mysql()->query("SELECT DISTINCT `subject` FROM `php_sms`.gradestable WHERE `subject_code` = '$bsSubjectCode'")->fetch_assoc()['subject'];
$subject = $subjectdata;
if (connect_mysql()->query("SELECT * FROM `php_sms`.gradestable WHERE `student_uid` = '$addid' AND `subject_code` = '$bsSubjectCode'")->num_rows > 0) {
    $data = array(
        'status' => 'error',
        'message' => '添加失败:该学生已在班级中',
    );
} else {
    connect_mysql()->query("INSERT INTO `php_sms`.gradestable (teacher, teacheruid, subject, subject_code, student_uid, student_name, student_major, student_grade, teacher_account) VALUES ('$tch_name', '$tch_id', '$subject', '$bsSubjectCode', '$addid', '$stu_name', '$stu_major', '$stu_class', '$bsTeacherAccount')");
    $data = array(
        'status' => 'success',
        'message' => '添加成功',
    );
}
echo json_encode($data);
?>