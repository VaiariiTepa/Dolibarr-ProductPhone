<?php
/* <one line to give the program's name and a brief idea of what it does.>
 * Copyright (C) <year>  <name of author>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * \file    index.php
 * \ingroup productphone
 * \brief   Example PHP page.
 *
 * Put detailed description here.
 */

// Load Dolibarr environment
require '../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/productphone/class/productphone.class.php';

global $db, $langs, $user;

// Access control
//if ($user->socid > 0) accessforbidden();

// Load translation files required by the page
$langs->load("productphone@productphone");


// Get parameters
$device = GETPOST('device');
$action = GETPOST('action');


$t_liste = array();


/*
 * ACTIONS
 *
 * Put here all code to do according to value of "action" parameter
 */
if ($action == 'rechercher') {
    $_fonoApi = new fonoApi($db);
    $result = $_fonoApi->getdevice($device);
    if ($result) {
        // Creation OK
        foreach($result as $key=>$reponse){
            $t_liste[] = (array)$reponse;
        }
    } else {
        // Creation KO
        setEventMessage('Erreur', 'errors');
    }
}

if($action == 'insert'){
    var_dump('lecture action insert');
    $_fonoApi = new fonoApi($db);
    $_fonoApi->device = $_GET['device'];
    $_fonoApi->brand = $_GET['brand'];
    $_fonoApi->_4G = $_GET['4G'];
    $_fonoApi->_3G = $_GET['3G'];
    $_fonoApi->_2G = $_GET['2G'];
    $_fonoApi->size = $_GET['size'];
    $_fonoApi->os = $_GET['os'];
    $_fonoApi->apn = $_GET['apn'];
    $_fonoApi->processeur = $_GET['processeur'];
    $_fonoApi->batterie= $_GET['batterie'];
    $_fonoApi->memoire_interne = $_GET['memoire'];
    $_fonoApi->couleur = $_GET['couleur'];
    $_fonoApi->sim = $_GET['sim'];
    $_fonoApi->particulariter = $_GET['particulariter'];
    $_fonoApi->sar = $_GET['sar'];

    $result = $_fonoApi->insert($user);
}


//-------------------------------------------------------------------------------

/*
 * VIEW
 */

llxHeader('', $langs->trans('Product-Phone'), '');

print '<h2>PRODUCT PHONE</h2>';

print '<form action="index.php" method="GET">';
print '<input type="hidden" name="action" value="rechercher"/>';
print 'Modele : <input type="text" name="device"/> ';
print '<input type="submit" value="Rechercher Modele"/>';
print '</form>';

print '<form action="index.php?action=insert" method="POST">';
if($t_liste){
    echo '<h2>Liste des enregistrements</h2><button><a href="index.php?action=insert&rowid='.$key.'&device='.$DeviceName.'
              &brand='.$Brand.'&4G='.$_4G.'&3G='.$_3G.'&2G='.$_2G.'&size='.$ecrant.'&os='.$os.'&apn='.$apn.'&processeur='.$processeur.'&batterie='.$batterie.'&memoire='.$memoire_interne.'&couleur='.$couleur.'&sim='.$sim.'&particulariter='.$particulariter.'&sar='.$sar.'">enregistre</a></button><br>';
    echo '<table style="border: 1px solid black;">';
    echo '<tbody >';
    echo '<tr >';
    echo '<th style="border: 1px solid black;">Marque</th>';
    echo '<th>Modele</th>';
    echo '<th>Taille ecrant</th>';
    echo '<th>Connexion Mobile</th>';
    echo '<th>OS</th>';
    echo '<th>APN</th>';
    echo '<th>CPU</th>';
//    echo '<th>Action</th>';
    echo '</tr>';
    echo '</tbody>';
//On affiche chaque entrée une a une
    foreach($t_liste as $key=>$reponse){

        $DeviceName = $reponse['DeviceName'];
        $Brand = $reponse['Brand'];
        $_4G = $reponse['_4g_bands'];
        $_3G = $reponse['_3g_bands'];
        $_2G = $reponse['_2g_bands'];
        $ecrant = $reponse['size'];
        $os = $reponse['os'];
        $apn = $reponse['primary_'];
        $processeur = $reponse['cpu'];
        $batterie = $reponse['battery_c'];
        $memoire_interne = $reponse['internal'];
        $couleur = $reponse['colors'];
        $sim= $reponse['sim'];
        $particulariter = $reponse['body_c'];
        $sar = $reponse['sar_eu'];



        //Brand
        echo '<tr>';
        echo '<td style="border: 1px solid black;">';
        echo $reponse['Brand'];
        echo '</td>';

        //DeviceName
        echo '<td style="border: 1px solid black;">';
        echo $reponse['DeviceName'];
        echo '<input type="hidden" name="'.$reponse['DeviceName'].'">';
        echo '</td>';
//--------------------------------------------------------------------
        //Size
        //< ou = a 4"
        if($reponse['Size']<=4.0){
            echo '<td style="border: 1px solid black;">';
            echo $reponse['Size'];
            echo ' < ou = a 4 Pouces';
            echo '</td>';

            //> a 12
        }elseif($reponse['Size']>12.0){
            echo '<td style="border: 1px solid black;">';
            echo $reponse['Size'];
            echo ' > a 12 Pouces';
            echo '</td>';
            //> a 10"
        }elseif($reponse['Size']>=10.0){
            echo '<td style="border: 1px solid black;">';
            echo $reponse['Size'];
            echo ' > ou = a 10 Pouces';
            echo '</td>';
            //> a 8"
        }elseif($reponse['Size']>8.0){
            echo '<td style="border: 1px solid black;">';
            echo $reponse['Size'];
            echo ' > a 8 Pouces';
            echo '</td>';
            //> a 6"
        }elseif($reponse['Size']>6.0){
            echo '<td style="border: 1px solid black;">';
            echo $reponse['Size'];
            echo ' > a 6 Pouces';
            echo '</td>';
            //> a 5"
        }elseif($reponse['Size']>5.0){
            echo '<td style="border: 1px solid black;">';
            echo $reponse['Size'];
            echo ' > a 5 Pouces';
            echo '</td>';
            //< ou = a 5"
        }elseif($reponse['Size']<=5.0){
            echo '<td style="border: 1px solid black;">';
            echo $reponse['Size'];
            echo ' < ou = a 5 Pouces';
            echo '</td>';
        }

//--------------------------------------------------------------------
        //3G ou 4G ou 2G
        if($reponse['_4g_bands']==true){
            //4G
            echo '<td style="border: 1px solid black;">';
            echo '4G';
            echo '</td>';
        } elseif($reponse['_3g_bands']==true) {
            //3G
            echo '<td style="border: 1px solid black;">';
            echo '3G';
            echo '</td>';
        } else {
            //2G
            echo '<td style="border: 1px solid black;">';
            echo '2G';
            echo '</td>';
        }
//--------------------------------------------------------------------
        //OS
        if ($reponse['OS'] == 'And') {
            echo '<td style="border: 1px solid black;">';
            echo 'Android';
            echo '</td>';
        } elseif ($reponse['OS'] == 'iOS') {
            echo '<td style="border: 1px solid black;">';
            echo 'IOS';
            echo '</td>';
        } elseif ($reponse['OS'] == 'Fir') {
            echo '<td style="border: 1px solid black;">';
            echo 'Firefox OS';
            echo '</td>';
        } elseif ($reponse['OS'] == 'Bla') {
            echo '<td style="border: 1px solid black;">';
            echo 'BlackBerry';
            echo '</td>';
        } elseif ($reponse['OS'] == 'Mic') {
            echo '<td style="border: 1px solid black;">';
            echo 'Microsoft';
            echo '</td>';
        } elseif ($reponse['OS'] == '') {
            echo '<td style="border: 1px solid black;">';
            echo 'Proprietaire';
            echo '</td>';
        }
//--------------------------------------------------------------------
        //APN
        if($reponse['primary_']>=20){
            echo '<td style="border: 1px solid black;">';
            echo $reponse['primary_'];
            echo ' egale ou Superieur a 20';
            echo '</td>';
        }elseif($reponse['primary_']>=18){
            echo '<td style="border: 1px solid black;">';
            echo $reponse['primary_'];
            echo ' egale ou Superieur a 18';
            echo '</td>';
        }elseif($reponse['primary_']>=16){
            echo '<td style="border: 1px solid black;">';
            echo $reponse['primary_'];
            echo ' superieur a 16';
            echo '</td>';
        }elseif($reponse['primary_']>=13){
            echo '<td style="border: 1px solid black;">';
            echo $reponse['primary_'];
            echo ' egale ou superieur a 13';
            echo '</td>';
        }elseif($reponse['primary_']>=12){
            echo '<td style="border: 1px solid black;">';
            echo $reponse['primary_'];
            echo ' egale ou Superieur a 12';
            echo '</td>';
        }elseif($reponse['primary_']>=8){
            echo '<td style="border: 1px solid black;">';
            echo $reponse['primary_'];
            echo ' egale ou Superieur a 8';
            echo '</td>';
        }elseif($reponse['primary_']>=5){
            echo '<td style="border: 1px solid black;">';
            echo $reponse['primary_'];
            echo ' egale ou Superieur a 5';
            echo '</td>';
        }elseif($reponse['primary_']>=2) {
            echo '<td style="border: 1px solid black;">';
            echo $reponse['primary_'];
            echo ' egale ou Superieur a 2';
            echo '</td>';
        }elseif($reponse['primary_']>=1) {
            echo '<td style="border: 1px solid black;">';
            echo $reponse['primary_'];
            echo ' egale ou Superieur a 1';
            echo '</td>';
        }elseif($reponse['primary_']>='VG') {
            echo '<td style="border: 1px solid black;">';
            echo $reponse['primary_'];
            echo ' VGA';
            echo '</td>';
        }elseif($reponse['primary_'] == 'Du') {
            echo '<td style="border: 1px solid black;">';
            echo ' 12 megapixel';
            echo '</td>';
        }elseif($reponse['primary_'] == '') {
            echo '<td style="border: 1px solid black;">';
            echo $reponse['primary_'];
            echo ' Pas d\'APN';
            echo '</td>';
        }
//--------------------------------------------------------------------
        //CPU
//    var_dump($reponse['cpu']);
        if($reponse['cpu'] == 'Hex'){
            echo '<td style="border: 1px solid black;">';
            echo ' hexa-core';
            echo '</td>';
        }elseif($reponse['cpu'] == 'Dua'){
            echo '<td style="border: 1px solid black;">';
            echo ' dual-core';
            echo '</td>';
        }elseif($reponse['cpu'] == 'Qua'){
            echo '<td style="border: 1px solid black;">';
            echo ' quad-core';
            echo '</td>';
        }elseif($reponse['cpu'] == 'Oct'){
            echo '<td style="border: 1px solid black;">';
            echo ' octa-core';
            echo '</td>';
        }else{
            echo '<td style="border: 1px solid black;">';
            echo ' -core';
            echo '</td>';
        }

//        echo '<td style="border: 1px solid black;">';
//        print '<a href="index.php?action=insert&rowid='.$key.'&device='.$DeviceName.'
//              &brand='.$Brand.'&4G='.$_4G.'&3G='.$_3G.'&2G='.$_2G.'&size='.$ecrant.'&os='.$os.'&apn='.$apn.'&processeur='.$processeur.'&batterie='.$batterie.'&memoire='.$memoire_interne.'&couleur='.$couleur.'&sim='.$sim.'&particulariter='.$particulariter.'&sar='.$sar.'">enregistre</a>';
////        print '<input type="submit" value="enregistré"/>';
//        echo '</td>';

        echo '</tr>';
        print '<form>';
    }
    echo '</table>';
}



// Example 1: Adding jquery code
//echo '<script type=application/javascript" language="javascript">
//	jQuery(document).ready(function() {
//		function init_myfunc()
//		{
//			jQuery("#myid")
//			.removeAttr(\'disabled\')
//			.attr(\'disabled\',\'disabled\');
//		}
//		init_myfunc();
//		jQuery("#mybutton").click(function() {
//			init_needroot();
//		});
//	});
//</script>';

// Example 2: Adding links to objects
// The class must extend CommonObject for this method to be available
//$somethingshown = $form->showLinkedObjectBlock($myobject);

// End of page
llxFooter();