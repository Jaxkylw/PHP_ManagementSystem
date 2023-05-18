<?php
function connect_mysql(){
    $servername = "localhost";
    $username = "root";
    $password = "root";
    return new mysqli($servername, $username, $password);
}
