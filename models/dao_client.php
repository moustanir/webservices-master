<?php

require_once __DIR__ . './connect.php';

class DaoClient extends ConnectSuperClass {
    const TABLE_NAME = "client";
    
    public static function find($idUser){
        if($pdo = self::connect()){
            $requete = $pdo->prepare("SELECT c.*, v.nom as ville FROM ".self::TABLE_NAME. " c left join ville v on c.id_ville = v.id_ville WHERE `id_client` = :id");
            $requete->bindParam(':id',$idUser);
            $requete->execute();
            return $requete->fetch(PDO::FETCH_ASSOC);
        }
        return null;
    }
    public static function findAll(){
        if($pdo = self::connect()){
            $requete = $pdo->prepare("
                SELECT 
                    c.*, 
                    v.nom as ville,
                    (
                        SELECT
                            count(*)
                        FROM
                            commande
                        WHERE
                            id_client = c.id_client
                    ) as nbCommande
                FROM 
                    client c 
                    left join ville v on c.id_ville = v.id_ville");
            $requete->execute();
            return $requete->fetchAll(PDO::FETCH_ASSOC);
        }
        return null;        
    }
    public static function delete($idUser){
        if($pdo = self::connect()){
            $requete = $pdo->prepare("DELETE FROM ".self::TABLE_NAME. " WHERE `id_client` = :id");
            $requete->bindParam(':id',$idUser);
            $requete->execute();
            return array(
                "status"=>"success",
                "message"=>"User deleted"  
            );
        }
        return null;
    }

    public static function update($id,$fields){
        
        if(count($fields) < 0 ){
            return array("status"=>"success","message" => "Aucun champs modifiés");
        }
        if(empty($id)){
            return array("status"=>"error","message" => "Id params null.Cannot update");
        }
        $request = "UPDATE".self::TABLE_NAME." SET ";
        $condition = " WHERE id_client = ".$id.";";
        foreach($fields as $field => $value){
            if(!empty($value)){
                $request = $request." $field='$value',";
            }
        }
        $request = substr_replace($request ,"", -1);
        $request = $request.$condition;
        if($pdo = self::connect()){
            $requete = $pdo->prepare($request);
            $requete->execute();
            return array(
                "status"=>"success",
                "message"=>"User updated",
                "id"=>$id
            );
        }
        return null;
    }

    public static function add($user){
        $request = "INSERT INTO ".self::TABLE_NAME." (";
        $requestValue = "(";
		foreach ($user as $key => $value) {
            $request = $request.$key.",";
            $requestValue = $requestValue.'"'.$value.'",';
		}
		$request = substr_replace($request ,"", -1);
		$requestValue = substr_replace($requestValue,"", -1);
        $request = $request.") VALUES $requestValue".");";
        $response = array(
            "status"=>"success",
            "message"=>"User inserted"  
        );
        
        if($pdo = self::connect()){
            $requete = $pdo->prepare($request);
            $requete->execute();
            $response["id"] = $pdo->lastInsertId();
            return $response;
        }
		return null;
    }

    public static function findUserCommande($idUser){
        if($pdo = self::connect()){
            $requete = $pdo->prepare("SELECT * FROM "."commande"." WHERE `id_client` = :id");
            $requete->bindParam(':id',$idUser);
            $requete->execute();
            return $requete->fetchAll(PDO::FETCH_ASSOC);
        }
        return null;
    }
}