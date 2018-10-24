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
require_once DOL_DOCUMENT_ROOT.'/productphone/lib/productPhone.lib.php';

global $db, $langs, $user;

// Access control
//if ($user->socid > 0) accessforbidden();

// Load translation files required by the page
$langs->load("productphone@productphone");

// Initialization of object productphone
$_productPhone = new ProductPhone($db);


// Retrieve the different fields of the filters
$t_filter = $_productPhone->get_filter();
$t_search_productphone = array();

// Get parameters from filter
$action = GETPOST('action');
$t_param = array();
foreach($t_filter as $field){
  $t_param[ $field['field'] ] = GETPOST($field['field']);
}


$t_field = array();

if($t_field){
    if($p_filter_selected){
       //Si un nouveau filtre crée et selectionner, Alors rajouter a ce tableau
       $t_field[] = $p_filter_selected;
    }
}

// Generate the html of the different fields of the search filter
$t_input = generateInputHTMLofFilter($t_filter);

//var_dump($t_input);

// If action is equal to search else
if($action == 'search') {
    $t_search_productphone = $_productPhone->search_productphone($t_param);
}


/*
 * VIEW
 */

llxHeader('', $langs->trans('Product-Phone'));


print '<h1>Affichage liste PRODUCT-PHONE</h1>';

//affiché toutes les données d'un Mega var_dump

//    print '<pre>';
//    print_r($t_search_productphone);
//    print '</pre>';

/*//////////////////////////////////DIV OUVRANTE GLOBAL -- CONTAINERFLUID///////////////////////////////////////////*/
print '<div id="global_div">';
/*/////////////////////////////////////////////////////////////////////////////////////////////////////////////////*/
    /*************open div row***************/
    print '<div class="row_catalogue">';
        //*********************************DIV OUVRANTE CATALOGUE***********************************************//
        print '<div id="accordion">';
        //*******************************************************************************************************//
            if($t_search_productphone){
                foreach ($t_search_productphone as $key=>$value){
                    //==== TITRE ====//
                    print '<h4>';
                    print '<div id="bandeau">';
                        print '<div id="header_accordion">';
                            print '<div>';
                                print '<b class="DeviceName">'.$value['Device']['DeviceName'].' </b>';
                                print ' date de sortie: ' . $value['Device']['announced'];
                            print '</div>';
                        print '</div>';
                    print '</div>';
                        print '<div id="cadre_title">';
                            print '<div id="cadre">';
                                    //==== HEADER CARACTERISTIQUE == LABEL + VALUE filter ==//


                        //FOREACH permetant d'affiché les filtres qui on été selectionner

                    foreach ($t_param as $nkey=>$param){
                        if(!empty($param)){
                          $label = (!empty($t_filter[$nkey]) ? $t_filter[$nkey]['label'] : $nkey );
                          print $label.': '.$value['Device'][$nkey].' | ';
                        }
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

    //Formulaire filtre selectionner
    print '<form>';
    foreach($t_input as $input){
        print $input['label'];
        print '<br>';
        print $input['input'];
        print '<br>';
    }
    print '<button type="submit" name="action" value="search">Rechercher</button>';
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
        //ACCORDION
        $( "#accordion" ).accordion({
            collapsible: true
        });
    })


</script>
