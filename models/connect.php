<?php
class ConnectSuperClass {
    public static function connect(){
        $hote = 'localhost';
        $nom_bdd = "webservices";
        $utilisateur = 'root';
        $mot_de_passe = "";
        $pdo = new PDO('mysql:host='.$hote.';dbname='.$nom_bdd,$utilisateur,$mot_de_passe,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }
}
?>