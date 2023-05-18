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
            <h4 style="padding-top: 10px;">教务系统-学生管理</h4>

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
                            <a href="#" class="dropdown-item">学生管理</a>
                            <a href="admin2.php" class="dropdown-item">教师管理</a>
                            <a href="admin3.php" class="dropdown-item">课程管理</a>
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
    <div class="container d-flex flex-column animate__animated animate__fadeIn">
        <div class="align-items-center justify-content-center" style="padding-top: 20px;">
            <div class="card hover-translate-y-n10">
                <div class="card-body">
                    <h4>录入/修改信息申请</h4>

                    <div id="opinfo"></div>

                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th style="text-align: center;font-size: medium;">UID</th>
                            <th style="text-align: center;font-size: medium;">姓名</th>
                            <th style="text-align: center;font-size: medium;">专业</th>
                            <th style="text-align: center;font-size: medium;">班级</th>
                            <th style="text-align: center;font-size: medium;">操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $applyData = array();
                        $applyData = connect_mysql()->query("SELECT * FROM `php_sms`.studenttmp WHERE `tag` = '0'")->fetch_all(MYSQLI_ASSOC);
                        $applyDataCount = count($applyData);
                        if ($applyDataCount == 0) {
                            echo "<tr><td colspan='8' style='text-align: center;font-size: medium;'>暂无学生申请</td></tr>";
                        } else {
                            for ($i = 0; $i < $applyDataCount; $i++) {
                                echo "<tr>";
                                echo "<td style='text-align: center;font-size: medium;'>" . $applyData[$i]['id'] . "</td>";
                                echo "<td style='text-align: center;font-size: medium;'><del>" . $applyData[$i]['priname'] . "</del> -> <span style='color: #0f5da2'>" . $applyData[$i]['name'] . "</span></td>";
                                echo "<td style='text-align: center;font-size: medium;'><del>" . $applyData[$i]['primajor'] . "</del> -> <span style='color: #0f5da2'>" . $applyData[$i]['major'] . "</span></td>";
                                echo "<td style='text-align: center;font-size: medium;'><del>" . $applyData[$i]['priclass'] . "</del> -> <span style='color: #0f5da2'>" . $applyData[$i]['class'] . "</span></td>";
                                echo "<td style='text-align: center;font-size: medium;'><button type='button' class='btn btn-danger btn-sm mb-3' onclick='denyApply(" . $applyData[$i]['id'] . ")'>拒绝</button><button type='button' class='btn btn-success btn-sm mb-3' onclick='accApply(" . $applyData[$i]['id'] . ")'>同意</button></td>";
                                echo "</tr>";
                            }
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="container d-flex flex-column animate__animated animate__fadeIn">
        <div class="align-items-center justify-content-center" style="padding-top: 20px;">
            <div class="card hover-translate-y-n10">
                <div class="card-body">

                    <div id="accordion-2" class="accordion accordion-spaced">
                        <!-- Accordion card 1 -->
                        <div class="card">
                            <div class="card-header py-4" id="heading-2-1" data-toggle="collapse" role="button"
                                 data-target="#collapse-2-1" aria-expanded="false" aria-controls="collapse-2-1">
                                <h6 class="mb-0"><i data-feather="user-minus" class="mr-3"></i>未录入信息的学生</h6>
                            </div>
                            <div id="collapse-2-1" class="collapse" aria-labelledby="heading-2-1"
                                 data-parent="#accordion-2">
                                <div class="card-body">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th style="text-align: center;font-size: medium;">#</th>
                                            <th style="text-align: center;font-size: medium;">UID</th>
                                            <th style="text-align: center;font-size: medium;">账户</th>
                                            <th style="text-align: center;font-size: medium;">姓名</th>
                                            <th style="text-align: center;font-size: medium;">姓别</th>
                                            <th style="text-align: center;font-size: medium;">专业</th>
                                            <th style="text-align: center;font-size: medium;">班级</th>
                                            <th style="text-align: center;font-size: medium;">注册时间</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $studentNullData = array();
                                        $studentNullData = connect_mysql()->query("SELECT * FROM `php_sms`.student WHERE `major` = '未录入'")->fetch_all(MYSQLI_ASSOC);
                                        $studentNullDataCount = count($studentNullData);
                                        if ($studentNullDataCount == 0) {
                                            echo "<tr><td colspan='8' style='text-align: center;font-size: medium;'>暂无数据</td></tr>";
                                        } else {
                                            for ($i = 0;
                                                 $i < $studentNullDataCount;
                                                 $i++) {
                                                echo "<tr>";
                                                echo "<td style='text-align: center;font-size: medium;'>" . ($i + 1) . "</td>";
                                                echo "<td style='text-align: center;font-size: medium;'>" . $studentNullData[$i]['id'] . "</td>";
                                                echo "<td style='text-align: center;font-size: medium;'>" . $studentNullData[$i]['account'] . "</td>";
                                                echo "<td style='text-align: center;font-size: medium;'>" . $studentNullData[$i]['name'] . "</td>";
                                                echo "<td style='text-align: center;font-size: medium;'>" . $studentNullData[$i]['gender'] . "</td>";
                                                echo "<td style='text-align: center;font-size: medium;'>" . $studentNullData[$i]['major'] . "</td>";
                                                echo "<td style='text-align: center;font-size: medium;'>" . $studentNullData[$i]['class'] . "</td>";
                                                echo "<td style='text-align: center;font-size: medium;'>" . $studentNullData[$i]['create_time'] . "</td>";
                                                echo "</tr>";
                                            }
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- Accordion card 2 -->
                        <div class="card">
                            <div class="card-header py-4" id="heading-2-2" data-toggle="collapse" role="button"
                                 data-target="#collapse-2-2" aria-expanded="false" aria-controls="collapse-2-2">
                                <h6 class="mb-0"><i data-feather="user-plus" class="mr-3"></i>已录入信息的学生</h6>
                            </div>
                            <div id="collapse-2-2" class="collapse" aria-labelledby="heading-2-2"
                                 data-parent="#accordion-2">
                                <div class="card-body">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th style="text-align: center;font-size: medium;">#</th>
                                            <th style="text-align: center;font-size: medium;">UID</th>
                                            <th style="text-align: center;font-size: medium;">账户</th>
                                            <th style="text-align: center;font-size: medium;">姓名</th>
                                            <th style="text-align: center;font-size: medium;">姓别</th>
                                            <th style="text-align: center;font-size: medium;">专业</th>
                                            <th style="text-align: center;font-size: medium;">班级</th>
                                            <th style="text-align: center;font-size: medium;">注册时间</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $studentData = array();
                                        $studentData = connect_mysql()->query("SELECT * FROM `php_sms`.student WHERE `major` != '未录入'")->fetch_all(MYSQLI_ASSOC);
                                        $studentDataCount = count($studentData);
                                        if ($studentDataCount == 0) {
                                            echo "<tr><td colspan='8' style='text-align: center;font-size: medium;'>暂无数据</td></tr>";
                                        } else {
                                            for ($i = 0;
                                                 $i < $studentDataCount;
                                                 $i++) {
                                                echo "<tr>";
                                                echo "<td style='text-align: center;font-size: medium;'>" . ($i + 1) . "</td>";
                                                echo "<td style='text-align: center;font-size: medium;'>" . $studentData[$i]['id'] . "</td>";
                                                echo "<td style='text-align: center;font-size: medium;'>" . $studentData[$i]['account'] . "</td>";
                                                echo "<td style='text-align: center;font-size: medium;'>" . $studentData[$i]['name'] . "</td>";
                                                echo "<td style='text-align: center;font-size: medium;'>" . $studentData[$i]['gender'] . "</td>";
                                                echo "<td style='text-align: center;font-size: medium;'>" . $studentData[$i]['major'] . "</td>";
                                                echo "<td style='text-align: center;font-size: medium;'>" . $studentData[$i]['class'] . "</td>";
                                                echo "<td style='text-align: center;font-size: medium;'>" . $studentData[$i]['create_time'] . "</td>";
                                            }
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
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

        function accApply(uid) {
            var opinfo = document.getElementById("opinfo");
            opinfo.display = "none";
            $.ajax({
                url: "bs_admin_accApply.php",
                type: "POST",
                data: {
                    uid: uid
                },
                success: function (data) {
                    if (data.status === "success") {
                        opinfo.display = "block";
                        opinfo.className = "alert alert-success alert-dismissible animate__animated animate__fadeIn";
                        opinfo.role = "alert";
                        opinfo.style.textAlign = "center";
                        opinfo.innerHTML = data.message;
                        setTimeout(function () {
                            opinfo.className = "alert alert-success alert-dismissible animate__animated animate__fadeOut";
                            setTimeout(function () {
                                opinfo.className = "";
                                opinfo.innerHTML = "";
                                opinfo.display = "none";
                                window.location.reload();
                            },600)
                        }, 2000)
                    } else {
                        opinfo.className = "alert alert-warning alert-dismissible animate__animated animate__fadeIn";
                        opinfo.role = "alert";
                        opinfo.style.textAlign = "center";
                        opinfo.innerHTML = data.message;
                        opinfo.display = "block";
                        setTimeout(function () {
                            opinfo.className = "alert alert-success alert-dismissible animate__animated animate__fadeOut";
                            setTimeout(function () {
                                opinfo.className = "";
                                opinfo.innerHTML = "";
                                opinfo.display = "none";
                                window.location.reload();
                            },600)
                        }, 2000)
                    }
                }
            });
        }

        function denyApply(uid) {
            var opinfo = document.getElementById("opinfo");
            opinfo.display = "none";
            $.ajax({
                url: "bs_admin_denyApply.php",
                type: "POST",
                data: {
                    uid: uid
                },
                success: function (data) {
                    opinfo.display = "block";
                    if (data.status === "success") {
                        opinfo.className = "alert alert-success alert-dismissible fade show";
                        opinfo.role = "alert";
                        opinfo.style.textAlign = "center";
                        opinfo.innerHTML = data.message;
                        setTimeout(function () {
                            opinfo.className = "alert alert-success alert-dismissible animate__animated animate__fadeOut";
                            opinfo.className = "";
                            opinfo.innerHTML = "";
                            opinfo.display = "none";
                            window.location.reload();
                        }, 1000)
                    } else {
                        opinfo.display = "block";
                        opinfo.className = "alert alert-warning alert-dismissible fade show";
                        opinfo.role = "alert";
                        opinfo.style.textAlign = "center";
                        opinfo.innerHTML = data.message;
                        setTimeout(function () {
                            opinfo.className = "alert alert-warning alert-dismissible animate__animated animate__fadeOut";
                            opinfo.className = "";
                            opinfo.innerHTML = "";
                            opinfo.display = "none";
                        }, 1000)
                    }
                }
            });
        }
    </script>
    </body>
    </html>
<?php

?>