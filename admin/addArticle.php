<?php
session_start();

include('../config/config.php');
include('../lib/bdd.lib.php');

userIsConnected();


$vue='addArticle.phtml';
$title = 'Ajouter un article';

//Initialisation des erreurs à false
$erreur = '';

//Tableau correspondant aux valeurs à récupérer dans le formulaire.
$values = [        
    "publishDate" => '',
    "userId" => $_SESSION['user']['id'],
    "title" => '',
    "content" => '',
    "image" => '',
    "tag" => '',
    "statut" => ''
]; 

/** 1 : connexion au serveur de BDD - SGBDR */
$dbh = connexion();
            
/**2 : Prépare ma requête SQL */
$sth = $dbh->prepare('SELECT * FROM categorie');

/** 3 : executer la requête */
$sth->execute(); // j'éxécute en envoyant mon tableau

$categories = $sth->fetchAll(PDO::FETCH_ASSOC);

var_dump($categories);

try{
    if(array_key_exists('title',$_POST)){
        
        foreach($values as $champ => $value)
        {
            
            if(isset($_POST[$champ]) && trim($_POST[$champ])!='')
            $values[$champ] = $_POST[$champ];
            elseif(isset($tab_erreur[$champ]))   
            $erreur.= '<br>'.$tab_erreur[$champ];
            //else
            //$values[$champ] = '';
        }
        $values['publishDate'] = date('Y-m-d h:i:s');
        
        $uploads_dir = '../uploads/users';
        
        if($_FILES["image"]["error"] == UPLOAD_ERR_OK){
            $tmp_name = $_FILES["image"]["tmp_name"];
            // basename() peut empêcher les attaques de système de fichiers;
                // la validation/assainissement supplémentaire du nom de fichier peut être approprié
                $name = basename($_FILES["image"]["name"]);
                move_uploaded_file($tmp_name, "$uploads_dir/$name");
            }
            
        $values['image'] = $_FILES['image']['name'];
        
        
            /** 1 : connexion au serveur de BDD - SGBDR */
            $dbh = connexion();
            
            /**2 : Prépare ma requête SQL */
            $sth = $dbh->prepare('INSERT INTO article VALUES (NULL,:userId, :publishDate, :title, :content, :image, :tag, :statut)');
            
            /** 3 : executer la requête */
            $sth->execute($values); // j'éxécute en envoyant mon tableau

        }
}

catch(PDOException $e)
{
    //$vue = 'erreur.phtml';
    //Si une exception est envoyée par PDO (exemple : serveur de BDD innaccessible) on arrive ici
    $erreur =  'Une erreur de connexion a eu lieu :'.$e->getMessage();
}

include('tpl/layout.phtml');