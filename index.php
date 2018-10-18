<?php
/**
 * Created by PhpStorm.
 * User: vaiarii.tepa
 * Date: 25/04/2018
 * Time: 11:46
 */
// Load Dolibarr environment
require '../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/productphone/class/productphone.class.php';

global $db, $langs, $user;

// Access control
//if ($user->socid > 0) accessforbidden();

// Load translation files required by the page
$langs->load("productphone@productphone");

// Initialization of object productphone
$_productPhone = new ProductPhone($db);

// Get parameters
$action = GETPOST('action');
$p_productphone = GETPOST('productphone');
$p_search = GETPOST('search');
$brand = GETPOST('brand');
$device = GETPOST('device');

// Array field names
$t_field = array(
    "brand" => "Marque",
    "device" => "Modèle"
);

// Retrieve the different fields of the filters
$_filter = $_productPhone->get_filter();

// Generate the html of the different fields of the search filter
$t_input = generateInputHTMLofFilter($_filter);

// If action is equal to search else
if($action == 'search') {

    $_resultats = $_productPhone->search_productphone($p_productphone);
    if ($_resultats) {
        foreach ($_resultats as $resultat) {
            $t_resultats[] = $resultat;
        }
    }
}
//else {
//    $_resultats = $_productPhone->get_productphone_all();
//    if ($_resultats) {1
//        foreach ($_resultats as $resultat) {
//            $t_resultats[] = $resultat;
//        }
//    }
//}

/*
 * VIEW
 */

llxHeader('', $langs->trans('Product-Phone'));


print '<h1>Affichage liste PRODUCT-PHONE</h1>';

//affiché toutes les données d'un Mega var_dump
//    print '<pre>';
//    print_r($t_resultats);
//    print '</pre>';

/*//////////////////////////////////DIV OUVRANTE GLOBAL -- CONTAINERFLUID///////////////////////////////////////////*/
print '<div id="global_div">';
/*/////////////////////////////////////////////////////////////////////////////////////////////////////////////////*/
    /*************open div row***************/
    print '<div class="row_catalogue">';
        //*********************************DIV OUVRANTE CATALOGUE***********************************************//
        print '<div id="accordion">';
        //*******************************************************************************************************//
            if($t_resultats){
                foreach ($t_resultats as $key=>$value){
                    //==== TITRE ====//
                    print '<h4>';
                    print '<div id="bandeau">';
                        print '<div id="header_accordion">';
                            print '<div>';
                                print '<b class="DeviceName">'.$value['DeviceName'].' </b>';
                                foreach(array_slice($value['DeviceAssociated'], 0, 1) AS $ref) {
                                    print ' date de sortie: ' . $ref['announced'];
                                }
                            print '</div>';
                        print '</div>';
                    print '</div>';
                        print '<div id="cadre_title">';
                            print '<div id="cadre">';
//                                    //==== TITRE CARACTERISTIQUE ====//
                                    foreach(array_slice($value['DeviceAssociated'], 0, 1) AS $ref){
                                        print '<tr class="titre_accordion">';
                                            print '<td class="contenue">';
                                                print $ref['Os_name'].' '.$ref['Os_version'].' '.$ref['Os_version_name'];
                                            print '</td>';
                                            print ' | ';
                                            print '<td class="contenue">';
                                                print $ref['cpu_number'].' coeur';
                                            print '</td>';
                                            print ' | ';
                                            print '<td class="contenue">';
                                                print 'Batterie '.$ref['battery_capacity'];
                                            print '</td>';
                                            print ' | ';
                                            print '<td class="contenue">';
                                                print 'Format Sim 1 '.$ref['sim1_format'];
                                            print '</td>';
                                        print '</tr>';
                                        print '<tr class="titre_accordion">';
                                            print '<td class="contenue">';
                                                print $ref['phone_size'].' Pouce - '.$ref['screen_resolution'].' Pixel';
                                            print '</td>';
                                            print ' | ';
                                            print '<td class="contenue">';
                                                print $ref['ram'];
                                            print '</td>';
                                            print ' | ';
                                            print '<td class="contenue">';
                                                print $ref['phone_weight'].' Grammes';
                                            print '</td>';
                                            print ' | ';
                                            print '<td class="contenue">';
                                                print 'Format Sim2: '.$ref['sim2_format'];
                                            print '</td>';
                                        print ' | ';
                                        print '</tr>';
                                        print '<tr class="titre_accordion">';
                                            print '<td class="contenue">';
                                                print $ref['primary_camera_resolution'].' (Principal) - '.$ref['secondary_camera_resolution'].' (Avant)';
                                            print '</td>';
                                            print ' | ';
                                            print '<td class="contenue">';
                                                print $ref['interne_memory'].' Mémoire interne';
                                            print '</td>';
                                            print ' | ';
                                            print '<td class="contenue">';
                                                print 'dual sim ou pas: '.$ref['dual_sim'];
                                            print '</td>';
                                        print '</tr>';
                            }
                            print '</div>';
                        print '</div>';
                    print '</h4>';
                    //============== Telephone -- Table ==============//
                    print '<div id="contenue_accordion">';
                        print '<div id="photo">';
                        print '</div>';
                        print '<div id="table_contenue_accordion">';
                        print '<table class="noborder" width="100%">';
                            // HEADER
                            print '<thead>';
                                print '<tr class="liste_titre">';
                                    print '<th width="25%">';
                                        print '<p>Réference</p>';
                                    print '</th>';
                                    print '<th width="25%">';
                                        print '<p>libéler</p>';
                                    print '</th>';
                                    print '<th width="25%">';
                                        print '<p>prix</p>';
                                    print '</th>';
                                print '</tr>';
                            print '</thead>';
                            // BODY
                            print '<tbody>';
                            $parity = TRUE;
                                foreach($value['DeviceAssociated'] AS $ref) {
                                    $parity =! $parity;
                                print '<tr class="'.($parity?'pair':'impair').'">';
                                    print '<td width="25%">';
                                        print $ref['Ref'];
                                    print '</td>';
                                    print '<td width="25%">';
                                        print $ref['label'];
                                    print '</td>';
                                    print '<td width="25%">';
                                        print $ref['Prix_TTC'].'xpf TTC';
                                    print '</td>';
                                print '</tr>';
                                }
                            print '</tbody>';
                        print '</table>';
                        print '</div>';
                    print '</div>';
                }
            }
        //*********************************DIV FERMANTE CATALOGUE***********************************************//
        print '</div>';
        //******************************************************************************************************//
print '<br>';

// LATERAL BAR SEARCH / FILTER OF CATALOG
print '<div class="vertical-menu">';
//print '<h5><em>' . $langs->trans("productPhoneCriteriaToRefineSearch") . ': </em></h5>';
//print '<form action="' . dol_buildpath('/productphone/index.php', 1) . '?action=search&productphone="' . $p_productphone . '" method="get">';
//print '<div>';
//foreach ($t_input as $key => $value) {
//    print $value['label'];
//    print '<br>';
//    print $value['input'];
//    print '<br>';
//}
//print '</div>';
//print '<div>';
//print '<button type="submit" name="action" class="button" value="search">' . $langs->trans("productPhoneSearch") . '</button>';
//print '</div>';
//print '</form>';
//print '</div>';
//// filtre en Dur pour les test
//print '<div class="marque">';
//print '<div>';
////print '<label>sony</label>';
////print '<input type="checkbox" class="sony" placeholder="sony" value="sony">';
////print '</div>';
//print '<div>';
//print '<label>apple</label>';
//print '<input type="checkbox" id="apple" placeholder="apple" value="apple">';
//print '</div>';
//print '<div>';
//print '<label>samsung</label>';
//print '<input type="checkbox" id="samsung" placeholder="samsung" value="samsung">';
//print '</div>';
//print '<div>';
//print '<label>huawei</label>';
//print '<input type="checkbox" id="huawei" placeholder="huawei" value="huawei">';
//print '</div>';
//print '</div>';

foreach ($_filter as $filter => $value)
{
    print '<div class="barre_laterale">';

    // Si type est un select
    if( $value [ 'type' ] == 'select')
    {
        print '<div>';
        print '<label>'.$value['label'].'</label>';
        print '<select width="20%" class="'.$value['label'].'">';
        print '<option value></option>';
        foreach ($value['t_value'] as $val)
        {
            print '<option value="'.$val.'" class="'.$val.'">'.$val.'</option>';
        }
        print '</select><br>';
        print '</div>';
    }
    // Sinon si type est un checkbox
    elseif ($value['type'] == 'checkbox')
    {
        print '<div>';
        print '<label>'.$value['label'].'</label>';
        print '<div class="checkbox">';
        foreach ($value['t_value'] as $val)
        {
            print '<label>';
            print '<input type="checkbox" class="flat">'.$val;
            print '</label><br>';
        }
        print '</div>';
        print '</div>';
    }
    // Sinon si type est un text
    elseif ($value['type'] == 'text')
    {
        print '<div>';
        print '<label>'.$value['label'].'</label>';
        print '<div class=" form-group">';
        foreach ($value['t_value'] as $val)
        {
            print '<input type="text" placeholder="'.$val.'" value="'.$p_productphone.'">';
        }
        print '</div><br>';
        print '</div>';
    }
    // Sinon si type est un radio
    elseif ($value['type'] == 'radio')
    {
        print '<div>';
        print '<label>'.$value['label'].'</label>';
        print '<div class=" form-group">';
        foreach ($value['t_value'] as $val)
        {
            print '<input type="radio" placeholder="'.$val.'" value="'.$p_productphone.'">';
        }
        print '</div><br>';
        print '</div>';
    }
    print '</div>';
}
print '<form action="'.dol_buildpath('/productphone/index.php', 1).'?action=search" method="post">';
print '<input type="text" name="productphone" value="'.$p_productphone.'" placeholder="search"/><br>';
print '<button type="submit" name="action" class="btn btn-primary" value="search">'.$langs->trans("productPhoneSearch").'</button>';
print '</form>';
print '</div>';

        //=======================================================================================================//
    /***********close div row**********/
    print '</div>';

/*/////////////////////////////////DIV FERMANTE GLOBAL -- CONTAINERFLUID/////////////////////////////////////////*/
print '</div>';
/*//////////////////////////////////////////////////////////////////////////////////////////////////////////////*/

// End of page
llxFooter();
?>
<script>

    $(document).ready(function(){

        var model;
        var cloneMarque;
        var cloneModèle;

        //ACCORDION
        $( "#accordion" ).accordion({
            collapsible: true
        });

        /* zone de test */
//
//        $('select.Marque').change(function (){
//            $('#header_accordion>div>b').addClass('selected');
//            model = $('select.Marque option:selected').val();
//
//            /* affiché le filtre selectionner,et le met en surbrillance */
//            cloneMarque = $('select.Marque option:selected').clone();
//            cloneMarque.appendTo($('div#cadre'));
//
//            show_filter_selected(cloneMarque);
//        });
//
        $('select.Modèle').change(function(){
            cloneModèle = $('select.Modèle option:selected').clone();
            cloneModèle.appendTo($('div#cadre'));
            show_filter_selected(cloneMarque);
        })
    })

    /* Récupère les produits en fonction du filtre selectionner */
    function show_filter_selected(model){
        $.ajax({
            type: "GET",
            url: "admin/ajax.php",
            dataType: "json",
            data: {
                    'action': 'show_filter_selected'
                    , 'filter': model
                  },
            'success': function (data){
                show_catalogue(data);
            },
            'error': function (data){
                console.log('la requête n\'a pas aboutie');
            }
        })
    }

    /* Affiche les porduits */
    function show_catalogue(data){

        $('')

    }

</script>
