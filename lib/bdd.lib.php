<?php

function connexion()
{
    $dbh = new PDO(DB_DSN,DB_USER,DB_PASS);
    //On dit à PDO de nous envoyer une exception s'il n'arrive pas à se connecter ou s'il rencontre une erreur
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    return $dbh;
}

function userIsConnected()
{
    if(!isset($_SESSION['connect']) || $_SESSION['connect'] != true)
    {
        header('Location:login.php');
        exit();
    }
}