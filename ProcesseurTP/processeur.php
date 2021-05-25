<?php 
include("./ress/connect.php");
if(isset($_GET["fabricant"])) $fab=$_GET["fabricant"]; else $fab="AMD";
?>
<!DOCTYPE html>
<html lang="fr"><head>


	<script src="ress/js/Chart.min.js"></script>
	<script src="ress/js/utils.js"></script>



	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>Révison HTML CSS PHP</title>
	<link href="./ress/style.css" type="text/css" rel="stylesheet">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	</head>

<body onload="charge_soc(<?php echo"'".$fab."'";?>)">
	<header>
		<table>
			<tbody><tr>
				<td><img src="./ress/processor.png" alt="PROC"></td>
				<td>
					<h1>Selectionner un fabricant</h1>
				</td>
			</tr>
		</tbody></table>
	</header>
	<div style="margin: auto;min-width: 50%;max-width: 70%;min-height: 542px;">
		<form action="processeur.php" method="get">
		<h3>Fabricant : <select id="sel_fabricant" name="fabricant" onchange="charge_soc(this.value)">
			<option value="AMD" <?php if($fab == "AMD") echo " selected";?>>AMD</option>
			<option value="INTEL"<?php if($fab == "INTEL") echo " selected";?>>INTEL</option>
		</select>&nbsp;&nbsp;&nbsp;&nbsp;<cite id="out_fabricant"><?php echo aff_fab($fab);?></cite></h3>
		</form>
		<form action="processeur.php" method="get">
		<h3>socket : <select id="sel_socket" name="socket" onchange="charge_cpu(this.value)">
			<option value=""</option>
		</select>&nbsp;&nbsp;&nbsp;&nbsp;<cite id="out_socket">selectionner le socket</cite></h3>

		</form>
		<div style="margin:auto;overflow-y:auto;max-height: 420px;"><table style="margin:auto;padding:0;border:0;">
			<!--<thead>
				<tr class="scroll">
					<th>Nom commercial du microprocesseur</th>
					<th>Prix</th>
					<th>Score<br>Multi</th>
					<th>Score<br>Mono</th>
					<th>Conso<br>Watts</th>
					<th>Date<br>Sortie</th>
					<th>Utilisation</th>
				</tr>
			</thead>
			<tbody id="tbody_cpu">
<?php
/*
$sql = "SELECT * FROM processeur WHERE fabricant='$fab';";
$resultat = $connMysqli->query($sql);
if ($resultat->num_rows > 0) {
	// output data of each row
	while ( $ligne = $resultat->fetch_assoc()) {

		echo "<tr class=\"liste\" id=\"" . $ligne["reference"]."\"><td>".$ligne["libelle"]."</td><td>".$ligne["prix"]."</td><td>".$ligne["score_multi"].
		"</td><td>".$ligne["score_mono"]."</td><td>".$ligne["watt"]."</td><td>".$ligne["annee"]."</td><td>".$ligne["utilisation"]."</td></tr>";
	}
}*/
$connMysqli->close();
?>
</tbody>-->
		</table></div>
			<canvas id="chart-0"></canvas>
	</div>




	<footer>
		<table class="ft_100">
			<tbody><tr>
				<td class="td_ft">
					<img src="./ress/pingu_swing80.gif" usemap="#Back" alt="Page -1">
					<map name="Back">
						<area shape="rect" coords="0,0,32,32" alt="Page Précédente" title="Page -">
					</map>
				</td>
				<td class="td_ft">
					<img class="im_rg" src="./ress/pingu_swing80.gif" usemap="#next" alt="Page +1">
					<map name="next">
						<area shape="rect" coords="0,0,32,32" alt="Page Suivante" title="Page +">
					</map>
				</td>
			</tr>
		</tbody></table>
	</footer>
	<br>
	<script>
	var dataAjax ="";
	var tab_socket ="";

		function charge_soc(fabricant) {
	dataAjax="";
	var url_PHP = "proc_soc.php?fab="+fabricant;
	//requete pour recup le fichier
	var infoLu = new XMLHttpRequest();
	//on utilise une foncton sur l'evenement "onreadystatechnge"
	infoLu.onreadystatechange = function() {
		if (infoLu.readyState === 4 && infoLu.status === 200) {
			var opt_socket = "";

			dataAjax = infoLu.responseText;
			tab_socket = JSON.parse(dataAjax);
			tab_socket.forEach(element => {
				opt_socket += '<option value=' +element[0] + '">'+element[0]+'</option>';
			})
			document.getElementById("sel_socket").innerHTML = opt_socket;
			charge_cpu(document.getElementById("sel_socket").value);
		}
	}
	infoLu.open("GET", url_PHP, true);
	infoLu.send();
}

		function charge_cpu(socket) {
	dataAjax="";
	socket= socket.replace('"','');
	var url_PHP = "charge_cpu.php?socket="+socket;
	//requete pour recup le fichier
	var infoLu = new XMLHttpRequest();
	//on utilise une foncton sur l'evenement "onreadystatechnge"
	infoLu.onreadystatechange = function() {
		if (infoLu.readyState === 4 && infoLu.status === 200) {
			var tr_td_cpu = "";
			var i =0;
			var sel_socket = document.getElementById("sel_socket").value;
			var i = document.getElementById("sel_socket").selectedIndex;
			document.getElementById("out_socket").innerHTML = tab_socket[i][1];
			//charger et traiter le fichier
			dataAjax = infoLu.responseText;
			tab_processeur = JSON.parse(dataAjax);

			var TOUT = [
				//labels = [],
				//ScoreMulti = [],
				//ScoreMono = []


			];

			TOUT[0] = [
				[], // Emplacements des labels de tout les processeurs
				[], // Emplacements des Scores Multi de tout les processeurs
				[]  // Emplacements des Scores Mono de tout les processeurs
			];

			var LABEL = 0;//Index Tableau Stock LABEL
			var SCORE_MULTI = 1;//Index Tableau Stock SCORE MULTI
			var SCORE_MONO = 2;//Index Tableau Stock SCORE MONO

			tab_processeur.forEach(element => {
				tr_td_cpu += "<tr class=\"liste\" id=\""+element[0]+"\">\n";
				for (i= 0; i < 7; i++) {
					tr_td_cpu += "<td >"+element[i]+"</td>\n";
				}
				tr_td_cpu += "</tr>\n";

				var LabelLength = TOUT[0][LABEL].length;
				var MultiLength = TOUT[0][SCORE_MULTI].length;
				var MonoLength = TOUT[0][SCORE_MONO].length;

				TOUT[0][LABEL][LabelLength] = element[0];
				TOUT[0][SCORE_MULTI][MultiLength] = element[2];
				TOUT[0][SCORE_MONO][MonoLength] = element[3];

			});

			setChart(TOUT[0][LABEL], TOUT[0][SCORE_MULTI], TOUT[0][SCORE_MONO]);

/*
var __lab = [
	'Athlon 5150 APU',
	'Athlon 5350 APU',
	'Athlon 5370 APU',
	'Sempron 2650 APU',
	'Sempron 3850 APU'];
var __MulDats =  [159.95, 109.99, 63.70, 64.95, 72.10];
var __SinDats = [2112, 2611, 2915, 951, 1718];
setChart(__lab, __MulDats, __SinDats);
*/

			//document.getElementById("tbody_cpu").innerHTML = tr_td_cpu;
			}
	}
	infoLu.open("GET", url_PHP, true);
	infoLu.send();
	}
</script>
<script>

var BC =  [
		'rgba(255, 99, 132, 1)',
		'rgba(54, 162, 235, 1)',
		'rgba(255, 206, 86, 1)',
		'rgba(75, 192, 192, 1)',
		'rgba(153, 102, 255, 1)',
		'rgba(255, 159, 64, 1)'
];
var BGC = [
		'rgba(255, 99, 132, 0.2)',
		'rgba(54, 162, 235, 0.2)',
		'rgba(255, 206, 86, 0.2)',
		'rgba(75, 192, 192, 0.2)',
		'rgba(153, 102, 255, 0.2)',
		'rgba(255, 159, 64, 0.2)'
];


//Options de la barre Chart
var BarOption = {
		scales: {
			xAxes: [{ stacked: true }],
			yAxes: [{ stacked: true	}]
		}
};



var chart;
function setChart(_labels, __multiDatas, __singleDatas)
{

if (chart != undefined || chart !=null) {
    chart.destroy();//Empeche le bug de la souris dessus
}

/*[

		'rgba(255, 99, 132, 1)',
		'rgba(54, 162, 235, 1)',
		'rgba(255, 206, 86, 1)',
		'rgba(75, 192, 192, 1)',
		'rgba(153, 102, 255, 1)',
		'rgba(255, 159, 64, 1)'
]*/
BC = [];
BC1 = [];

BGC = [];
BGC1 = [];

var ColorBlue = '#1b1b1b'//'#67a9d5';
var ColorBlack = '#FF8C00'//'#67a9d5';

for (var r = 0; r < _labels.length; r+=1) {
	BC[BC.length] = ColorBlue;
	BC1[BC1.length] = ColorBlack;

	BGC[BGC.length] = ColorBlack;
	BGC1[BGC1.length] =ColorBlue ;

}


	chart = new Chart('chart-0', {
	    type: 'horizontalBar',
	    data: {
			labels: _labels,
	        datasets: [

						{
		            label: '# Multi Core',
		            data: __multiDatas,
								backgroundColor: BGC1,
								borderColor: BC1,
		            borderWidth: 1
		        },
						{//2nd Colonnes
	            label: '# Single Core',
	            data: __singleDatas,
	            backgroundColor: BGC,
	            borderColor: BC,
	            borderWidth: 1
	        }

				]
		},
	    options: {
					scales: {
						xAxes: [{ stacked: true }],
						yAxes: [{ stacked: true	}]
					}
			}

	});
}
var __lab = [
	'Athlon 5150 APU',
	'Athlon 5350 APU',
	'Athlon 5370 APU',
	'Sempron 2650 APU',
	'Sempron 3850 APU'];
var __MulDats =  [159.95, 109.99, 63.70, 64.95, 72.10];
var __SinDats = [2112, 2611, 2915, 951, 1718];
//setChart(__lab, __MulDats, __SinDats);




</script>


</body>

</html>
<?php
function aff_fab($ref) {
	global $connMysqli;
	$result = $connMysqli->query("SELECT * FROM fabricant WHERE fab_ref='$ref';");
	if ($result->num_rows > 0) {
		$row = $result -> fetch_row();
		$nomFab=$row[1];
		} else $nomFab= "Erreur : Fabricant inconnu";
	$result -> free_result();
	return $nomFab;
}



?>
