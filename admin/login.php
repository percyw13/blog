<?php
session_start();

include('../config/config.php');
include('../lib/bdd.lib.php');

//userIsConnected();

$vue='login.phtml';
$title = 'Connexion';
$erreur = '';

$log = [
    "mail" => '',
    "password" => ''
];

try
{
    
    if(array_key_exists('mail',$_POST))
    {         
        $log = [
            "mail" => $_POST['mail'],
            "password" => $_POST['password']
        ];     
        
        
                
    /** 1 : connexion au serveur de BDD - SGBDR */
        $dbh = connexion();
                
    /**2 : Prépare ma requête SQL */
        $sth = $dbh->prepare('SELECT *  FROM auteur WHERE aut_email = :mail');
                
    /** 3 : executer la requête */
        $sth->execute(['mail' => $log['mail']]); 

    /** 4 : recupérer les résultats 
     * On utilise FETCH car un seul résultat attendu
    */
        $results = $sth->fetch(PDO::FETCH_ASSOC);
            
        //password_verify ( string $password , string $hash )
                
        if($results != false && password_verify ($log['password'], $results['aut_password'])){

            $_SESSION['connect'] = true;
            $_SESSION['user'] = ['nom'=>$results['aut_name'],'id'=>$results['aut_id']];
            
        } else{
            $erreur = 'La connexion est impossible';
            userIsConnected();
        }
    }
}
      

catch(PDOException $e)
{
    $vue = 'erreur.phtml';
    //Si une exception est envoyée par PDO (exemple : serveur de BDD innaccessible) on arrive ici
    $erreur =  'Une erreur de connexion a eu lieu :'.$e->getMessage();
}

include('tpl/layout.phtml');