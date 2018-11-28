<?php
session_start();

include('../config/config.php');
include('../lib/bdd.lib.php');

userIsConnected();


$vue='addUser.phtml';
$title = 'Ajouter un utilisateur';

//Initialisation des erreurs à false
$erreur = '';

//Tableau correspondant aux valeurs à récupérer dans le formulaire.
$values = [        
    "name" => '',
    "firstName" => '',
    "mail" => '',
    "password" => '',
    "bio" => '',
    "avatar" => '',
    "userName" => '',
    "role" => ''
];     

$tab_erreur =
[
'nom'=>'Le nom doit être rempli !',
'prenom'=>'Le prénom doit être rempli !',
'email'=>'L\'email doit être rempli !',
'password'=>'Le mot de passe ne peut être vide'
];

try
{
    
    if(array_key_exists('nom',$_POST))
    {
        foreach($values as $champ => $value)
        {
            if(isset($_POST[$champ]) && trim($_POST[$champ])!='')
                $values[$champ] = $_POST[$champ];
            elseif(isset($tab_erreur[$champ]))   
                $erreur.= '<br>'.$tab_erreur[$champ];
            else
                $values[$champ] = '';
        }

        if($_POST['password'] != $_POST['confirm'])
            $erreur.= '<br> Erreur confirmation mot de passe';

        if(!filter_var($_POST['mail'],FILTER_VALIDATE_EMAIL))
            $erreur.= '<br> Email erroné !';

        if($erreur =='')
        {
            $values['password'] = password_hash($_POST['password'],PASSWORD_DEFAULT);
            
                
                /** 1 : connexion au serveur de BDD - SGBDR */
                $dbh = connexion();
                
                /**2 : Prépare ma requête SQL */
                $sth = $dbh->prepare('INSERT INTO auteur VALUES (NULL, :name, :firstName, :mail, :password, :bio, :avatar, :userName, :role)');
                
                /** 3 : executer la requête */
                $sth->execute($values); // j'éxécute en envoyant mon tableau
                
        }
            
    }
}
      

catch(PDOException $e)
{
    $vue = 'erreur.phtml';
    //Si une exception est envoyée par PDO (exemple : serveur de BDD innaccessible) on arrive ici
    $messageErreur =  'Une erreur de connexion a eu lieu :'.$e->getMessage();
}

include('tpl/layout.phtml');
