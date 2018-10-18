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
 * \file    admin/about.php
 * \ingroup Product Phone
 * \brief   Example about page.
 *
 * Put detailed description here.
 */

// Load Dolibarr environment

if (false === (@include '../../main.inc.php')) { require '../../../main.inc.php'; }
global $langs, $user;

// Libraries
require_once DOL_DOCUMENT_ROOT . "/core/lib/admin.lib.php";
require_once '../lib/mymodule.lib.php';
require_once '../lib/fonoapi.lib.php';
require_once "../class/productphone.class.php";
require_once ("../lib/productPhone.lib.php");

// Translations
$langs->load("productphone@productphone");

// Access control
if (! $user->admin) { accessforbidden(); }

$_key = new fonoapi($db);

// Parameters
$p_action = GETPOST('action');
$p_field = GETPOST('field');
$p_type = GETPOST('type');
$p_label = GETPOST('label');
$p_valeur = GETPOST('value');
$p_active = GETPOST('active');
$p_order = GETPOST('order');
$p_rowid = GETPOST('rowid');
$p_test = GETPOST('test');

/*
 * Actions
 */
if($p_action == 'saveapikey')
{
    global $conf;
    $p_apikey = GETPOST('apikey');
    dolibarr_set_const($db,'PHONE_PRODUCT_FONOAPI_KEY',$p_apikey,'chaine',0,'',$conf->entity);
}

if ($p_action == 'creer'){
    $_productPhone = new ProductPhone($db);
    $result = $_productPhone->create_filter($p_field,$p_type,$p_label,$p_valeur,$p_active,$p_order);
    $_get_filter = $_productPhone->get_filter();
    if ($result>0){
        setEventMessage('Filtre ajouté','mesgs');
    }else{
        setEventMessage('Echec de l\'ajout d\'un nouveau filtre','errors');
    }
}

if ($p_action == 'modifier'){
    $_productPhone = new ProductPhone($db);
    $result = $_productPhone->update_filter($p_rowid,$p_field,$p_type,$p_label,$p_active,$p_order);
    if ($result>0){
        setEventMessage('Filtre modifié','mesgs');
    }else{
        setEventMessage('Echec de la modification du filtre','errors');
    }
}

if ($p_action == 'insert_value'){
    $_productPhone = new ProductPhone($db);
    $result = $_productPhone->update_filter_value($p_rowid,$p_valeur);
    if ($result>0){
        setEventMessage('Filtre modifié','mesgs');
    }else{
        setEventMessage('Echec de la modification du filtre','errors');
    }
}

if ($p_action == 'supprimer'){
    $_productPhone = new ProductPhone($db);
    $result = $_productPhone->delete_filter($p_rowid);
    if ($result>0){
        setEventMessage('Filtre supprimé','mesgs');
    }else{
        setEventMessage('Echec de la suppression du filtre','errors');
    }
}

$_productPhone = new ProductPhone($db);
$_get_filter = $_productPhone->get_filter();

$t_field_filter = $_productPhone->get_field_filter();
/*
 * View
 */
$page_name = "ProductPhoneSetup";
llxHeader('', $langs->trans($page_name));

// Back Modules list
$linkback='<a href="'.DOL_URL_ROOT.'/admin/modules.php">'.$langs->trans('BackToModuleList').'</a>';
print load_fiche_titre($langs->trans($page_name), $linkback);

// Configuration header
$head = productPhoneAdminPrepareHead();
dol_fiche_head($head, $langs->trans("ProductPhoneSetup"), $langs->trans("Module750504Name"),0 ,'productphone@productphone');

//Style de présentation - fond bleue clair
print '<div class="tabBar">';

    print '<h2>'.$langs->trans("ProductPhoneSetup").'</h2>';


    /**************************Clé API****************************/
    print '<table class="noborder" width="100%">';
        print '<thead>';
            print '<tr class="liste_titre">';
                print '<th>'.$langs->trans("Parameters").'</th>'."\n";

                //*****header Vide pour faire de la place*****//
                print '<th align="right" width="30"></th>'."\n";
                print '<th align="right" width="30">Action</th>'."\n";
//                print '<th width="80">&nbsp;</th></tr>'."\n";
                //*****FIN header Vide pour faire de la place**//

        print '</thead>';
        print '<tbody>';
            print '<form method="GET" action="'.dol_buildpath('/productphone/admin/config.php', 1).'">';
                print '<input type="hidden" name="action" value="saveapikey">';
                print '<tr>';
                    print '<td>';
                        print '<p>'.$langs->trans("ProductPhoneToken").'</p>';
                    print '</td>';
                    print '<td width="40%" align="left">';
                        print '<input type="text" name="apikey" size="60" value="'.$conf->global->PHONE_PRODUCT_FONOAPI_KEY.'"/>';
                    print '</td>';
                    print '<td>';
                        print '<input type="submit" class="btn btn-success" value="Valider">';
                    print '</td>';
                print '</tr>';
            print '</form>';
        print '</tbody>';
    print '</table>';

    print '<br>';

    /**********************EXTRAFIELD--FILTER***********************/
    print '<form action="'.$_SERVER['PHP_SELF'].'?action=creer" method="POST">';
        print '<table class="noborder" width="100%">';
            print '<thead>';
                print '<tr class="liste_titre">';
                    print '<th width="16%">Ajouter un nouveau filtre de recherche</th>'; // f.langs
                    print '<th width="16%"></th>';
                    print '<th width="16%"></th>';
                    print '<th width="16%"></th>';
                    print '<th width="16%"></th>';
                    print '<th width="16%"></th>';
                    print '<th width="16%"></th>';
                print '</tr>';
            print '</thead>';
            print '<tbody>';
                print '<tr class="pair">';
                    print '<td width="16%">Field</td>'; // voir f.langs
                    print '<td width="16%">Type</td>'; // voir f.langs
                    print '<td width="16%">Label</td>'; // voir f.langs
                    print '<td width="16%">Valeur</td>'; // voir f.langs
                    print '<td width="16%">Active</td>'; // voir f.langs
                    print '<td width="16%">Ordre</td>'; // voir f.langs
                    print '<td width="16%">Action</td>'; // voir f.langs
                print '</tr>';
                print '<tr class="pair">';

                    //field
                    print '<td width="16%">';
                        print '<select id="select_field" name="field" value="'.$p_field.'">';
                            // == FIELD -- Nom de filtre == //
                            foreach ($t_field_filter as $field_filter){
                                print '<option value="llx_product_phone.'.$field_filter['Field'].'">'.$field_filter['Field'].'</option>';
                            }
                        print '</select>';
                    print '</td>';

                    //type de filtre (select/checkbox/input/button/radio)
                    // stocker les differents type d input
                    print '<td width="16%">';
                        print '<select name="type" value="'.$p_type.'">';
                            print '<option value="select">Select</option>';
                            print '<option value="checkbox">Checkbox</option>';
                            print '<option value="text">Textarea</option>';
                            print '<option value="radio">Radio</option>';
                        print '</select>';
                    print '</td>';

                    //label
                    print '<td width="16%">';
                        print '<textarea type="text" name="label" value="'.$p_label.'" required></textarea>';
                    print '</td>';

                    //valeur
                    print '<td>';
                        print '<textarea id="valeur" type="text" name="value" value="'.$p_valeur.'" required></textarea>';
                    print '</td>';

                    //active -- inactive
                    print '<td width="16%">';
                        print '<select name="active" value="'.$p_active.'">';
                            print '<option value="1">actif</option>';
                            print '<option value="0">inactif</option>';
                        print '</select>';
                    print '</td>';

                    //ordre de trie
                    print '<td width="16%"><input type="text" name="order" value="'.$p_order.'" required/></td>';

                    //bouton ajouter
                    print '<td width="16%"><input type="submit" value="Ajouter" class="btn btn-success"></td>';
                print '</tr>';
            print '</tbody>';
        print '</table>';
    print '</form>';

    /************************Affichage TABLE FILTRE************************/

    print '<table class="noborder" width="100%">';
        print '<thead>';
            print '<tr class="liste_titre">';
                print '<th>Field</th>';
                print '<th>Type</th>';
                print '<th>Label</th>';
                print '<th>Valeur</th>';
                print '<th>Active</th>';
                print '<th>Ordre</th>';
                print '<th>Modification</th>';
            print '</tr>';
        print '</thead>';
        print '<tbody>';
            $parity = TRUE;
            foreach ($_get_filter as $filter){
            $parity = !$parity;
            ///////////////////////////////////////////////////////////////////
            /*====== EN COURS DE MODIFICATION LLX_C_PRODUCTPHONE_FILTER======*/
            ///////////////////////////////////////////////////////////////////
            if ($p_action == 'form_modifier' && $filter['rowid'] == $p_rowid) {
                print '<form action="'.$_SERVER['PHP_SELF'].'?action=modifier" method="POST">';
                print '<tr class="'.($parity?'pair':'impair').'">';
                print '<input type="hidden" name="rowid" value="'.$filter['rowid'].'">';
                print '<td><input type="text" name="field" value="'.$filter['field'].'" required/></td>';
                print '<td width="16%">';
                    print '<select name="type" value="'.$filter['type'].'">';
                        print '<option value="select">select</option>';
                        print '<option value="checkbox">checkbox</option>';
                        print '<option value="text">input</option>';
                    print '</select>';
                print '</td>';
                print '<td><input type="text" name="label" value="'.$filter['label'].'" required/></td>';
                print '<td></td>';
                print '<td><input type="text" name="active" value="'.$filter['active'].'" required/></td>';
                print '<td><input type="text" name="order" value="'.$filter['sort_order'].'" required/></td>';
                print '<td>';
                print '<input type="submit" value="Valider" class="btn btn-success">';
                print '</td>';
                print '</tr>';
                print '</form>';

            }else{

            //////////////////////////////////////////////////////
            /*======= AFFICHAGE NORMAL LLX_C_PRODUCTPHONE_FILTER*/

            //////////////////////////////////////////////////////
                print '<tr class="'.($parity?'pair':'impair').'">';
                    print '<td>'.substr($filter['field'],18).'</td>';
                    print '<td>'.$filter['type'].'</td>';
                    print '<td>'.$filter['label'].'</td>';

                        print '<td>'.$filter['value'].'</td>';
                        print '<td>'.$filter['active'].'</td>';
                        print '<td>'.$filter['sort_order'].'</td>';

                        //BOUTON MODIFIER
                        print '<td>';
                        print '<a href="'.dol_buildpath('/productphone/admin/config.php', 1).'?action=form_modifier&rowid='.$filter['rowid'].'" class="btn btn-light">';
                        print 'filtre';
                        print '</a>';
                        print '<a href="'.dol_buildpath('/productphone/admin/config.php', 1).'?action=ajouter_valeur&rowid='.$filter['rowid'].'" class="btn btn-light">';
                        print 'valeur';
                        print '</a>';
                        print '<a href="'.dol_buildpath('/productphone/admin/config.php', 1).'?action=supprimer&rowid='.$filter['rowid'].'" class="btn btn-light">';
                        print 'supprimer';
                        print '</a>';
                        print '</td>';


                    //BOUTON AJOUTER VALEUR
                print '</tr>';
            print '</tbody>';
            }
        }
    print '</table>';

//div fermante "TAB BAR"
print '</div>';

// Page end
dol_fiche_end();
llxFooter();
?>

<script>

    $(document).ready(function(){
        var product_phone_raw;
        var selected_field;
        $('#select_field').on('change',function(){
            selected_field = $('#select_field').val();

            get_filter(selected_field);
        })
        //Ajouter un trigger
        $('#select_field').trigger('change');
    })

    function get_filter(selected_field){
        $.ajax({
            type:"GET",
            url:"ajax.php",
            dataType:"json",
            data:{
                'action': 'get_filter'
                , 'get_value_filter': selected_field},
                'success': function(data){
                show_value_of_filter(data);
                },
                'error': function(){
                console.log('la requête n\'a pas aboutie');
                }
        });
    }

    function show_value_of_filter(data){
        console.log(data.data.FieldValue);
        $('#valeur').val(
            data.data.FieldValue
        );
    }

</script>
