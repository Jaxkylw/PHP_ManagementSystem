<?php
header('Content-Type: application/json');
session_start();
$subject_code = $_POST['subject_code'];
$_SESSION['subjectCode'] = $subject_code;
echo json_encode($_SESSION['subjectCode']);
?>