<?php
/**
 * Created by PhpStorm.
 * User: vaiarii.tepa
 * Date: 09/08/2018
 * Time: 11:58
 *
 *  \file       htdocs/product/fichebrut.php
 *  \ingroup    productphone
 *  \brief      Page to Card Raw
 */
// Librairies
require '../main.inc.php';
require_once "class/productphone.class.php";

// Access control
if( !$user->admin) accessforbidden();

//Initialisation de l objet
$_productPhone = new ProductPhone($db);

// Translation
$langs->load("productphone@productphone");

//Parameter
$p_fk_product_phone_raw = GETPOST('rowid');

// Si presence de l id du produit alors
if($p_fk_product_phone_raw)
{
    // Liste les appareils presents
    $t_productPhone = $_productPhone->fetch_productPhone_all($p_fk_product_phone_raw);
    // Si appareil present dans tableau alors remet le pointeur interne de tableau au debut
    if($t_productPhone) $t_productPhone = reset($t_productPhone);

    // Recupere l appareil dans product phone
    $t_productPhone_product = $_productPhone->get_product_from_productphone($p_fk_product_phone_raw);
//    var_dump($p_fk_product_phone_raw,$t_productPhone_product);
}

$t_param = array('rowid' => $p_fk_product_phone_raw);

$t_fields = array(

    // Bloc 1 : Marque Modele
    array(
        'Brand'
    ,'DeviceName'),

    // Bloc 2 : Donnees brut retravailler
    array(
        'os_name'
    ,'os_version'
    ,'os_version_name'
    ,'screen_resolution'
    ,'phone_size'
    ,'phone_weight'
    ,'primary_camera_resolution'
    ,'secondary_camera_resolution'
    ,'cpu_number'
    ,'spu_speed'
    ,'ram'
    ,'interne_memory'
    ,'connexion_type'
    ,'battery_capacity'
    ,'phone_color'
    ,'sim1_format'
    ,'sim2_format'
    ,'dual_sim'),

    // Bloc 3 : ProductPhone_product
    array(
        'fk_product'
    ,'ref'
    ,'label')
);

/******************************************************************************/
/*                               Affichage fiche                              */
/******************************************************************************/
$title = 'productphone_detailsSheet';
llxHeader('', $title);

// Configuration header
print load_fiche_titre($langs->trans($title));
$head = productPhoneCardPrepareHead($t_param);
dol_fiche_head($head, 'produit_associer', $langs->trans("Module750504Name"), 1, 'productphone@productphone');

// Affichage de la marque et modele de l appareil
print '<div class="tabBar">';
print '<table class="border" width="100%">';
//print_titre($langs->trans("productphone_brandAndDeviceName"));
print '<tbody>';
    foreach ($t_fields[0] as $key)
    {
        print '<tr>';
        print '<td style="text-align: left" width="30%">'.$langs->trans('productphone_'.$key).'</td>';
        print '<td style="text-align: left">'.$t_productPhone[$key].'</td>';
        print '</tr>';
    }
print '</tbody>';
print '</table>';
print '</div>';

// Affichage des donnees retravailler : caracteristiques
print '<div class="tabBar">';
print '<table class="border" width="100%">';
//print_titre($langs->trans("productphone_specificationsDevice"));
print '<tbody>';
foreach ($t_fields[1] as $key)
{
    print '<tr>';
    print '<td style="text-align: left" width="30%">'.$langs->trans('productphone_'.$key).'</td>';
    print '<td style="text-align: left">'.$t_productPhone[$key].'</td>';
    print '</tr>';
}
print '</tbody>';
print '</table>';
print '</div>';

// Affichage des appareils associes
if($t_productPhone_product)
{
    print '<div class="tabBar">';
    print '<table class="noborder">';
    print_titre($langs->trans("productphone_listOfRelatedProducts"));
    print '<tbody>';
    print '<tr class="liste_titre">';
    foreach ($t_fields[2] as $key)
    {
        print '<th width="10%" style="text-align: left">'.$key.'</th>';
    }
    print '</tr>';
    foreach ($t_productPhone_product as $device)
    {
        $parity = !$parity;
        // listing des appareils associes
        print '<tr class="'.($parity?"pair":"impair").'">';
        print '<td style="text-align: left">'.$device["fk_product"].'</td>';
        print '<td style="text-align: left">'.$device["ref"].'</td>';
        print '<td style="text-align: left">'.$device["label"].'</td>';
        print '</tr>';
    }
    print '</tbody>';
    print '</table>';
    print '</div>';
}

// Page end
dol_fiche_end();
llxFooter();
$db->close();

