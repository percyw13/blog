<?php
session_start();

include('../config/config.php');
include('../lib/bdd.lib.php');

$vue='login.phtml';
$title = 'Connexion';

$log = [
    "mail" => '',
    "password" => ''
];

try
{
    
    if(array_key_exists('mail',$_POST))
    {
        
            
                
                /** 1 : connexion au serveur de BDD - SGBDR */
                $dbh = connexion();
                
                /**2 : Prépare ma requête SQL */
                $sth = $dbh->prepare('SELECT aut_email FROM auteur WHERE email= :email;
                
                /** 3 : executer la requête */
                $sth->execute($log); // j'éxécute en envoyant mon tableau
                
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