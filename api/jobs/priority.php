<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/db.php';
include_once '../../models/Jobs.php';

$database = new Database();
$db = $database->connect();
$job = new Jobs($db);
$proc = isset($_GET['processor'])? intval($_GET['processor']):die();
$result = $job->priority();
$rowcount = $result->rowCount();
if($rowcount>0){
    $job_arr = array();

    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $job_item = array(
            'id' => intval($id),
            'submitter' => intval($idSub),
            'command' => $comm
        );
    }
    $job->updateStateProc($job_item['id'],0,$proc);
    echo json_encode($job_item);
}else{
    echo json_encode(
        array('message'=>'No pending jobs found')
    );
}
