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
//个人信息
$data_row = connect_mysql()->query("SELECT * FROM `php_sms`.teacher WHERE `account` = '$tch_account'")->fetch_assoc();
$data = array();
$data = $data_row;
$teacher_name = $data['name'];
$username_first = strtoupper(mb_substr($teacher_name, 0, 1, 'utf-8'));
//获取课程
$subject_code = $_SESSION['subjectCode'];
$subject = array();
$subject = connect_mysql()->query("SELECT `subject` FROM `php_sms`.gradestable WHERE `subject_code` = '$subject_code' AND `teacher_account` = '$tch_account'")->fetch_assoc()['subject'];
//获取学生信息
$stu_data = array();
$stu_data = connect_mysql()->query("SELECT * FROM `php_sms`.gradestable WHERE `subject` = '$subject' AND `student_name` IS NOT NULL AND `teacher_account` = '$tch_account'")->fetch_all(MYSQLI_ASSOC);
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
            <h4 style="padding-top: 10px;">教师端</h4>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse"
                    aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse animate__animated animate__fadeInDown" id="navbarCollapse">
                <ul class="navbar-nav align-items-center mx-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="teacher.php">我的班级</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="#">管理班级</a>
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
                                            <?php echo $teacher_name ?>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-single">
                                            <a class="dropdown-item" onclick="loginOut()"><i
                                                        class="fas fa-sign-out-alt text-primary"></i>登出</a>
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
<!--msgdel-->
<div class="modal-dialog modal-lg animate__animated animate__fadeIn" style="display: none" id="msgframedel">
    <div class="alert alert-success" role="alert">
        <strong id="msgdel"></strong>
    </div>
</div>
<!--end-->
<div class="animate__animated animate__fadeIn" style="padding-top: 20px;">
    <div class="card" id="page2">
        <div class="card-body">
            <div class="pt-2 pb-3">
                <h5><?php echo $subject ?>
                    <button type="button" class="btn btn-primary btn-sm mb-3" data-toggle="modal"
                            data-target=".docs-example-modal-lg" style="float: right;">添加学生
                    </button>
                </h5>
                <div class="pt-2 pb-3">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th scope="col" style="text-align: center;font-size: medium;color: black">#</th>
                            <th scope="col" style="text-align: center;font-size: medium;color: black">专业</th>
                            <th scope="col" style="text-align: center;font-size: medium;color: black">班级</th>
                            <th scope="col" style="text-align: center;font-size: medium;color: black">姓名</th>
                            <th scope="col" style="text-align: center;font-size: medium;color: black">
                                <div data-toggle="tooltip" data-placement="top" title="只允许输入0-100的整数或两位小数">成绩(点击修改)<i
                                            data-feather="alert-circle"></i></div>
                            </th>
                            <th scope="col" style="text-align: center;font-size: medium;color: black">操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if (connect_mysql()->query("SELECT * FROM `php_sms`.gradestable WHERE `subject` = '$subject' AND `student_name` IS NOT NULL AND `teacher_account` = '$tch_account'")->fetch_row() != 0) {
                            for ($i = 0; $i < count($stu_data); $i++) {
                                echo '<tr id="' . $stu_data[$i]['student_uid'] . '00' . '">';
                                echo '<td style="text-align: center;font-size: medium;color: black">' . ($i + 1) . '</td>';
                                echo '<td style="text-align: center;font-size: medium;color: black">' . $stu_data[$i]['student_major'] . '</td>';
                                echo '<td style="text-align: center;font-size: medium;color: black">' . $stu_data[$i]['student_grade'] . '</td>';
                                echo '<td style="text-align: center;font-size: medium;color: black">' . $stu_data[$i]['student_name'] . '</td>';
                                echo '<td id="' . $stu_data[$i]['student_uid'] . '0' . '" onclick="test(this)" style="text-align: center;font-size: medium;color: black">' . $stu_data[$i]['student_achievement'] . '</td>';
                                echo '<td style="text-align: center;font-size: medium;color: black"><button type="button" class="btn btn-danger btn-sm mb-3" id="' . $stu_data[$i]['student_uid'] . '" onclick="deleteStu(event)">删除</button></td>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr>';
                            echo '<td style="text-align: center;font-size: medium;color: black">-</td>';
                            echo '<td style="text-align: center;font-size: medium;color: black">-</td>';
                            echo '<td style="text-align: center;font-size: medium;color: black">-</td>';
                            echo '<td style="text-align: center;font-size: medium;color: black">-</td>';
                            echo '<td style="text-align: center;font-size: medium;color: black">-</td>';
                            echo '<td style="text-align: center;font-size: medium;color: black">-</td>';
                            echo '</tr>';
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!--管理框-->
<div class="modal fade docs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
     aria-hidden="true">
    <!--提示-->
    <div class="modal-dialog modal-lg animate__animated animate__fadeIn" style="display: none" id="msgframe">
        <div class="alert alert-success" role="alert">
            <strong id="msg"></strong>
        </div>
    </div>
    <!--end-->
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title h6" id="myLargeModalLabel">添加学生</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!--可在此输入说明文字-->
                <div id="accordion-1" class="accordion accordion-stacked">
                    <div class="card">
                        <div class="card-header py-4" id="heading-1-1" data-toggle="collapse" role="button"
                             data-target="#collapse-1-1" aria-expanded="false" aria-controls="collapse-1-1">
                            <h6 class="mb-0"><i data-feather="user-check" class="mr-3"></i>当前可添加学生</h6>
                        </div>
                        <div id="collapse-1-1" class="collapse" aria-labelledby="heading-1-1"
                             data-parent="#accordion-1">
                            <div class="card-body">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th scope="col" style="text-align: center;font-size: medium;color: black">#</th>
                                        <th scope="col" style="text-align: center;font-size: medium;color: black">姓名
                                        </th>
                                        <th scope="col" style="text-align: center;font-size: medium;color: black">专业
                                        </th>
                                        <th scope="col" style="text-align: center;font-size: medium;color: black">班级
                                        </th>
                                        <th scope="col" style="text-align: center;font-size: medium;color: black">操作
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $sql = "SELECT * FROM `php_sms`.student WHERE `major` != '未录入' AND `class` != '未录入'";
                                    $result = connect_mysql()->query($sql);
                                    $data = $result->fetch_all(MYSQLI_ASSOC);
                                    $sql2 = "SELECT * FROM `php_sms`.gradestable WHERE `subject_code` = '$subject_code' AND `student_name` IS NOT NULL";
                                    $result2 = connect_mysql()->query($sql2);
                                    $stu_data2 = $result2->fetch_all(MYSQLI_ASSOC);
                                    $tmpi = 0;
                                    for ($i = 0; $i < count($data); $i++) {
                                        $tmp = 0;
                                        for ($j = 0; $j < count($stu_data2); $j++) {
                                            if ($data[$i]['id'] == $stu_data2[$j]['student_uid']) {
                                                $tmp = 1;
                                                break;
                                            }
                                        }
                                        if ($tmp == 0) {
                                            $tmpi++;
                                            echo '<tr>';
                                            echo '<td style="text-align: center;font-size: medium;color: black">' . $tmpi . '</td>';
                                            echo '<td style="text-align: center;font-size: medium;color: black">' . $data[$i]['name'] . '</td>';
                                            echo '<td style="text-align: center;font-size: medium;color: black">' . $data[$i]['major'] . '</td>';
                                            echo '<td style="text-align: center;font-size: medium;color: black">' . $data[$i]['class'] . '</td>';
                                            echo '<td style="text-align: center;font-size: medium;color: black"><button type="button" class="btn btn-primary btn-sm mb-3" id="' . $data[$i]['id'] . '" onclick="addclass(event)">添加</button></td>';
                                            echo '</tr>';
                                        }
                                    }
                                    //重置一个新的session，为ajax后台php文件传输数据,必须唯一
                                    $_SESSION['bsTeacherAccount'] = $tch_account;
                                    $_SESSION['bsSubjectCode'] = $subject_code;
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">关闭</button>
            </div>
        </div>
    </div>
</div>
<!--end-->
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

    deleteStu = function (ev) {
        var delidNode = ev.target;
        var delid = delidNode.id;
        var msgframedel = document.getElementById('msgframedel');
        var msgdel = document.getElementById('msgdel');
        var tmp = delid * 100;
        var deleleid = document.getElementById(tmp);
        $.ajax({
            url: "bs_teacher_class_pagedel.php",
            type: "POST",
            data: {
                delid: delid
            },
            success: function (data) {
                if (data.status === 'success') {
                    msgframedel.style.display = 'block';
                    msgdel.innerHTML = data.message;
                    setTimeout(function () {
                        deleleid.hidden = true;
                        setTimeout(function () {
                            msgframedel.style.display = 'none';
                        }, 800);
                    }, 800);
                } else {
                    alert(data.message);
                }
            },
        })
    }

    //事件委托，获取操作目标id
    addclass = function (ev) {
        var addidNode = ev.target;
        var addid = addidNode.id;
        var msg = document.getElementById('msg');
        var msgframe = document.getElementById('msgframe');
        $.ajax({
            url: "bs_teacher_class_page.php",
            type: "POST",
            data: {
                addid: addid
            },
            success: function (data) {
                if (data.status === 'success') {
                    msg.innerHTML = data.message;
                    msgframe.style.display = 'block';
                    setTimeout(function () {
                        window.location.reload();
                    }, 1000);
                } else {
                    alert(data.message);
                }
            },
        })
    }

    //文本框点击更改
    var firstValue = "";
    var nowDom = "";//当前操作的td
    //点击更改事件
    function test(doms) {
        doms.removeAttribute("onclick");
        nowDom = doms;
        var text = doms.innerText;
        doms.innerHTML = '<input style="align-items: center;width: 95px;margin: 0 auto" type="text" class="form-control" placeholder="' + text + '" id="input" onchange="chane(this)"  onblur="inputOnblur(this)"/>';
        firstValue = text;
        document.getElementById("input").focus();
    }

    //文本框更改事件
    function chane(doms) {
        var text = doms.value;
        var updid = nowDom.id;
        updid /= 10;
        if (text !== firstValue) {
            //提交后台更改数据库
            $.ajax({
                url: "bs_teacher_class_pageupd.php",
                type: "POST",
                data: {
                    text: text,
                    updid: updid,
                },
                success: function (data) {
                    if (data.status === 'success') {
                        nowDom.innerHTML = "" + data.achievement;
                        nowDom.setAttribute("onclick", "test(this)");
                    } else {
                        alert(data.message);
                        nowDom.innerHTML = "" + firstValue;
                        nowDom.setAttribute("onclick", "test(this)");
                    }
                },
            })
        }
        nowDom.innerHTML = "" + firstValue;
        nowDom.setAttribute("onclick", "test(this)");
    }

    //文本框失焦事件
    function inputOnblur(doms) {
        var text = doms.value;
        var updid = nowDom.id;
        updid /= 10;
        if (text !== firstValue) {
            $.ajax({
                url: "bs_teacher_class_pageudp.php",
                type: "POST",
                data: {
                    text: text,
                    updid: updid,
                },
                success: function (data) {
                    if (data.status === 'success') {
                        nowDom.innerHTML = "" + data.achievement;
                        nowDom.setAttribute("onclick", "test(this)");
                    } else {
                        alert(data.message);
                        nowDom.innerHTML = "" + firstValue;
                        nowDom.setAttribute("onclick", "test(this)");
                    }
                },
            })
        }
        nowDom.innerHTML = "" + firstValue;
        nowDom.setAttribute("onclick", "test(this)");
    }
</script>
</body>
</html>