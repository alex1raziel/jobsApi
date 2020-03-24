<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/db.php';
include_once '../../models/Jobs.php';

$database = new Database();
$db = $database->connect();
$job = new Jobs($db);
$job->id = isset($_GET['id']) ? $_GET['id']:die();
$job->getJobId();
if(empty($job->id)){
    print_r(json_encode(array('message'=>'Job not found')));
}else{
    $singleJob = array(
        'id' => $job->id,
        'submitter' => $job->idSub,
        'processor' => $job->idPro,
        'command' => $job->comm,
        'status' => $job->status
    );
    print_r(json_encode($singleJob));
}
