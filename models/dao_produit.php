<?php

require_once __DIR__ . './connect.php';

class DaoProduit extends ConnectSuperClass {
    const TABLE_NAME = "produit";
    public static function connect(){
        $hote = 'localhost';
        $nom_bdd = "webservices";
        $utilisateur = 'root';
        $mot_de_passe = "";
        $pdo = new PDO('mysql:host='.$hote.';dbname='.$nom_bdd,$utilisateur,$mot_de_passe,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }

    public static function find($idProduit){
        if($pdo = self::connect()){
            $requete = $pdo->prepare("SELECT * FROM ".self::TABLE_NAME. " WHERE `id_produit` = :id");
            $requete->bindParam(':id',$idProduit);
            $requete->execute();
            return $requete->fetch(PDO::FETCH_ASSOC);
        }
        return null;
    }
    public static function findAll(){
        if($pdo = self::connect()){
            $requete = $pdo->prepare("SELECT * FROM ".self::TABLE_NAME);
            $requete->execute();
            return $requete->fetchAll(PDO::FETCH_ASSOC);
        }
        return null;        
    }
    public static function delete($idProduit){
        if($pdo = self::connect()){
            $requete = $pdo->prepare("DELETE FROM ".self::TABLE_NAME. " WHERE `id_produit` = :id");
            $requete->bindParam(':id',$idProduit);
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
        $condition = " WHERE id_produit = ".$id.";";
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
                "message"=>"Commande updated",
                "id"=>$id
            );
        }
        return null;
    }

    public static function add($produit){
        $request = "INSERT INTO ".self::TABLE_NAME." (";
        $requestValue = "(";
		foreach ($produit as $key => $value) {
            $request = $request.$key.",";
            $requestValue = $requestValue.'"'.$value.'",';
		}
		$request = substr_replace($request ,"", -1);
		$requestValue = substr_replace($requestValue,"", -1);
        $request = $request.") VALUES $requestValue".");";
        $response = array(
            "status"=>"success",
            "message"=>"Produit inserted"  
        );
        
        if($pdo = self::connect()){
            $requete = $pdo->prepare($request);
            $requete->execute();
            $response["id"] = $pdo->lastInsertId();
            return $response;
        }
		return null;
    }
}
?>