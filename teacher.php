<?php
// Session 和 权限不满足，跳转到登录页面
header('Content-Type: text/html; charset=utf-8');
require_once 'api_connect_mysql.php';
session_start();
if (isset($_SESSION['account']) && isset($_SESSION['password']) && isset($_SESSION['permission'])) {
    $tch_account = $_SESSION['account'];
    $tch_password = $_SESSION['password'];
    $tch_permission = $_SESSION['permission'];
    if (connect_mysql()->connect_error) {
        echo '<script>alert("数据库连接失败");window.location.href="login.html";</script>';
        exit();
    }
    if (!connect_mysql()->query("SELECT * FROM `php_sms`.teacher WHERE `account` = '$tch_account' AND `password` = '$tch_password'")->num_rows > 0) {
        echo '<script>alert("登录信息变化");window.location.href="api_loginout.php";</script>';
        exit();
    }
    if ($tch_permission != 'teacher') {
        echo '<script>alert("权限校验失败");window.location.href="api_loginout.php";</script>';
        exit();
    }
} else {
    echo '<script>alert("请先登录");window.location.href="login.html";</script>';
    exit();
}
//嵌入后端_个人信息
$data_row = connect_mysql()->query("SELECT * FROM `php_sms`.teacher WHERE `account` = '$tch_account'")->fetch_assoc();
$data = array();
$data = $data_row;
$teacher_name = $data['name'];
$username_first = strtoupper(mb_substr($teacher_name, 0, 1, 'utf-8'));
$teacher_uid = $data['id'];
//班级信息
$class_data = array();
//查询subject字段，并存入数组，去重
$class_data = connect_mysql()->query("SELECT DISTINCT `subject` FROM `php_sms`.gradestable WHERE `teacheruid` = '$teacher_uid'")->fetch_all();
//去除二维数组
$class_data = array_column($class_data, 0);
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>教师-管理页面</title>
    <link rel="stylesheet" href="assets/libs/@fortawesome/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="assets/css/quick-website.css" id="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
</head>
<body>
<header class="header" id="header-main">
    <nav class="navbar navbar-main navbar-expand-lg fixed-top navbar-shadow navbar-light bg-white border-bottom"
         id="navbar-main">
        <div class="container-fluid justify-content-between">
            <a class="navbar-brand">
                <i data-feather="book" style="height: 40px;width: 40px;"></i>
            </a>
            <h4 class="animate__animated animate__fadeIn" style="padding-top: 10px;">教师端</h4>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse"
                    aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!--menu-->
            <div class="docs-example">
                <div class="btn-group">
                    <div class="btn btn-sm">
                        <div class="collapse navbar-collapse" id="navbarCollapse">
                            <ul class="navbar-nav mt-4 mt-lg-0 ml-auto">
                                <li class="nav-item dropdown dropdown-animate" data-toggle="hover">
                                    <a class="nav-link" href="#" role="button" data-toggle="dropdown"
                                       aria-haspopup="true"
                                       aria-expanded="false">
                                        <span class="avatar bg-primary text-white rounded-circle avatar-sm"><?php echo $username_first ?></span>
                                        <?php echo $teacher_name ?>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-single">
                                        <a class="dropdown-item" onclick="loginOut()">
                                            <i class="text-primary"></i>登出</a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!--end-->
        </div>
    </nav>
</header>
<div class="container d-flex flex-column animate__animated animate__fadeIn" style="padding-top: 20px;">
    <div id="page1" style="display: block">
        <h4>欢迎您！<?php echo $teacher_name ?><sub style="color: #a9b7c6"> UID：<?php echo $teacher_uid ?></sub>
        </h4>
        <h5>我的课程</h5>
        <?php
        for ($i = 0; $i < count($class_data); $i++) {
            $subject_code = array();
            $subject_stu = array();
            $subject_code = connect_mysql()->query("SELECT `subject_code` FROM `php_sms`.gradestable WHERE `subject` = '$class_data[$i]'")->fetch_assoc()['subject_code'];
            $subject_stu_count = connect_mysql()->query("SELECT * FROM `php_sms`.gradestable WHERE `subject` = '$class_data[$i]' AND `student_name` IS NOT NULL AND `teacheruid` = '$teacher_uid'")->num_rows;
            ?>
            <div class="container d-flex flex-column animate__animated animate__fadeIn"
                 id="<?php echo $subject_code ?>">
                <div class="align-items-center justify-content-center" style="padding-top: 20px;">
                    <div class="card hover-translate-y-n10">
                        <div class="card-body">
                            <div class="pb-4">
                                <span class="avatar bg-primary text-white rounded-circle"><?php echo strtoupper(mb_substr($class_data[$i], 0, 1, 'utf-8')) ?></span>
                                <div style="display: inline-block;float: right">
                                    <button class="btn btn-primary btn-sm" onclick="tclass(event)">管理班级
                                    </button>
                                </div>
                            </div>
                            <div class="pt-2 pb-3">
                                <h5><?php echo $class_data[$i] ?></h5>
                                <p class="text-muted mb-0">
                                    班级人数：<?php echo $subject_stu_count ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</div>
<script src="assets/libs/jquery/dist/jquery.min.js"></script>
<script src="assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/libs/svg-injector/dist/svg-injector.min.js"></script>
<script src="assets/libs/feather-icons/dist/feather.min.js"></script>
<script src="assets/js/quick-website.js"></script>
<script>
    feather.replace({
        'width': '1em',
        'height': '1em'
    })
</script>
<script>
    function loginOut() {
        window.location.href = "api_loginout.php";
    }

    function tclass(ev) {
        //事件委托，获取id
        var subjectNode = ev.target.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode;
        var subject_code = subjectNode.id;
        $.ajax({
            url: "bs_teacher_class.php",
            type: "POST",
            data: {
                "subject_code": subject_code
            },
            success: function (data) {
                window.location.href = "teacher_class_page.php";
            }
        })
    }
</script>
</body>
</html>