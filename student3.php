<?php
// Session 和 权限不满足，跳转到登录页面
header('Content-Type: text/html; charset=utf-8');
require_once 'api_connect_mysql.php';
session_start();
if (isset($_SESSION['account']) && isset($_SESSION['password']) && isset($_SESSION['permission'])) {
    $stu_account = $_SESSION['account'];
    $stu_password = $_SESSION['password'];
    $stu_permission = $_SESSION['permission'];
    if (connect_mysql()->connect_error) {
        echo '<script>alert("数据库连接失败");window.location.href="login.html";</script>';
        exit();
    }
    if (!connect_mysql()->query("SELECT * FROM `php_sms`.student WHERE `account` = '$stu_account' AND `password` = '$stu_password'")->num_rows > 0) {
        echo '<script>alert("登录信息变化");window.location.href="api_loginout.php";</script>';
        exit();
    }
    if ($stu_permission != 'student') {
        echo '<script>alert("权限校验失败");window.location.href="api_loginout.php";</script>';
        exit();
    }
} else {
    echo '<script>alert("请先登录");window.location.href="login.html";</script>';
    exit();
}
//取出当前学生的班级和专业
$stu_data_row = array();
$stu_data_row = connect_mysql()->query("SELECT * FROM `php_sms`.student WHERE `account` = '$stu_account'")->fetch_assoc();
$stu_major = $stu_data_row['major'];
$stu_class = $stu_data_row['class'];
//查询显示该专业班级的所有学生
$stu_data = array();
$stu_data = connect_mysql()->query("SELECT * FROM `php_sms`.student WHERE `major` = '$stu_major' AND `class` = '$stu_class'")->fetch_all(MYSQLI_ASSOC);
$data_row = connect_mysql()->query("SELECT * FROM `php_sms`.student WHERE `account` = '$stu_account' AND `password` = '$stu_password'")->fetch_assoc();
$data = array();
$data = $data_row;
$username = $data['name'];
$username_first = strtoupper(mb_substr($username, 0, 1, 'utf-8'));
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>学生-我的班级</title>
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
            <h4 style="padding-top: 10px;">学生端</h4>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse"
                    aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse animate__animated animate__fadeIn" id="navbarCollapse">
                <ul class="navbar-nav align-items-center mx-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="student1.php">个人信息</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="student2.php">我的成绩</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="#">我的班级</a>
                    </li>
                </ul>
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
                                            <?php echo $username ?>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-single">
                                            <a class="dropdown-item" href="#">
                                                <i class="fas fa-user-plus text-primary"></i>录入/修改信息</a>
                                            <a class="dropdown-item" onclick="loginOut()">
                                                <i class="fas fa-sign-out-alt text-primary"></i>登出</a>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end-->
            </div>
        </div>
    </nav>
</header>
<div class="container d-flex flex-column animate__animated animate__fadeIn" style="padding-top: 20px;">
    <div class="card">
        <div class="card-body">
            <div class="pt-2 pb-3">
                <h5><?php
                    if ($stu_major != "未录入") {
                        echo $stu_major . "-" . $stu_class;
                    } else {
                        echo "您还未加入任何班级！";
                    } ?></h5>
                <div class="pt-2 pb-3">
                    <table class="table table-striped table-borderless table-hover">
                        <tbody>
                        <?php
                        if ($stu_major != "未录入") {
                            $i = 0;
                            for ($i = 0; $i < count($stu_data); $i++) {
                                echo '<tr>';
                                echo '<td style="text-align: center;font-size: medium">' . ($i + 1) . '</td>';
                                echo '<td style="text-align: right"><span class="avatar bg-primary text-white rounded-circle avatar-sm">' . strtoupper(mb_substr($stu_data[$i]['name'], 0, 1, 'utf-8')) . '</span></td>';
                                echo '<td style="text-align: left;font-size: medium">' . $stu_data[$i]['name'] . '</td>';
                                echo '<td style="text-align: center;font-size: medium">' . $stu_data[$i]['id'] . '</td>';
                            }
                        } else {
                            echo "<tr><td style='text-align: center;font-size: medium;'>暂无数据</td></tr>";
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
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
</script>
</body>
</html>