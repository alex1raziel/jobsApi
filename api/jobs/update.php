<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-With');
include_once '../../config/db.php';
include_once '../../models/Jobs.php';

$database = new Database();
$db = $database->connect();
$job = new Jobs($db);
$data = json_decode(file_get_contents("php://input"));
$id = $data->id;
$status = $data->status;
$res = $job->updateState($id,$status);
if($res===true){
    echo json_encode(array('message'=>'Job updated'));
}else{
    echo json_encode(array('message'=>$res));
}
