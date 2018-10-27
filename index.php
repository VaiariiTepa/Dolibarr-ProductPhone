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

//tableaux valeur par défault
$t_paramKey = array(
    'Nom OS'=>'os_name',
    'Nom Vresion'=>'os_version_name',
    'Numéro Version'=>'os_version',
    'Nombre Coeur'=>'cpu_number',
    'Résolution écrant'=>'screen_resolution',
);

// Generate the html of the different fields of the search filter
$t_input = generateInputHTMLofFilter($t_filter);


// If action is equal to search else
if($action == 'search') {
    $t_search_productphone = $_productPhone->search_productphone($t_param);
}

//Si bouton reset selectionner, alors remetre a zero le filtre d'affichage
if($action == 'reset'){
    $t_param = array();
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
                        print '<div>';

                        //Affichage par default avec mis en page spéciaux
                        print '<b class="DeviceName">'.$value['Device']['DeviceName'].' </b>';
                        print ' date de sortie: ' . $value['Device']['announced'];

                        print '</div>';
                        print '<div id="cadre_title">';
                            print '<div id="cadreDefault">';
                                //Affichage par défault sans mis en page spécial
                                foreach($t_paramKey as $fkey=>$paramKey){
                                    if ($paramKey !== 'DeviceName' && $paramKey !== 'announced'){
                                        print $fkey.': '.$value['Device'][$paramKey].' | ';
                                    }
                                }
                            print '</div>';
                            print '<div id="cadre">';
                                //FOREACH permetant d'affiché les filtres qui on été selectionner
                                foreach ($t_param as $nkey=>$param){
                                    if(!empty($param)){
                                        foreach ($value['Device'] as $key => $Pvalue){
                                            var_dump($value['Device'][$key]);
                                            if ($Pvalue !== $value['Device'][$key]){
                                                $label = (!empty($t_filter[$nkey]) ? $t_filter[$nkey]['label'] : $nkey );
                                                //lire le nom des caractéristique par default
                                                print $label.': '.$value['Device'][$key].' | ';
                                            }
                                        }
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
                                    if($ref['tosell'] === '1' ){
                                        $parity =! $parity;
                                        print '<tr class="'.($parity?'pair':'impair').'">';
                                            print '<td width="25%">';
                                                print $ref['ref_product'];
                                            print '</td>';
                                            print '<td width="25%">';
                                                print $ref['label'];
                                            print '</td>';
                                            print '<td width="25%">';
                                                print price($ref['Prix_TTC']).' '.$langs->trans('SellingPriceTTC');
                                            print '</td>';
                                        print '</tr>';
                                    }
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

    //Permet de remettre a Zero le filtre
    print '<form action="index.php">';
        print '<input type="submit" name="action" value="Reset">';
    print '</form>';

    //Formulaire filtre selectionner
    print '<form>';
    if($t_input){
        foreach($t_input as $input){
            print $input['label'];
            print '<br>';
            print $input['input'];
            print '<br>';
        }
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
