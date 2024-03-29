<?php

require_once __DIR__ . './connect.php';

class DaoCommande extends ConnectSuperClass {
    const TABLE_NAME = "commande";
    public static function connect(){
        $hote = 'localhost';
        $nom_bdd = "webservices";
        $utilisateur = 'root';
        $mot_de_passe = "";
        $pdo = new PDO('mysql:host='.$hote.';dbname='.$nom_bdd,$utilisateur,$mot_de_passe,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }

    public static function find($idCommande){
        if($pdo = self::connect()){
            $requete = $pdo->prepare("SELECT * FROM ".self::TABLE_NAME. " WHERE `id_commande` = :id");
            $requete->bindParam(':id',$idCommande);
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
    public static function delete($idCommande){
        if($pdo = self::connect()){
            $requete = $pdo->prepare("DELETE FROM ".self::TABLE_NAME. " WHERE `id_commande` = :id");
            $requete->bindParam(':id',$idCommande);
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
        $condition = " WHERE id_commande = ".$id.";";
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

    public static function add($commande){
        $request = "INSERT INTO ".self::TABLE_NAME." (";
        $requestValue = "(";
		foreach ($commande as $key => $value) {
            $request = $request.$key.",";
            $requestValue = $requestValue.'"'.$value.'",';
		}
		$request = substr_replace($request ,"", -1);
		$requestValue = substr_replace($requestValue,"", -1);
        $request = $request.") VALUES $requestValue".");";
        $response = array(
            "status"=>"success",
            "message"=>"Commande inserted"  
        );
        
        if($pdo = self::connect()){
            $requete = $pdo->prepare($request);
            $requete->execute();
            $response["id"] = $pdo->lastInsertId();
            return $response;
        }
		return null;
    }

    public static function findCommandeProduit($idCommande){
        if($pdo = self::connect()){
            $requete = $pdo->prepare("SELECT id_produit FROM commande_produit WHERE id_commande = :id");
            $requete->bindParam(':id',$idCommande);
            $requete->execute();

            $produits = $requete->fetchAll(PDO::FETCH_ASSOC);

            $id_list = "(-1";
            for($i = 0; $i < count($produits); $i++) {
                $id_list = $id_list.",".$produits[$i]["id_produit"];
            }
            $id_list = $id_list.")";

            $requete2 = $pdo->prepare("SELECT p.*, f.nom as fournisseur, v.nom as ville FROM produit p left join fournisseur f on p.id_fournisseur = f.id_fournisseur left join ville v on f.id_ville = v.id_ville WHERE id_produit in ".$id_list);
            $requete2->execute();
            return $requete2->fetchAll(PDO::FETCH_ASSOC);
        }
        return null;
    }
}
?>