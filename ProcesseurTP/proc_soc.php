<?PHP
$fab = $_REQUEST["fab"];
$qry_db="SELECT socket, libelle, annee FROM socket WHERE fabricant ='$fab' ORDER BY socket ASC;";
include "./ress/connect.php";
//Requete SQL
$resultat = $connMysqli->query($qry_db);
echo json_encode($resultat->fetch_all());
$connMysqli->close();
?>
