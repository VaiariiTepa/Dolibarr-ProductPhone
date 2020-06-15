<?php

require '../main.inc.php';
require_once TCPDF_PATH.'tcpdf.php';
require_once "class/productphone.class.php";

// Access control
if( !$user->admin) accessforbidden();

//Initialisation de l objet
$_productPhone = new ProductPhone($db);


//Parameter
// $p_fk_product_phone_raw = GETPOST('rowid');
// $p_value_capaciti = GETPOST('value_capaciti');
// $p_rowid_type_promotion = GETPOST('rowid_type_promotion');
// $p_fk_product_phone = GETPOST('fk_product_phone');

// // Si presence de l id du produit alors
// if($p_fk_product_phone_raw)
// {
// 	// Récupère 
// 	// Caractéristiques
// 	// Nom appareil
//     $t_productPhone = $_productPhone->get_productPhone_ById($p_fk_product_phone_raw);
	
// 	// Récupère
// 	// Capacité stokage
// 	// Scénario
// 	// Prix + promo
// 	$t_capacity_promotion = $_productPhone->get_product_capacity_promotion(
// 		$p_value_capaciti
// 		,$p_rowid_type_promotion
// 		,$p_fk_product_phone
// 	);

// }


/**
 * View
 */
$content = '<div id="bandeau">
			<p>
				bandeau
			</p> 
				
			<!-- <img class="enhaut"src="C:\Users\Joane\Documents\Projet voda\img\bandeau_tatoo.png"> -->
			</div>';

 $content .= '<div id="header">


				<div>
					<p>
						<h1 class="blocktextcentre">Samsung galaxi A80</h1>
						<h5 class="blocktextdroit"> Taux de DAS : 0.242w/kg </h5>
					</p>
				</div>
				</div>

				<div id="body_caracteristique">
				
				<div class="separation">
					séparation
						<!-- <img src="C:\Users\Joane\Documents\Projet voda\img\separation.png"> -->
				</div>
				<div id="caracteristique">
					
						<table>
							<tr>
								<th>
									<div id ="icone1">icone1</div>            
								</th>
								<th>
									<div id="icone2">icone2</div>
								</th>
								<th>
									<div id="icone3">icone3</div>
								</th>
							</tr>
							<tr>
								<td>
									valeur 1er colonne
								</td>
								<td>
									valeur 2em colonne
								</td>
								<td>
									valeur 3em colonne
								</td>
							</tr>
						</table>
						<table>
							<tr>
								<th>
									<div id="icone4">icone4</div>            
								</th>
								<th>
									<div id="icone5">icone5</div>
								</th>
								<th>
									<div id="icone6">icone6</div>
								</th>
							</tr>
							<tr>
								<td>
									valeur 4em colonne            
								</td>
								<td>
									valeur 5em colonne
								</td>
								<td>
									valeur 6em colonne
								</td>
							</tr>
						</table>
					
				</div>
				
				<div class="separation">
					séparation
					<!-- <img src="C:\Users\Joane\Documents\Projet voda\img\separation_bas.png"> -->
				</div>
				</div>

				<div id="couleur">
				<table width="100%" id="table_color">
					<tr>
						<td width="50%" colspan="3">
							Couleur
							<div id="couleurtel">couleur disponible</div>
						</td>
						<td width="25%">
							Type téléphone
							<div id="typetel"> type de téléphone</div>
						</td>
						<td width="25%">
							Capacité
							<div id="capacitetel">Capicité disponible </div>
						</td>
					</tr>
				</table>
			</div>
			';

// foreach ($t_productPhone as $key => $value) {
	
// 	foreach ($value as $val) {
// 		// var_dump($val);
// 		$content = '
// 					<div >
// 						<img src="./img/bandeau_tatoo.png" width="1000px" >
// 						<div style="text-align: center;">'	
// 						.$val['DeviceName'].
// 						'</div>
// 						<br>
// 						<img src="./img/separation.png" >
// 						<br>
// 						<table border="1">
// 							<tbody>
// 								<tr style="border: 1px solid black;">
// 									<td >';
							
// 									if($val['connexion_type'] == '4G') {
// 										# code...
// 										$content.='<img src="./img/4G.png" >'
// 										.'<br><b>Connexion</b>';
// 									}else{
// 										$content.='<img src="./img/3G.png">'
// 										.'<br><b>Connexion</br>';
// 									}
									
// 									$content .='</td>
									
// 									<td>
// 										<img src="./img/phone_size.png">'
// 										.'<br><b>'.$val['phone_size'].'</b>
// 									</td>

// 									<td>
// 										<img src="./img/camera_resolution.png">'
// 										.'<br><b>'.$val['primary_camera_resolution'].'</b>
// 									</td>

// 								</tr>

// 								<tr>
// 									<td>
// 										<img src="./img/camera_resolution.png">'
// 										.'<br><b>'.$val['primary_camera_resolution'].'</b>
// 									</td>
									
// 									<td>2em ligne
// 									<br></td>
									
// 									<td>2em ligne
// 									<br></td>
// 								</tr>
// 								</tbody>
// 						</table>
// 						<img src="./img/separation_bas.png">
// 						';
// 	}

// }

// $scenario = '<div class="abonnement"><p><h4>Abonnements</h4></p><br>';

$scenario .= '
<div id="all_price">
 <table>
	 <th colspan="2">ABONNEMMENT</th>
	 <tr>
		 <td class="price_field">abo>13000</td>
		 <td class="price_value"><b>8873fr</b></td>
	 </tr>
	 <tr>
		 <td class="price_field">abo>8000</td>
		 <td class="price_value"><b>6456fr</b></td>
	 </tr>
 </table>
 <table>
		 <th colspan="2">PREPAYEE</th>
	 <tr>
		 <td class="price_field">abo>5000</td>
		 <td class="price_value"><b>8873fr</b></td>
	 </tr>
	 <tr>
		 <td class="price_field">abo>2500</td>
		 <td class="price_value"><b>6456fr</b></td>
	 </tr>
 </table><table>
 <th colspan="2">SANS ENGAGEMENT</th>
<tr>
 <td id="price_raw"><h2>96 999FR</h2></td>
</tr>

</table>
</div>';

// Affichages des scénarios
// foreach ($t_capacity_promotion as $device) {
// 	$label = explode('-',$device['label']);
// 	$abo = explode('(',$label[0]);

// 	if($device['price_ttc'] < 1){
// 		if($abo[2]){

// 			$scenario .='<li>'.$abo[2].'<b>1 F</b></li>';
// 		}

		
// 	}else{
// 		if ($abo[2]) {
// 			$scenario .='<li>'.$abo[2].'<b>'.substr($device['price_ttc'], 0, -9).' F</b></li>';
// 		}
		
// 	}
// }
// $scenario .='</div>';

// Affichage prix Sans Engagement
// $old_price = '<br><div><p class="old_price">'.$p_old_price.'</p><h2>';
// foreach ($t_capacity_promotion as $value) {
// 	$old_price .= $value['promo_price'];
// break;
// 	}
// $old_price .= '</h2></div>';

// $footer = '<div id="footer"><img src="./img/logo_footer_right.png" style="width:200px;position: sticky;bottom: 0px;"></div>';
$style = '
	<style>
	th{
		border: 1px black solid;
	}
	
	td{
		border: 1px black solid;
	}
	tr{
		border: 1px black solid;
	}
	h1{
		border: 1px black solid;
	}
	#bandeau{
		border: 1px black solid;
	}
	
	#all_price.table{
		width: auto;
		border: 1px red solid;
	}
	table{
		width: 100%;
		border: 1px black solid;
	}
	
	#body_caracteristique{
		margin: auto;
		width: 80%;
	}
	
	#couleur{
		margin: auto;
		width: 80%;
	}
	
	#all_price{
		margin: auto;
		width: 80%;
	}
	
	#caracteristique{
		margin: auto;
		width: 100%;
		text-align: center;
		border: 1px green solid;
	}
	
	#header{
		margin: auto;
		width: 80%;
		text-align: center;
		border: greenyellow 2px solid;
	}
	
	.price_value{
		width: 50%;
		background-color: #DCDCDC;
		text-align: right;
	}
	
	.price_field{
		width: 50%;
		background-color: #DCDCDC;
		text-align: left;
	}
	
	#price_raw{
		text-align: center;
	}
	
	#bandeau {
		height: 36px;
		width: 100%;
		margin: auto;
		text-align: center;
		border: solid 1px black;
		/* padding-top: 50px; */
	}
	
	
	h5.blocktextdroit {
		margin-top: 8px;
		margin-left: 0px;
		margin-bottom: 5px;
		margin-right: 0px;
		border: solid 1px red;
		text-align: right;
	}
	
	
	
	.separation {
		border: solid 1px black;
	}
	
	div#icone1{
		margin: auto;
		width: 100%;
		height: 150px;    
		/* padding-left:35px; */
		border: solid 1px black;
		/* float: left; */
		background-color: red;
	}
	div#icone2{
		margin: auto;
		width: 100%;
		height: 150px;    
		/* padding-left:35px; */
		border: solid 1px black;
		/* float: left; */
		background-color: grey;
	}
	div#icone3{
		margin: auto;
		width: 100%;
		height: 150px;    
		/* padding-left:35px; */
		border: solid 1px black;
		/* float: left; */
		background-color: yellowgreen;
		
	}
	div#icone4{
		margin: auto;
		width: 100%;
		height: 150px;    
		/* padding-left:35px; */
		border: solid 1px black;
		/* float: none; */
		background-color: purple;
	}
	div#icone5{
		margin: auto;
		width: 100%;
		height: 150px;    
		/* padding-left:35px; */
		border: solid 1px black;
		/* float: left; */
		/* position: absolute; */
		left: 207px;
		background-color: blue;
	}
	div#icone6{
		margin: auto;
		width: 100%;
		height: 150px;
		/* padding-left: 35px; */
		border: solid 1px black;
		/* float: left; */
		/* position: absolute; */
		left: 207px;
		background-color: maroon;
	}
	
	div#couleurtel{
		height: 50px;
		margin: 0px 0px 0px 0px;
		border: solid 1px black;
		/* position: absolute; */
		top: 172px;
		right: 362px;
		left: 47px;
	
	}
	div#typetel{
		height: 50px;
		border: solid 1px black;
		/* position: absolute; */
		top: 20px;
		left: 213px;
	
		
	}
	div#capacitetel{
		height: 50px;
		border: solid 1px black;
		/* position: absolute; */
		top: -54px;
		left: 468px;
		
	}
		
	</style>
	';
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// $pdf->setHeaderData($logo,$width_logo);
$pdf->setPrintFooter(False);

// set margins
$pdf->SetMargins(0,0,0);

$pdf->SetFont('helvetica','',18);

$pdf->AddPage('P','A4');

$pdf->writeHTML($style.$content,true,false,true,false,'');
$pdf->writeHTML($style.$scenario,true,false,true,false,'');
// $pdf->writeHTML($style.$old_price,true,false,true,false,'');
// $pdf->writeHTML($style.$footer,true,false,true,false,'');

	


$pdf->lastPage();

$pdf->Output($devicename.'_ProductPad','I');

// Page end
dol_fiche_end();
llxFooter();
?>
<!-- JS, Popper.js, and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
