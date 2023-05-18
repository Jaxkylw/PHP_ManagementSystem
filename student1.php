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
//嵌入后端
$data_row = connect_mysql()->query("SELECT * FROM `php_sms`.student WHERE `account` = '$stu_account' AND `password` = '$stu_password'")->fetch_assoc();
$data = array();
$data = $data_row;
$uid = $data['id'];
$account = $data['account'];
$username = $data['name'];
$username_first = strtoupper(mb_substr($username, 0, 1, 'utf-8'));
$gender = $data['gender'];
$major = $data['major'];
$class = $data['class'];
$create_time = $data['create_time'];
$permission = $data['permission'];
//session 传递,只用于提交修改数据
$_SESSION['pri_uid'] = $uid;
$_SESSION['pri_username'] = $username;
$_SESSION['pri_major'] = $major;
$_SESSION['pri_class'] = $class;
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>学生-个人信息</title>
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
                        <a class="nav-link active" href="#">个人信息</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="student2.php">我的成绩</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="student3.php">我的班级</a>
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
<div class="container d-flex flex-column animate__animated animate__fadeIn">
    <div class="align-items-center justify-content-center" style="padding-top: 20px;">
        <div class="card hover-translate-y-n10">
            <div class="card-body">
                <div class="pb-4">
                    <span class="avatar bg-primary text-white"
                          style="font-size: x-large"><?php echo $username_first ?></span>
                    <h2 class="h5 mt-4 mb-0" style="font-size: x-large"><?php echo $username ?>
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                data-target="#updateModal" style="float: right">
                            录入/修改信息
                        </button>
                    </h2>
                </div>
                <div class="pt-2 pb-3">
                    <table class="table table-striped table-bordered table-hover">
                        <tbody>
                        <tr>
                            <th scope="col" style="color: black;font-size: medium"><strong>UID</strong></th>
                            <td style="text-align: center;font-size: medium"><?php echo $uid ?></td>
                        </tr>
                        <tr>
                            <th scope="col" style="color: black;font-size: medium"><strong>账号</strong></th>
                            <td style="text-align: center;font-size: medium"><?php echo $account ?></td>
                        </tr>
                        <tr>
                            <th scope="col" style="color: black;font-size: medium"><strong>姓名</strong></th>
                            <td style="text-align: center;font-size: medium"><?php echo $username ?></td>
                        </tr>
                        <tr>
                            <th scope="col" style="color: black;font-size: medium"><strong>性别</strong></th>
                            <td style="text-align: center;font-size: medium"><?php echo $gender ?></td>
                        </tr>
                        <tr>
                            <th scope="col" style="color: black;font-size: medium"><strong>专业</strong></th>
                            <td style="text-align: center;font-size: medium"><?php echo $major ?></td>
                        </tr>
                        <tr>
                            <th scope="col" style="color: black;font-size: medium"><strong>班级</strong></th>
                            <td style="text-align: center;font-size: medium"><?php echo $class ?></td>
                        </tr>
                        <tr>
                            <th scope="col" style="color: black;font-size: medium"><strong>密码</strong></th>
                            <td style="text-align: center;font-size: medium">
                                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                        data-target="#modal_success">
                                    修改密码
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <th scope="col" style="color: black;font-size: medium"><strong>注册时间</strong></th>
                            <td style="text-align: center;font-size: medium"><?php echo $create_time ?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!--信息修改框-->
<div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">信息修改/录入</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <?php
                if (connect_mysql()->query("select * from `php_sms`.studenttmp where `id` = '$uid' AND `tag`= '0' ")->num_rows > 0) {
                    $tmp = array();
                    $tmp = connect_mysql()->query("select * from `php_sms`.studenttmp where `id` = '$uid' AND `tag` = '0'")->fetch_all(MYSQLI_ASSOC);
                    echo "<div style='border-bottom: 3px solid #eaecf3;'>";
                    echo "<div class='alert alert-warning shadow-lg' role='alert' style='text-align: center'>你有一条待审核的申请，请勿重复申请</div>";
                    echo "<table class='table table-striped table-bordered table-hover table-sm'>";
                    echo "<thead>";
                    echo "<tr>";
                    echo "<td style='text-align: center;font-size: medium'><strong>姓名</strong></td>";
                    echo "<td style='text-align: center;font-size: medium'><strong>专业</strong></td>";
                    echo "<td style='text-align: center;font-size: medium'><strong>班级</strong></td>";
                    echo "<tr>";
                    echo "</thead>";
                    echo "<tbody>";
                    echo "<tr>";
                    echo "<td style='text-align: center;font-size: medium'><del>" . $username . "</del> -> <span style='color: #0f5da2'>" . $tmp[0]['name'] . "</span></td>";
                    echo "<td style='text-align: center;font-size: medium'><del>" . $major . "</del> -> <span style='color: #0f5da2'>" . $tmp[0]['major'] . "</span></td>";
                    echo "<td style='text-align: center;font-size: medium'><del>" . $class . "</del> -> <span style='color: #0f5da2'>" . $tmp[0]['class'] . "</span></td>";
                    echo "<tr>";
                    echo "</tbody>";
                    echo "</table>";
                    echo "</div>";
                }
                ?>
                <div class="form-group mb-3 mt-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">姓名</span>
                        </div>
                        <input type="text" id="updname" class="form-control" placeholder="姓名">
                    </div>
                </div>
                <div class="form-group mb-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">专业</span>
                        </div>
                        <input type="text" id="updmajor" class="form-control" placeholder="专业">
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">班级</span>
                        </div>
                        <input type="text" id="updclass" class="form-control" placeholder="班级">
                    </div>
                </div>

                <div id="applyinfo" class="alert shadow-lg" role="alert" style="display: none;text-align: center"></div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-primary" onclick="updinfo()">提交</button>
            </div>
        </div>
    </div>
</div>
<!--end-->
<!-- 修改密码提示框 -->
<div class="modal fade" id="modal_success" tabindex="-1" role="dialog" aria-labelledby="modal_success"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title h6" id="modal_title_6">修改您的密码</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="py-3 text-center">
                    <form>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i data-feather="key"></i></span>
                                </div>
                                <input type="text" class="form-control" placeholder="输入原密码"
                                       maxlength="20" id="pripwd">
                            </div>
                        </div>
                        <div class="form-group mb-0">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i data-feather="key"></i></span>
                                </div>
                                <input type="text" class="form-control" placeholder="输入新密码"
                                       maxlength="20" id="updpwd">
                                <div class="input-group-append" data-toggle="tooltip" data-placement="top"
                                     title="8-20位，由字母、符号、数字组成，符号包含!@#$%^&*">
                                    <span class="input-group-text"><i data-feather="help-circle"></i></span>
                                </div>
                            </div>
                        </div>
                        <div id="info"></div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-primary" onclick="updatePassword()" id="updateDetemine">
                    <div style="display: block" id="successText">确定</div>
                    <div class="spinner-border text-secondary" id="loading" role="status" style="display: none"></div>
                </button>
            </div>
        </div>
    </div>
</div>
<!--修改密码提示框_end-->
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

    function updinfo() {
        var updname = $("#updname").val();
        var updmajor = $("#updmajor").val();
        var updclass = $("#updclass").val();
        var applyinfo = document.getElementById("applyinfo");
        applyinfo.style.display = "none";
        $.ajax({
            url: "bs_student1_update_info.php",
            type: "POST",
            data: {
                updname: updname,
                updmajor: updmajor,
                updclass: updclass,
            },
            success: function (data) {
                if (data.status === 'success') {
                    if (applyinfo.classList.contains('alert-warning')) {
                        applyinfo.classList.remove('alert-warning');
                    }
                    if (applyinfo.classList.contains('alert-danger')) {
                        applyinfo.classList.remove('alert-danger');
                    }
                    applyinfo.classList.add('alert-success');
                    applyinfo.classList.add('animate__animated');
                    applyinfo.classList.add('animate__fadeIn');
                    applyinfo.style.display = "block";
                    applyinfo.innerText = data.message;
                    setTimeout(function () {
                        window.location.reload();
                    }, 1000);
                } else if (data.status === 'warning') {
                    if (applyinfo.classList.contains('alert-success')) {
                        applyinfo.classList.remove('alert-success');
                    }
                    if (applyinfo.classList.contains('alert-danger')) {
                        applyinfo.classList.remove('alert-danger');
                    }
                    applyinfo.classList.add('alert-warning');
                    applyinfo.classList.add('animate__animated');
                    applyinfo.classList.add('animate__fadeIn');
                    applyinfo.style.display = "block";
                    applyinfo.innerText = data.message;
                } else {
                    if (applyinfo.classList.contains('alert-success')) {
                        applyinfo.classList.remove('alert-success');
                    }
                    if (applyinfo.classList.contains('alert-warning')) {
                        applyinfo.classList.remove('alert-warning');
                    }
                    applyinfo.classList.add('alert-danger');
                    applyinfo.classList.add('animate__animated');
                    applyinfo.classList.add('animate__fadeIn');
                    applyinfo.style.display = "block";
                    applyinfo.innerText = data.message;
                }
            }
        });
    }

    function updatePassword() {
        var info = document.getElementById("info");
        var loading = document.getElementById("loading");
        var successText = document.getElementById("successText");
        var updateDetemine = document.getElementById("updateDetemine");
        info.style.display = 'none';
        $.ajax({
            url: "api_update_pwd.php",
            type: "POST",
            data: {
                updatepwd: $("#updpwd").val(),
                pripwd: $("#pripwd").val()
            },
            success: function (data) {
                //判断前清除上一个样式，否会会继承上一个样式
                if (info.classList.contains('alert-success')) {
                    info.classList.remove('alert-success');
                }
                if (info.classList.contains('alert-danger')) {
                    info.classList.remove('alert-danger');
                }
                if (info.classList.contains('alert-warning')) {
                    info.classList.remove('alert-warning');
                }
                //添加样式
                info.classList.add('mt-4');
                info.classList.add('alert');
                info.classList.add('animate__animated');
                info.classList.add('animate__fadeIn');
                info.style.textAlign = 'center';
                info.style.display = 'block';
                info.ariaRoleDescription = 'alert';
                if (data.status === "success") {
                    info.classList.add('alert-success');
                    info.innerHTML = data.message;
                    successText.style.display = 'none';
                    loading.style.display = 'block';
                    updateDetemine.disabled = true;
                    setTimeout(function () {
                        window.location.href = "api_loginout.php";
                    }, 1000);
                } else if (data.status === "error_pripwd") {
                    info.classList.add('alert-danger');
                    info.innerHTML = data.message;
                } else if (data.status === "warning") {
                    info.classList.add('alert-warning');
                    info.innerHTML = data.message;
                } else if (data.status === "error") {
                    info.classList.add('alert-danger');
                    info.innerHTML = data.message;
                } else {
                    info.classList.add('alert-danger');
                    info.innerHTML = "未知错误";
                }
            }
        })
    }
</script>
</body>
</html>