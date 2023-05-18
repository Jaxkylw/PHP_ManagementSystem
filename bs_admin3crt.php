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
$subjectName = $_POST['subjectName'];
$subjectCode = $_POST['subjectCode'];
$selectTeacher = $_POST['selectTeacher'];
if ($subjectName == "" || $subjectCode == "" || $selectTeacher == "" || $selectTeacher == "选择教师" || !isset($_POST['subjectName']) || !isset($_POST['subjectCode']) || !isset($_POST['selectTeacher'])) {
    $data = array(
        'status' => 'warning',
        'message' => '请填写完整信息',
    );
} else {
    if (connect_mysql()->query("Select * from `php_sms`.`gradestable` where `subject_code` = '$subjectCode'")->num_rows > 0) {
        $data = array(
            'status' => 'warning',
            'message' => '该课程代号已存在，请更换',
        );
    } elseif (connect_mysql()->query("Select * from `php_sms`.`gradestable` where `subject` = '$subjectName' and `teacheruid` = '$selectTeacher'")->num_rows > 0) {
        $data = array(
            'status' => 'warning',
            'message' => '该教师已教授该课程，请更换',
        );
    } else {
        $teacher_data = array();
        $teacher_data = connect_mysql()->query("Select * from `php_sms`.`teacher` where `id` = '$selectTeacher'")->fetch_assoc();
        $teacher_name = $teacher_data['name'];
        $teacher_account = $teacher_data['account'];
        if (connect_mysql()->query("INSERT INTO `php_sms`.`gradestable` (`teacher`,`teacheruid`,`subject`, `subject_code`,`teacher_account`) VALUES ('$teacher_name','$selectTeacher','$subjectName', '$subjectCode','$teacher_account')")) {
            $data = array(
                'status' => 'success',
                'message' => '添加成功',
            );
        } else {
            $data = array(
                'status' => 'error',
                'message' => '添加失败',
            );
        }
    }
}
echo json_encode($data);
mysqli_close(connect_mysql());
?>