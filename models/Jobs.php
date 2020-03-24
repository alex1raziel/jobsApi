<?php

class Jobs{
    private $conn;

    public $id;
    public $idSub;
    public $idPro;
    public $comm;
    public $status;

    public function __construct($db){
        $this->conn = $db;
    }

    public function priority(){
        $query = 'select id,submitter as idSub,processor as idPro,command as comm from jobs where status=-1 order by id asc limit 1';
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function getJobId(){
        $query = 'select id,submitter as idSub,processor as idPro,command as comm,status as status from jobs where id=?';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$this->id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->id = intval($row['id']);
        $this->idSub = intval($row['idSub']);
        $this->idPro = intval($row['idPro']);
        $this->comm = $row['comm'];
        switch($row['status']){
            case '1':
                $this->status = 'completed';
                break;
            case '-1':
                $this->status = 'pending';
                break;
            case '0':
                $this->status = 'processing';
                break;
        }
    }

    public function create(){
        $query = 'INSERT INTO JOBS SET submitter=:idSub,processor=:idPro,command=:comm,status=:status,startPro=:start';
        $stmt = $this->conn->prepare($query);
        $this->comm = htmlspecialchars(strip_tags($this->comm));
        if(is_int($this->idSub)){
            $stmt->bindParam(':idSub',$this->idSub);
        }else{
            return 'submitter must be int';
        }
        if(is_int($this->idPro) && $this->idPro!=null){
            $stmt->bindParam(':idPro',$this->idPro);
        }else{
            if($this->idPro==null){
                $stmt->bindValue(':idPro',null);
            }else{
                return 'processor must be int';
            }
        }
        $stmt->bindParam(':comm',$this->comm);
        if($this->idPro==null){
            $stmt->bindValue(':status','-1');
            $stmt->bindValue(':start',null);
        }else{
            $stmt->bindValue(':status','0');
            $date = date('Y-m-d H:i:s');
            $stmt->bindParam(':start',$date);
        }
        if($stmt->execute()){
            $id = $this->conn->lastInsertId();
            return intval($id);
        }else{
            return 'DataBase error';
        }
    }

    public function update(){
        $query = 'UPDATE JOBS SET submitter=:idSub,processor=:idPro,command=:comm,status=:status,startPro=:start where id=:id';
        $stmt = $this->conn->prepare($query);
        $this->comm = htmlspecialchars(strip_tags($this->comm));
        if(is_int($this->id)){
            $stmt->bindParam(':id',$this->id);
        }else{
            return 'id must be int';
        }
        if(is_int($this->idSub)){
            $stmt->bindParam(':idSub',$this->idSub);
        }else{
            return 'submitter must be int';
        }
        if(is_int($this->idPro) && $this->idPro!=null){
            $stmt->bindParam(':idPro',$this->idPro);
        }else{
            if(empty($this->idPro)){
                $stmt->bindValue(':idPro',null);
            }else{
                return 'processor must be int';
            }
        }
        $stmt->bindParam(':comm',$this->comm);
        if($this->idPro==null){
            $stmt->bindValue(':status','-1');
            $stmt->bindValue(':start',null);
        }else{
            $stmt->bindValue(':status','0');
            $date = date('Y-m-d H:i:s');
            $stmt->bindParam(':start',$date);
        }
        if($stmt->execute()){
            return true;
        }else{
            return 'DataBase error';
        }
    }

    public function updateState($id,$status){
        if($status==1){
            $query = 'UPDATE JOBS SET status=:status,endPro=:end where id=:id';
        }else{
            $query = 'UPDATE JOBS SET status=:status where id=:id';
        }
        $stmt = $this->conn->prepare($query);
        if(is_int($id)){
            $stmt->bindParam(':id',$id);
        }else{
            return 'id must be int';
        }
        if(is_int($status)){
            $stmt->bindParam(':status',$status);
        }else{
            return 'status must be int';
        }
        if($status==1){
            $date = date('Y-m-d H:i:s');
            $stmt->bindParam(':end',$date);
        }
        if($stmt->execute()){
            return true;
        }else{
            return 'DataBase error';
        }
    }

    public function updateStateProc($id,$status,$proc){
        $query = 'UPDATE JOBS SET status=:status,processor=:proc,startPro=:start where id=:id';
        $stmt = $this->conn->prepare($query);
        if(is_int($id)){
            $stmt->bindParam(':id',$id);
        }else{
            return 'id must be int';
        }
        if(is_int($status)){
            $stmt->bindParam(':status',$status);
        }else{
            return 'status must be int';
        }
        if(is_int($proc)){
            $stmt->bindParam(':proc',$proc);
        }else{
            return 'processor must be int';
        }
        $date = date('Y-m-d H:i:s');
        $stmt->bindParam(':start',$date);
        if($stmt->execute()){
            return true;
        }else{
            return 'DataBase error';
        }
    }

    public function time(){
        $query = 'select AVG(TIMESTAMPDIFF(SECOND,startPro,endPro)) as diff from jobs where endPro is not null;';
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return 'the tasks are being processed in an average time of '.$row['diff'].' seconds';
    }
}
