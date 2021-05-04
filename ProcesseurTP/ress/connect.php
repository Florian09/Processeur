<?php
$host="localhost";
$user="mysql";
$bdd="processeur";
$passwd="azerty";
// Quelle API Choisir voir https://www.php.net/manual/fr/mysqlinfo.api.choosing.php
$connMysqli = new mysqli($host,$user,$passwd,$bdd);
if ($connMysqli->connect_errno) {
    echo "Erreur : " . $connMysqli->connect_errno . "=" . $connMysqli->connect_error . "\n";
    exit('connection impossible');
}
else $connMysqli->set_charset('utf8');
?>
