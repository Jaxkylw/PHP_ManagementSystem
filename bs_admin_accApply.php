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
//取出新数据
$data = array();
$data = connect_mysql()->query("SELECT * FROM `php_sms`.studenttmp WHERE `id` = '$uid' AND `tag` = '0'")->fetch_all(MYSQLI_ASSOC);
$name = $data[0]['name'];
$major = $data[0]['major'];
$class = $data[0]['class'];
//操作tag是否合法
if (connect_mysql()->query("SELECT * FROM `php_sms`.studenttmp WHERE `id` = '$uid' AND `tag` = '0'")->num_rows != 1) {
    $data = array(
        'status' => 'error',
        'message' => '操作失败：该用户已受理',
    );
    echo json_encode($data);
    exit();
}
//修改studenttmp表tag
if (connect_mysql()->query("UPDATE `php_sms`.`studenttmp` SET `tag` = '1' WHERE `studenttmp`.`id` = '$uid'")) {
    //更新student,gradestable表
    if (connect_mysql()->query("UPDATE `php_sms`.student SET `name` = '$name', `major` = '$major', `class` = '$class' WHERE `id` = '$uid'") && connect_mysql()->query("UPDATE `php_sms`.gradestable SET `student_name` = '$name', `student_major` = '$major', `student_grade` = '$class' WHERE `student_uid` = '$uid'")) {
        $data = array(
            'status' => 'success',
            'message' => '操作成功',
        );
    } else {
        $data = array(
            'status' => 'error',
            'message' => '操作失败：信息更新失败',
        );
    }
} else {
    $data = array(
        'status' => 'error',
        'message' => '操作失败：请求失败',
    );
}
echo json_encode($data);
?>