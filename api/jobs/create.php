<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-With');
include_once '../../config/db.php';
include_once '../../models/Jobs.php';

$database = new Database();
$db = $database->connect();
$job = new Jobs($db);
$data = json_decode(file_get_contents("php://input"));
if(isset($data->id)){
    $job->id = $data->id;
    $job->getJobId();
    if(empty($job->id)){
        echo json_encode(array('message'=>'Job id not found'));
    }else{
        if($job->status=='completed'){
            echo json_encode(array('message'=>'Job comppleted cant be updated'));
        }else{
            $job->idSub = $data->submitter;
            $job->idPro = $data->processor;
            $job->comm = $data->command;
            $res = $job->update();
            if($res===true){
                echo json_encode(array('message'=>'Job updated'));
            }else{
                echo json_encode(array('message'=>$res));
            }
        }
    }
}else{
    $job->idSub = $data->submitter;
    $job->idPro = $data->processor;
    $job->comm = $data->command;
    $res = $job->create();
    if(is_int($res)){
        echo json_encode(array('message'=>'Job created',"id"=>$res));
    }else{
        echo json_encode(array('message'=>$res));
    }
}

