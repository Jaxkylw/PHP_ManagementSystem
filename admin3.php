<?php
header('Content-Type: text/html; charset=utf-8');
require_once 'api_connect_mysql.php';
session_start();
if (isset($_SESSION['admin_account']) && isset($_SESSION['admin_password']) && !empty($_SESSION['admin_account']) && !empty($_SESSION['admin_password']) && $_SESSION['admin_account'] != '' && $_SESSION['admin_password'] != '') {
    if (!connect_mysql()->query("SELECT * FROM `php_sms`.administrator WHERE `account` = '" . $_SESSION['admin_account'] . "' AND `password` = '" . $_SESSION['admin_password'] . "'")->num_rows > 0) {
        echo "<script>alert('登录状态已失效,请重新登录');window.location.href='login.html';</script>";
        exit();
    }
} else {
    echo "<script>alert('登录状态已失效,请重新登录');window.location.href='login.html';</script>";
    exit();
}
//取出当前管理员的信息
$admin_data_row = array();
$admin_data_row = connect_mysql()->query("SELECT * FROM `php_sms`.administrator WHERE `account` = '" . $_SESSION['admin_account'] . "'")->fetch_assoc();
$admin_name = $admin_data_row['name'];
$admin_username_first = strtoupper(mb_substr($admin_name, 0, 1, 'utf-8'));
?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Title</title>
    </head>
    <link rel="stylesheet" href="assets/libs/@fortawesome/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="assets/css/quick-website.css" id="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white">
        <div class="container">
            <i class="navbar-brand" data-feather="aperture" style="height: 35px;width: 35px;"></i>
            <h4 style="padding-top: 10px;">教务系统-课程管理</h4>

            <!-- Toggler -->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse"
                    aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Collapse -->
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav mt-4 mt-lg-0 ml-auto">
                    <li class="nav-item dropdown dropdown-animate" data-toggle="hover">
                        <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true"
                           aria-expanded="false">管理页面</a>
                        <div class="dropdown-menu dropdown-menu-single">
                            <a href="admin.php" class="dropdown-item">学生管理</a>
                            <a href="admin2.php" class="dropdown-item">教师管理</a>
                            <a href="#" class="dropdown-item">课程管理</a>
                        </div>
                    </li>
                </ul>
                <!--userinfo-->
                <div class="docs-example">
                    <div class="btn-group">
                        <div class="btn btn-sm">
                            <div class="collapse navbar-collapse" id="navbarCollapse">
                                <ul class="navbar-nav mt-4 mt-lg-0 ml-auto">
                                    <li class="nav-item dropdown dropdown-animate" data-toggle="hover">
                                        <a class="nav-link" href="#" role="button" data-toggle="dropdown"
                                           aria-haspopup="true"
                                           aria-expanded="false">
                                            <span class="avatar bg-primary text-white rounded-circle avatar-sm"><?php echo $admin_username_first ?></span>
                                            <?php echo $admin_name ?>
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
        </div>
    </nav>
    <!--msgdel-->
    <div class="modal-dialog modal-lg animate__animated animate__fadeIn" style="display: none" id="msgframedel">
        <div class="alert alert-success" role="alert">
            <strong id="msgdel"></strong>
        </div>
    </div>
    <!--end-->
    <div class="container d-flex flex-column animate__animated animate__fadeIn">
        <div class="align-items-center justify-content-center" style="padding-top: 20px;">
            <div class="card hover-translate-y-n10">
                <div class="card-body">

                    <h4>已录入课程
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                data-target="#exampleModal" style="float: right">
                            添加课程
                        </button>
                    </h4>
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th style="text-align: center;font-size: medium;">#</th>
                            <th style="text-align: center;font-size: medium;">课程</th>
                            <th style="text-align: center;font-size: medium;">科目代码</th>
                            <th style="text-align: center;font-size: medium;">任课老师</th>
                            <th style="text-align: center;font-size: medium;">
                                <div data-toggle="tooltip" data-placement="top" title="该课程和课程里的所有学生和成绩都将被删除">操作<i
                                            data-feather="alert-triangle"></i></div>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $course_data = array();
                        $course_data = connect_mysql()->query("SELECT * FROM `php_sms`.gradestable WHERE student_uid IS NULL")->fetch_all(MYSQLI_ASSOC);
                        $course_data_count = count($course_data);
                        for ($i = 0; $i < $course_data_count; $i++) {
                            echo "<tr id='" . $course_data[$i]['subject_code'] . '0' . "'>";
                            echo "<td style='text-align: center;font-size: medium;'>" . ($i + 1) . "</td>";
                            echo "<td style='text-align: center;font-size: medium;'>" . $course_data[$i]['subject'] . "</td>";
                            echo "<td style='text-align: center;font-size: medium;'>" . $course_data[$i]['subject_code'] . "</td>";
                            echo "<td style='text-align: center;font-size: medium;'>" . $course_data[$i]['teacher'] . "</td>";
                            echo "<td style='text-align: center;font-size: medium;'><button type='button' data-toggle='modal' data-target='#modal_5' class='btn btn-danger btn-sm' id='" . $course_data[$i]['subject_code'] . "' onclick='deleteCourse(event)'>删除</button></td>";
                            echo "</tr>";
                        }
                        ?>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
    <!--管理框-->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">新建课程</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!--输入组-->
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i data-feather="clipboard"></i></span>
                            </div>
                            <input type="text" class="form-control" id="subjectName" placeholder="课程名">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i data-feather="terminal"></i></span>
                            </div>
                            <input type="text" class="form-control" id="subjectCode" maxlength="10" placeholder="课程代号"
                                   onKeyUp="value=value.replace(/[\W]/g,'')">
                            <div class="input-group-append" data-toggle="tooltip" data-placement="top"
                                 title="由1-10位字母数字组成">
                                <span class="input-group-text"><i data-feather="help-circle"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <select class="custom-select" id="teacher">
                                <option selected>选择教师</option>
                                <?php
                                $teacher_data = array();
                                $teacher_data = connect_mysql()->query("SELECT * FROM `php_sms`.teacher")->fetch_all(MYSQLI_ASSOC);
                                $teacher_data_count = count($teacher_data);
                                for ($i = 0; $i < $teacher_data_count; $i++) {
                                    echo "<option value='" . $teacher_data[$i]['id'] . "'>" . $teacher_data[$i]['id'] . $teacher_data[$i]['name'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="" role="alert" id="info" style="display: none;text-align: center"></div>
                    <!--end-->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">关闭</button>
                    <button type="button" class="btn btn-primary" id="newCourse" onclick="crtSubject()">提交</button>
                    <button type="button" class="btn btn-primary btn-sm" id="loading"
                            style="display: none;" disabled>
                        <div class="spinner-border text-secondary" role="status"></div>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!--end-->
    <!--确认删除框-->
    <div class="modal modal-danger fade" id="modal_5" tabindex="-1" role="dialog" aria-labelledby="modal_5"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h6" id="modal_title_6">这个行为很危险!</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="py-3 text-center">
                        <i class="fas fa-exclamation-circle fa-4x"></i>
                        <h5 class="heading h4 mt-4">您确定要继续删除课程？</h5>
                        <h6 class="heading h6 mt-4">删除该课程将会:</h6>
                        <p style="text-align: left"> 1.该课程被删除<br>
                            2.课程里的所有学生成绩将被清空</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-success" data-dismiss="modal">取消</button>
                    <button id="deleteAcc" type="button" class="btn btn-sm btn-warning" data-dismiss="modal"
                            onclick="deleteSub(event)">仍要删除
                    </button>
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

        function crtSubject() {
            var subjectName = document.getElementById("subjectName");
            var subjectCode = document.getElementById("subjectCode");
            var selectTeacher = $("#teacher option:selected").val();
            var info = document.getElementById("info");
            var loading = document.getElementById("loading");
            var newCourse = document.getElementById("newCourse");
            info.style.display = "none";
            newCourse.style.display = "none";
            loading.style.display = "block";
            $.ajax({
                url: "bs_admin3crt.php",
                type: "post",
                data: {
                    subjectName: subjectName.value,
                    subjectCode: subjectCode.value,
                    selectTeacher: selectTeacher
                },
                success: function (data) {
                    if (data.status === "success") {
                        info.style.display = "block";
                        info.className = "alert alert-success animate__animated animate__fadeIn";
                        info.innerHTML = data.message;
                        setTimeout(function () {
                            window.location.reload();
                        }, 1000);
                    } else if (data.status === "warning") {
                        info.style.display = "block";
                        info.className = "alert alert-warning animate__animated animate__fadeIn";
                        info.innerHTML = data.message;
                        newCourse.style.display = "block";
                        loading.style.display = "none";
                    } else {
                        info.style.display = "block";
                        info.className = "alert alert-danger animate__animated animate__fadeIn";
                        info.innerHTML = data.message;
                        newCourse.style.display = "block";
                        loading.style.display = "none";
                    }
                }
            })
        }

        //课程码赋给确认删除框
        function deleteCourse(ev) {
            var subjectCode = ev.target.id;
            var deleteAcc = document.getElementById("deleteAcc");
            deleteAcc.setAttribute("name", subjectCode);
        }

        function deleteSub(ev) {
            var subjectCode = ev.target.name;
            var msgframedel = document.getElementById("msgframedel");
            var msgdel = document.getElementById("msgdel");
            var tmp = subjectCode*10;
            var delele = document.getElementById(tmp);
            $.ajax({
                url: "bs_admin3del.php",
                type: "post",
                data: {
                    subjectCode: subjectCode
                },
                success: function (data) {
                    if (data.status === "success") {
                        msgframedel.style.display = "block";
                        msgdel.className = "alert alert-success shadow-lg animate__animated animate__fadeIn";
                        msgdel.innerHTML = data.message;
                        setTimeout(function () {
                            delele.hidden = true;
                            setTimeout(function () {
                                msgframedel.style.display = "none";
                            }, 800);
                        }, 800);
                    } else if (data.status === "error") {
                        msgframedel.style.display = "block";
                        msgdel.className = "alert alert-danger shadow-lg animate__animated animate__fadeIn";
                        msgdel.innerHTML = data.message;
                    }
                }
            })
        }
    </script>
    </body>
    </html>
<?php

?>