<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/db.php';
include_once '../../models/Jobs.php';

$database = new Database();
$db = $database->connect();
$job = new Jobs($db);
$res = $job->time();
print_r(json_encode(array('message'=>$res)));
