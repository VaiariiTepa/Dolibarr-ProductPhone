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
    'Nom Version'=>'os_version_name',
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


print '<table summary="" width="100%" border="0" class="notopnoleftnoright" style="margin-bottom: 2px;">';
    print '<tbody>';
        print '<tr>';
            print '<td class="nobordernopadding hideonsmartphone" width="40" align="left" valign="middle">';
                print '<img src="/erp-vaiarii/theme/eldy/img/title.png" border="0" alt="" title="" id="pictotitle">';
            print '</td>';
            print '<td class="nobordernopadding" valign="middle">';
                print '<div class="titre">Affichage liste PRODUCT-PHONE</div>';
            print '</td>';
        print '</tr>';
    print '</tbody>';
print '</table>';

print '<div class="fiche">';
    print '<div class="fichecenter">';
        print '<div class="fichehalfleft">';
            /* Assenceur catalogue */
            print '<div class="assenceur_catalogue" style="overflow: scroll; height: 800px;">';
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
                                        //FOREACH permetant d'affiché dans les caractéristique
                                        //les filtres qui on été selectionner, mais n'affiche pas
                                        //les filtres qui sont déja affiché par défaut
                                        foreach ($t_param as $nkey=>$param){
                                            if(!empty($param)){
                                                if ($param !== $value['Device']['os_name']
                                                    && $value['Device']['os_version_name']
                                                    && $param !== $value['Device']['os_version']
                                                    && $param !== $value['Device']['cpu_number']
                                                    && $param !== $value['Device']['screen_resolution']){
                                                    $label = (!empty($t_filter[$nkey]) ? $t_filter[$nkey]['label'] : $nkey );
                                                    //lire le nom des caractéristique par default
                                                    print $label.': '.$value['Device'][$nkey].' | ';
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
            print '</div>';
        /* Fermeture Div FicheHalfLeft */
        print '</div>';


        /* Début fiche half right */
        print '<div class="fichehalfright">';

            // LATERAL BAR SEARCH / FILTER OF CATALOG
            print '<div class="vertical-menu">';

                //Permet de remettre a Zero le filtre
                print '<form action="index.php">';
                    print '<input type="submit" name="action" value="Reset">';
                print '</form>';

                /* div form filtre */
                print '<div class="filtre">';
                        //Formulaire filtre selectionner
                        print '<form>';
                        if($t_input){
                            foreach($t_input as $input){
                                //var_dump($input);
                                print $input['label'];
                                print '<br>';
                                print $input['input'];
                                print '<br>';
                            }
                        }
                        print '<button type="submit" name="action" value="search">Rechercher</button>';
                        print '</form>';

                /* Fin Div Form Filter */
                print '</div>';

            print '</div>';
        /* Fin Fichehalfright */
        print '</div>';

    /* Fin fiche center */
    print '</div>';

/* Fin Div Fiche */
print '</div>';


// End of page
llxFooter();
?>
<script>

    $(document).ready(function(){
        //ACCORDION
        $( "#accordion" ).accordion({
            collapsible: true,
            heightStyle: "fill"
        });

        $("#other").click(function(){
            $(".assenceur_catalogue").scroll();
        })

    })


</script>
