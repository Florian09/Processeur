<?PHP
$socket = $_REQUEST["socket"];
$qry_db="SELECT * FROM processeur WHERE socket = '$socket';";
include "./ress/connect.php";
//Requete SQL
$resultat = $connMysqli->query($qry_db);
echo json_encode($resultat->fetch_all());
$connMysqli->close();
?>
