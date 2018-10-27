<?php
/* Copyright (C) 2007-2012 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) ---Put here your own copyright and developer email---
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
/**
 *  \file       dev/skeletons/skeleton_class.class.php
 *  \ingroup    mymodule othermodule1 othermodule2
 *  \brief      This file is an example for a CRUD class file (Create/Read/Update/Delete)
 *				Put here some comments
 */
// Put here all includes required by your class file
require_once(DOL_DOCUMENT_ROOT."/core/class/commonobject.class.php");
require_once(DOL_DOCUMENT_ROOT."../productphone/lib/fonoapi.lib.php");
require_once (DOL_DOCUMENT_ROOT."../productphone/lib/productPhone.lib.php");
//require_once(DOL_DOCUMENT_ROOT."/product/class/product.class.php");

/**
 *	Put here description of your class
 */
class ProductPhone //extends CommonObject
{
    public $element = 'product_phone_raw';
    public $table_element = 'product_phone_raw';
    public $fk_element = '';
    protected $childtables = array();    // To test if we can delete object
    protected $isnolinkedbythird = 1;     // No field fk_soc
    protected $ismultientitymanaged = 1;    // 0=No test on entity, 1=Test with field entity, 2=Test with link by societe
    public $product_phone_raw;

    // Champs
    var $id;
    var $brand;
    var $device_name;

    //Liste les champs llx_product_phone
    //soit 19 champs
    //Utiliser dans la methode "feed_productPhone_from_productPhoneRaw()"
    var $t_field_product_phone = array(
        'os_name', 'os_version', 'os_version_name', 'screen_resolution', 'phone_size'
    , 'phone_weight', 'primary_camera_resolution', 'secondary_camera_resolution', 'cpu_number', 'cpu_speed'
    , 'ram', 'interne_memory', 'connexion_type', 'battery_capacity', 'phone_color'
    , 'sim1_format', 'sim2_format', 'dual_sim' , 'fk_product_phone_raw'
    );

    // Liste les champs llx_product_phone_raw
    var $t_field = array('DeviceName', 'Brand', 'technology', 'gprs'
    , 'edge', 'announced', 'status', 'dimensions', 'weight'
    , 'sim', 'type', 'resolution', 'display_c', 'card_slot'
    , 'phonebook', 'call_records', 'camera_c', 'alert_types'
    , 'loudspeaker_', 'sound_c', 'wlan', 'bluetooth', 'gps'
    , 'radio', 'messaging', 'clock', 'alarm', 'games', 'languages'
    , 'java', 'features_c', 'battery_c', 'colors', '_2g_bands'
    , '_3_5mm_jack_', 'stand_by', 'talk_time', 'infrared_port'
    , 'sar_us', 'sar_eu', 'browser', 'memory_c', 'sensors', 'cpu'
    , 'internal', 'size', 'os', 'keyboard', 'primary_', 'video'
    , 'secondary', 'usb', 'chipset', 'network_c', 'body_c', 'speed'
    , '_3g_bands', 'features', 'loudspeaker', 'audio_quality'
    , 'protection', 'music_play', 'camera', 'gpu', 'multitouch'
    , '_4g_bands', 'display', 'nfc', 'performance', 'build'
    , 'price', 'sar');

    /**
     * ProductPhone constructor.
     */

    function __construct($db)
    {
        global $langs;
        $this->db = $db;
    }

    /**
     * Insert des données dans productPhone
     */
    function feed_productPhone($brand, $device)
    {
        // recupere les donnees brutes sur FonoApi
        $t_data = $this->get_raw_data($brand, $device);
        // insert les donnees brutes
        $this->insert_productPhoneRaw($t_data);

        // lance l alimentation de productPhone a partir des donnees brutes inseres
        $this->feed_productPhone_from_productPhoneRaw();

    }

    /**
     * Recupere les donnees brutes de fonoapi
     */
    function get_raw_data($brand, $device)
    {
        $t_getDevice = array();

        // initialisation de fonoapi
        $_fonoapi = new fonoapi();

        // recuperation des donnees fonoapi
        $t_getDevice = $_fonoapi->getDevice($device, $brand);

        return $t_getDevice;
    }

    /**
     * Insert des données dans productPhoneRaw￼Profilage [ En ligne ] [ Modifier ] [ Expliquer SQL ] [ Créer source PHP ] [ Actualiser ]
     */
    function insert_productPhoneRaw($t_data)
    {
        //Boucle - donnée fiche produit
        $t_sql_part = array();
        foreach ($t_data as $mobile) {

            //Boucle - champs product_phone_raw
            $t_val = array();
            $t_valUpd = array();
            foreach ($this->t_field as $f) {
                //Correspondance entre Champs product_phone_raw ET donnée fiche produit
                $t_val[] = (isset($mobile->{$f}) ? "'" . $mobile->{$f} . "'" : "''");
                $t_valUpd[] = '`' . $f . '`=VALUES(`' . $f . '`)';
            }

            // signal s il y a des champs non repertorie
            $t_field_unref = array_diff(array_keys((array)$mobile), $this->t_field);
            if ($t_field_unref) {
                dol_syslog('Product Phone API - Champs non pris en compte dans alimentation raw : ' . print_r($t_field_unref, TRUE), LOG_WARNING);
            }
            $t_sql_part[] = "(" . implode(', ', $t_val) . ")";
        }


        $sql = "INSERT INTO `" . MAIN_DB_PREFIX . "product_phone_raw` (`";
        $sql .= implode('`, `', $this->t_field);
        $sql .= "`) VALUES ";
        $sql .= implode(',', $t_sql_part);
        $sql .= " ON DUPLICATE KEY UPDATE " . implode(', ', $t_valUpd);

        $begin = $this->db->begin();
        $query = $this->db->query($sql);
        $commit = $this->db->commit();

    }

    /**
     * lance l alimentation de productPhone a partir des donnees brutes inseres
     * @param null $data
     */
    function feed_productPhone_from_productPhoneRaw($data = NULL)
    {
        $t_data_field_product_phone = array();
        $t_valUpd = array();

        foreach ($this->t_field_product_phone as $value) {
            $t_data_field_product_phone[] = $value;
            $t_valUpd[] = '`' . $value . '`=VALUES(`' . $value . '`)';
        }

        $sql = "INSERT INTO ".MAIN_DB_PREFIX."product_phone (";
        $sql.= implode(', ',$t_data_field_product_phone);
        $sql.= ")";
        $sql.= " SELECT DISTINCT";

        //os_name 1
        $sql.= " 
        CASE 
        WHEN (os LIKE \"%Microsoft%\") THEN \"Windows Phone\"
        ELSE SUBSTRING_INDEX(SUBSTRING_INDEX(os,',',1),' ',1) 
        END AS os_name";

        //version os 2
        $sql.= "
        ,CASE 
    	WHEN (os LIKE \"%v%\") THEN SUBSTRING_INDEX(SUBSTRING_INDEX(SUBSTRING_INDEX(SUBSTRING_INDEX(os,\" \",3),\" \",-2),\",\",-1),\"v\",-1)
        WHEN (os LIKE \"%Android 4%\") THEN SUBSTRING_INDEX(SUBSTRING_INDEX(os,\" \",2),\" \",-1)
        WHEN (os LIKE \"%Android 5%\") THEN SUBSTRING_INDEX(SUBSTRING_INDEX(os,\" \",2),\" \",-1)
        WHEN (os LIKE \"%Android 6%\") THEN SUBSTRING_INDEX(SUBSTRING_INDEX(os,\" \",2),\" \",-1)
        WHEN (os LIKE \"%Android 7%\") THEN SUBSTRING_INDEX(SUBSTRING_INDEX(os,\" \",2),\" \",-1)
        WHEN (os LIKE \"%Android 8%\") THEN SUBSTRING_INDEX(SUBSTRING_INDEX(os,\" \",2),\" \",-1)
        WHEN (os LIKE \"%Android 9%\") THEN SUBSTRING_INDEX(SUBSTRING_INDEX(os,\" \",2),\" \",-1)
        WHEN (os LIKE \"%Wear%\") THEN SUBSTRING_INDEX(SUBSTRING_INDEX(os,\" \",-3),\"(\",1)
        WHEN (os LIKE \"%Blackberry%\") THEN SUBSTRING_INDEX(SUBSTRING_INDEX(os,\" \",-1),\",\",1)
        WHEN (os LIKE \"%Blackberry%\" AND os LIKE \"%.%\") THEN SUBSTRING_INDEX(SUBSTRING_INDEX(os,\" \",-1),\",\",1)
        WHEN (os LIKE \"%Blackberry%\" AND os LIKE \"%, upgradable %\") THEN SUBSTRING_INDEX(SUBSTRING_INDEX(os,\" \",-2),\",\",1)
        WHEN (os LIKE \"%fox OS%\") THEN SUBSTRING_INDEX(os,\" \",-1)
        WHEN (os LIKE \"%Mango%\") THEN SUBSTRING_INDEX(os,\" \",4)
        WHEN (os LIKE \"%PocketPC%\") THEN \"Pochet PC\"
        WHEN (os LIKE \"%Microsoft%\" AND os LIKE \"% 8.1%\") THEN 8.1
        WHEN (os LIKE \"%Windows Phone 8,%\" AND os LIKE \"% 8,%\") THEN 8
        WHEN (os LIKE \"%Qind%\") THEN SUBSTRING_INDEX(SUBSTRING_INDEX(os,\" \",-2),\" \",1)
        WHEN (os LIKE \"%HP webOS%\") THEN SUBSTRING_INDEX(os,\" \",-1)
        WHEN (os LIKE \"%ios%\") THEN SUBSTRING_INDEX(os,\" \",-1)
        ELSE \"test_os_version\"
        END AS os_version";

        //version name 3
        $sql.="
        ,CASE
    	WHEN (os LIKE \"%Cupcake%\") THEN \"Cupcake\"
    	WHEN (os LIKE \"%Eclair%\") THEN \"Eclair\"
    	WHEN (os LIKE \"%Froyo%\") THEN \"Froyo\"
        WHEN (os LIKE \"%Gingerbread%\") THEN \"Gingerbread\"
        WHEN (os LIKE \"%HoneyComb%\") THEN \"HoneyComb\"
        WHEN (os LIKE \"%Ice Cream Sandwich%\") THEN \"IceCreamsandwich\"
    	WHEN (os LIKE \"%Jelly Bean%\") THEN \"Jelly_Bean\"
    	WHEN (os LIKE \"%KitKat%\") THEN \"KitKat\"
    	WHEN (os LIKE \"%Lollipop%\") THEN \"Lollipop\"
    	WHEN (os LIKE \"%Marshmallow%\") THEN \"Marshmallow\"
        WHEN (os LIKE \"%Nougat%\") THEN \"Nougat\"
        WHEN (os LIKE \"%Oreo%\") THEN \"Oreo\"
        WHEN (os LIKE \"%P%\" AND os LIKE \"%Android%\") THEN \"P\"
        WHEN (os LIKE \"%Wear%\") THEN \"Android Wear\"
        WHEN (os LIKE \"%iOS%\") THEN SUBSTRING_INDEX(SUBSTRING_INDEX(SUBSTRING_INDEX(os,\" \",2),\" \",-1),\",\",1)
        WHEN (os LIKE \"%watchOS%\") THEN SUBSTRING_INDEX(SUBSTRING_INDEX(SUBSTRING_INDEX(os,\" \",2),\" \",-1),\",\",1)
        WHEN (os LIKE \"%Symbian OS,%\") THEN SUBSTRING_INDEX(SUBSTRING_INDEX(os,\" \",3),\" \",-2)
        WHEN (os LIKE \"%Symbian%\") THEN SUBSTRING_INDEX(SUBSTRING_INDEX(SUBSTRING_INDEX(os,\" \",3),\" \",-2),\",\",1)
        WHEN (os LIKE \"%Windows%\") THEN SUBSTRING_INDEX(SUBSTRING_INDEX(SUBSTRING_INDEX(os,\" \",4),\" \",-3),\",\",1)
        WHEN (os LIKE \"%Blackberry%\" AND os LIKE \"%.%\") THEN SUBSTRING_INDEX(SUBSTRING_INDEX(os,\" \",-1),\",\",1)
        WHEN (os LIKE \"%Firefox OS, %\") THEN SUBSTRING_INDEX(os,\" \",1)
        WHEN (os LIKE \"%Firefox OS %\") THEN SUBSTRING_INDEX(os,\" \",1)
        WHEN (os LIKE \"%HP webOS%\") THEN SUBSTRING_INDEX(os,\" \",-2)
        WHEN (os LIKE \"%amazon%\") THEN SUBSTRING_INDEX(os,\" \",2)
        WHEN (os LIKE \"%Tencent OS%\") THEN SUBSTRING_INDEX(os,\" \",1)
        ELSE \"test_os_version_name\"
        END AS os_version_name
        ";

        //dimensions Ecrant 4
        $sql.= " 
        ,SUBSTRING_INDEX(resolution,' ',3) AS screen_resolution ";

        //resolution ecrant -- pouces 5
        $sql.= "
        ,SUBSTRING_INDEX(size,'inches',1) AS phone_size
        ";

        //poids 6
        $sql.="
        ,SUBSTRING_INDEX(weight,' ',1) AS phone_weight
        ";

        //camera arriere 7
        $sql.="
        ,SUBSTRING_INDEX(SUBSTRING_INDEX(primary_,'(',1),',',1) AS primary_camera_resolution
        ";

        //camera avant 8
        $sql.="
        ,SUBSTRING_INDEX(SUBSTRING_INDEX(secondary,'(',1),',',1) AS secondary_camera_resolution
        ";

        //nombre du cpu 9
        $sql.="
        ,CASE 
    	WHEN (cpu LIKE \"Octa-core%\") THEN 8
    	WHEN (cpu LIKE \"Dual-core% & Dual-core%\" OR cpu LIKE \"Dual-core% & Dual-core%\") THEN 8
    	WHEN (cpu LIKE \"Hexa-core%\") THEN 6
        WHEN (cpu LIKE \"Quad-core%\") THEN 4
        WHEN (cpu LIKE \"Dual-core%\") THEN 2
        WHEN (cpu LIKE \"1.2 GHz / 1.6 GHz%\") THEN 2
        ELSE 1
    	END AS cpu_number
        ";

        //vitesse du cpu 10
        $sql.="
        ,SUBSTRING_INDEX(cpu,' ',2) AS cpu_speed
        ";

        //ram 11
        $sql.="
        ,internal as ram
        ";

        //mémoire interne 12
        $sql.="
        ,SUBSTRING_INDEX(internal,',',1) AS interne_memory
        ";

        //connexions 13
        $sql.="
        ,CASE
		WHEN (_4g_bands LIKE \"%LTE%\") THEN \"4G\"
        WHEN (_3g_bands LIKE \"%HSDPA%\") THEN \"3G\"
        ELSE \"2G\"
	    END AS connexion_type
        ";

        //capaciter batterie 14
        $sql.="
        ,CASE
		WHEN (battery_c LIKE \"%Po%\") THEN SUBSTRING_INDEX(SUBSTRING_INDEX(battery_c,\" \",3),\" \",-2)
        ELSE SUBSTRING_INDEX(SUBSTRING_INDEX(battery_c,\" \",3),\" \",-2)
	    END AS battery_capacity
        ";

        //couleur 15
        $sql.="
        ,colors AS phone_color
        ";

        //sim1 format 16
        $sql.="
        ,CASE
		WHEN (sim LIKE \"%Dual%\") THEN 
			IF(sim LIKE \"%Stand-by%\"/* SI Stand-by J'ajoute la même donner dans sim2_type */,(SELECT sim as test_sim),2)
		WHEN (SELECT DISTINCT sim FROM llx_product_phone_raw 
				WHERE NOT EXISTS(SELECT sim LIKE \"%5%\" FROM llx_product_phone_raw))
					THEN \"test\"
        ELSE \"n/a\"
	    END AS sim1_format
        ";

        //sim2 format 17
        $sql.="
        ,CASE
		WHEN (sim LIKE \"%Dual%\") THEN SUBSTRING_INDEX(SUBSTRING_INDEX(sim,\"(\",-1),\",\",1)
		ELSE \"simple sim\"
        END AS sim2_format
        ";

        //dual sim 18
        $sql.="
        ,CASE
		WHEN (sim LIKE \"%Dual%\") THEN SUBSTRING_INDEX(SUBSTRING_INDEX(sim,\"(\",-1),\",\",1)
		ELSE \"dual-sim\"
        END AS dual_sim
        ";

        //fk_product_phone_raw 19
        $sql.="
        ,rowid as fk_product_phone_raw
        ";

        //FROM
        $sql.=" FROM " . MAIN_DB_PREFIX . "product_phone_raw";

        //ON DUPLICATE CASE
        $sql.= " ON DUPLICATE KEY UPDATE ".implode(', ', $t_valUpd);

//        print '<pre>';
//        print_r($sql);
//        print '</pre>';

        $resql = $this->db->query($sql);
        $commit = $this->db->commit($sql);

        var_dump($resql);
        var_dump($commit);

    }

    /**
     *  Recupere les produits||phones
     *  Stock dans un tableau les produits selon sa marque et son modele
     *
     * @param   $brand      string          Marque du produit
     * @param   $device     string          Modele du produit
     * @return  array       $t_data
     */
    function get_productPhoneRaw($brand = '', $device = '')
    {
        $t_data = array();

        // execution de la requete
        $sql = "SELECT * FROM " . MAIN_DB_PREFIX . "product_phone_raw";
        $sql .= " WHERE Brand LIKE '%" . $brand . "%' AND DeviceName LIKE '%" . $device . "%' ";
        $t_row = $this->db->query($sql);

        // regroupement des informations pour la ligne de t_data
        // insertion selon la marque pour brand et le modele pour DeviceName, qui fait que le produit est unique
        foreach ($t_row as $row) {
            $t_data[$row['Brand']][$row['DeviceName']] = (object)$row;
        }

        return $t_data;

    }

    /**
     * Recupere les donnee formater de llx_product_phone_raw join llx_product_phone en fonction du Id
     * @param $fk_productphone_raw
     * @return array
     */
    function fetch_productPhone_all($fk_productphone_raw)
    {
        $t_data = array();

        // execution de la requete
        $sql = "SELECT * FROM " . MAIN_DB_PREFIX . "product_phone_raw AS ppr";
        $sql .= " LEFT JOIN " . MAIN_DB_PREFIX . "product_phone AS pp ON ppr.rowid = pp.fk_product_phone_raw";
        $sql .= " WHERE pp.fk_product_phone_raw= " . $fk_productphone_raw;
        $t_row = $this->db->query($sql);

        foreach ($t_row as $row) {
            $t_data[] = $row;
        }
        return $t_data;

    }


    /**
     * Recupere les productPhone regroupe par brand||device
     */
    function get_productPhone($brand = '', $device = '')
    {
        $t_data = array();
        // execution de la requete
        $sql = "SELECT * FROM " . MAIN_DB_PREFIX . "product_phone_raw AS ppr";
        $sql .= " LEFT JOIN " . MAIN_DB_PREFIX . "product_phone AS pp ON ppr.rowid = pp.fk_product_phone_raw";
        $sql .= " WHERE ppr.Brand LIKE '%" . $brand . "%' AND ppr.DeviceName LIKE '%" . $device . "%'";
        $t_row = $this->db->query($sql);

        // regroupement des informations pour la ligne de t_data

        // insertion dans t_data[brand][device]
        foreach ($t_row as $row) {
            $t_data[$row['Brand']][$row['DeviceName']] = $row;
        }
        return $t_data;
    }

    /**
     * @author Vaiarii
     * effectue une recherche dans :
     * -- productphone_product
     * @param  array $t_param
     * @return array
     */
    function search_productphone($t_param)
    {
        if(is_string($t_param)){
          $t_param = array(
            'DeviceName' => $t_param,
          );
        }

        //affichage des caractéristique par défault
        $t_keyDevice = array(
            'DeviceName', 'announced','os_version_name'
        ,'os_version','cpu_number','os_name'
        ,'screen_resolution'
        );

        $t_keyDeviceFlip = array_flip($t_keyDevice);

        //affichage des caractéristique en fonction des filtre selectionner
        $t_paramKey = array();
        foreach ($t_param as $key=>$param) {
                    $t_paramKey[] =  $key;
        }

        $t_search_productphone = array();


        $t_keyAssociated = array(
          'ref_product', 'Prix_TTC','couleur','os_name'
            ,'os_version','os_version_name',''
            ,'screen_resolution','phone_size','phone_weight','primary_camera_resolution'
            ,'secondary_camera_resolution','cpu_number','cpu_speed','ram'
            ,'interne_memory','connexion_type','battery_capacity','sim1_format'
            ,'sim2_format','dual_sim','label','stock','lieu'
            ,'tosell'
        );

        // recupere les paramettre
        $t_where = array();
        foreach ($t_param AS $key=>$val){
          if(is_array($val) && $val){
            $t_subwhere = array();
            foreach( $val as $v ){
              $t_subwhere[] = '`'.$key.'` LIKE "%'.$v.'%"';
            }
            $t_where[$key] = '('.implode(' OR ', $t_subwhere).')';

          } else {
            $t_where[$key] = '`'.$key.'` LIKE "%'.$val.'%"';
          }
        }

        // ajoute les filtres
        $t_filterField = array_keys($t_param);
        $sql_filterField = ', `'.implode('`, `', $t_filterField).'`';

        $sql = "SELECT";
        //Nom des champs
        $sql .= " ppr.rowid AS DeviceId";
        $sql .= " ,ppr.DeviceName";
        $sql .= " ,ppp.fk_product AS id_product";
        $sql .= " ,p.ref AS ref_product";
        $sql .= " ,SUBSTRING_INDEX(p.price_ttc,'.',1) AS Prix_TTC";
        $sql .= " ,SUBSTRING_INDEX(SUBSTRING_INDEX(p.label, ')', 1),'(',-1) AS Couleur";
        $sql .= " ,p.label AS label";
        $sql .= " ,pp.os_name";
        $sql .= " ,pp.os_version";
        $sql .= " ,pp.os_version_name";
        $sql .= " ,pp.screen_resolution";
        $sql .= " ,pp.phone_size";
        $sql .= " ,pp.phone_weight";
        $sql .= " ,pp.primary_camera_resolution";
        $sql .= " ,pp.secondary_camera_resolution";
        $sql .= " ,pp.cpu_number";
        $sql .= " ,pp.cpu_speed";
        $sql .= " ,pp.ram";
        $sql .= " ,pp.interne_memory";
        $sql .= " ,pp.connexion_type";
        $sql .= " ,pp.battery_capacity";
        $sql .= " ,pp.sim1_format";
        $sql .= " ,pp.sim2_format";
        $sql .= " ,pp.dual_sim";
        $sql .= " ,ps.reel AS stock";
        $sql .= " ,e.lieu";
        $sql .= " ,ppr.announced AS announced";

        $sql .= " ,p.tosell AS tosell";

        $sql .= $sql_filterField;

        //Jointure
        $sql .= " FROM " . MAIN_DB_PREFIX . "product_phone_product AS ppp";
        $sql .= " LEFT JOIN " . MAIN_DB_PREFIX . "product AS p ON ppp.fk_product = p.rowid";
        $sql .= " LEFT JOIN " . MAIN_DB_PREFIX . "product_phone_raw AS ppr ON ppp.fk_product_phone_raw = ppr.rowid";
        $sql .= " LEFT JOIN " . MAIN_DB_PREFIX . "product_phone AS pp ON ppr.rowid = pp.fk_product_phone_raw";
        $sql .= " LEFT JOIN " . MAIN_DB_PREFIX . "product_stock AS ps ON p.rowid = ps.fk_product";
        $sql .= " LEFT JOIN " . MAIN_DB_PREFIX . "entrepot AS e ON ps.fk_entrepot = e.rowid";
        if($t_where){
          $sql .= " WHERE ".implode(' AND ',$t_where);
        }
        $sql .= " ORDER BY p.price_ttc ASC";

        $resql = $this->db->query($sql);

        /* ================================================================= */
        /*                        EXEMPLE -- NUM_ROWS()
                                 Construction du Tableau                     */
        /* ================================================================= */
        if ($resql) {

            $num_rows = $this->db->num_rows($resql);
            if ($num_rows > 0) {
                while ($row = (array)$this->db->fetch_object($resql)) {

                    //***************************************************//
                                //ZONE DE TEST


                    //Affichage par default
                    foreach($t_keyDevice as $k){
                        $t_search_productphone[$row['DeviceId']]['Device'][ $k ] = $row[ $k ];
                    }

                    //Affichage des caractéristique séléctionner
                    foreach ($t_paramKey as $paramKey){
                        $t_search_productphone[$row['DeviceId']]['Device'][ $paramKey ] = $row[ $paramKey ];
                    }





                    //***************************************************//



//                    //Affichage par default
//                    foreach($t_keyDevice as $k){
//                      $t_search_productphone[$row['DeviceId']]['Device'][ $k ] = $row[ $k ];
//                    }
//
//                    //Affichage des caractéristique séléctionner
//                    foreach ($t_paramKey as $paramKey){
//                        $t_search_productphone[$row['DeviceId']]['Device'][ $paramKey ] = $row[ $paramKey ];
//                    }

                    //Affichage des produit associer
                    foreach($t_keyAssociated as $k){
                      $t_search_productphone[$row['DeviceId']]['DeviceAssociated'][$row['id_product']][ $k ] = $row[ $k ];
                    }
                }
            }
        }
        /* ================================================================= */
        /* ================================================================= */

        return $t_search_productphone;
    }

    /**
     * @author Vaiarii
     * Affiche jointure
     * -- llx_product_phone_product
     * -- llx_product
     * -- llx_product_phone
     * @return array
     */
    function get_productphone_all()
    {
        $t_search_productphone = array();

        $sql = "SELECT * FROM " . MAIN_DB_PREFIX . "product_phone_product as ppp";
        $sql .= " left join " . MAIN_DB_PREFIX . "product as p on ppp.fk_product = p.rowid";
        $sql .= " left join " . MAIN_DB_PREFIX . "product_phone_raw as ppr on ppp.fk_product_phone_raw = ppr.rowid";
        $sql .= " left join " . MAIN_DB_PREFIX . "product_phone as pp on ppr.rowid = pp.fk_product_phone_raw";

        $pre_search = $this->db->query($sql);

        foreach ($pre_search as $resultat) {
            $t_search_productphone[] = $resultat;
        }
        return $t_search_productphone;
    }

    /**
     * @author Vaiarii
     * Cherche dans llx_product_raw
     * @param $search_brand
     * @return array
     */
    function search_product_phone_raw($search_brand)
    {
        $t_search_raw = array();

        $sql = "SELECT * FROM " . MAIN_DB_PREFIX . "product_phone_raw AS ppr";
        $sql .= " LEFT JOIN " . MAIN_DB_PREFIX . "product_phone AS pp ON ppr.rowid = pp.fk_product_phone_raw";

        $sql .= " WHERE ppr.Brand LIKE '%$search_brand%' OR ppr.DeviceName LIKE '%$search_brand%'";

        $pre_search = $this->db->query($sql);

        foreach ($pre_search as $resultat) {
            $t_search_raw[] = $resultat;
        }

        return $t_search_raw;
    }

    /**
     * @author Vaiarii
     * Cherche dans llx_product
     * @param $search_brand
     * @return array
     */
    function search_product($search_brand, $categorie)
    {
        $t_search_product = array();

        $sql = "SELECT * FROM " . MAIN_DB_PREFIX . "categorie_product as CP";
        $sql .= " LEFT JOIN " . MAIN_DB_PREFIX . "categorie as C on CP.fk_categorie = C.rowid";
        $sql .= " LEFT JOIN " . MAIN_DB_PREFIX . "product as P on CP.fk_product = P.rowid";
        $sql .= " WHERE ";

        if ($categorie > 0) {
            $sql .= " CP.fk_categorie =" . $categorie . " AND P.label LIKE '%$search_brand%' OR P.ref LIKE '%$search_brand%'";
        } else {
            $sql .= "P.label LIKE '%$search_brand%' OR P.ref LIKE '%$search_brand%'";
        }

        $res = $this->db->query($sql);

        foreach ($res as $resultat) {
            $t_search_product[] = $resultat;
        }
        return $t_search_product;
    }

    /**
     * @author Vaiarii
     * select le produit llx_product_phone_product -- SI attribué a product_phone,
     * alors affiché la jointure llx_product
     * @param $fk_product_phone_raw
     * @return array
     */
    function get_product_from_productphone($fk_product_phone_raw)
    {
        $t_search_product = array();

        $sql = "SELECT fk_product,ref,label";
        $sql .= " FROM " . MAIN_DB_PREFIX . "product_phone_product AS ppp";
        $sql .= " JOIN " . MAIN_DB_PREFIX . "product AS p";
        $sql .= " ON ppp.fk_product = p.rowid";
        $sql .= " WHERE fk_product_phone_raw = '" . $fk_product_phone_raw . "'";
//echo 'Affiche les Association '.$sql;
        $resql = $this->db->query($sql);

        foreach ($resql as $resultat) {
            $t_search_product[] = $resultat;
        }
        return $t_search_product;
    }

    /**
     * Formate les donnée de product_phone
     * @param $t_data
     */
    function format_raw_data($t_data)
    {
        $t_format_raw_data = array();


    }

    /**
     * @author Vaiarii
     * Insere ou Supprime $fk_product_phone_raw et $fk_product dans llx_product_phone_product
     * @param $fk_product_phone_raw
     * @param $fk_product
     */
    function set_or_unset_productphone_product($fk_product_phone_raw, $fk_product)
    {

        $sql = "SELECT fk_product_phone_raw,fk_product FROM " . MAIN_DB_PREFIX . "product_phone_product";
        $sql .= " WHERE fk_product_phone_raw=" . $fk_product_phone_raw . " AND fk_product=" . $fk_product;
        echo 'SELECT ' . $sql;
        $resql = $this->db->query($sql);
        $num_rows = $this->db->num_rows($resql);

        if ($num_rows > 0) {

            $sql = "DELETE FROM " . MAIN_DB_PREFIX . "product_phone_product";
            $sql .= " WHERE fk_product_phone_raw=" . $fk_product_phone_raw . " AND fk_product=" . $fk_product;
//            echo 'DELETE '.$sql;
            $this->db->query($sql);

        } else {

            $sql = "INSERT INTO " . MAIN_DB_PREFIX . "product_phone_product";
            $sql .= " (`fk_product_phone_raw`,`fk_product`) VALUES";
            $sql .= " (" . $fk_product_phone_raw . "," . $fk_product . ")";
//            echo 'INSERT '.$sql;
            $this->db->query($sql);
        }
    }

    /**
     * @author Vaiarii
     * suppression liste llx_productphone et llx_product from llx_product_phone_product
     * @param $fk_product_phone
     *
     */
    function unset_all_productphone_product($fk_product_phone)
    {

        $sql = "DELETE FROM " . MAIN_DB_PREFIX . "product_phone_product";
        $sql .= " WHERE fk_product_phone_raw=" . $fk_product_phone;

        $this->db->query($sql);

    }

    /**
     * @author Vaiarii
     * Test enregistre llx_productphone et llx_product from llx_product_phone_product
     * @param $fk_product_phone_raw
     * @param $t_fk_product
     */
    function set_all_productphone_product($fk_product_phone, $t_fk_product)
    {
        $t_val = array();

        foreach ($t_fk_product as $fk_product) {
            $t_val[] = "(" . $fk_product_phone . "," . $fk_product . ",2)";
        }

        $sql = "INSERT INTO `" . MAIN_DB_PREFIX . "product_phone_product`";
        $sql .= " (`fk_product_phone_raw`,`fk_product`,`fk_user`) VALUES " . implode(',', $t_val);
        $sql .= " ON DUPLICATE KEY UPDATE `fk_product`=VALUES(`fk_product`),`fk_product_phone_raw`=VALUES(`fk_product_phone_raw`)";
//        echo $sql;
        $this->db->query($sql);
    }

    /**
     * @author Vaiarii
     * Affiche les donnée dans llx_productphone_product
     * @param $t_fk_product_phone_raw
     * @return array
     */
    function gen_product_phone($t_fk_product_phone)
    {
        $t_getProductPhone = $this->get_product_from_productphone($t_fk_product_phone);
        $t_response['status'] = 'success';
        $t_ProductPhone = array();
        if ($t_getProductPhone) {
            foreach ($t_getProductPhone as $id => $productphone) {
                $t_ProductPhone[] = array_intersect_key(
                    $productphone,
                    array_flip(array('fk_product', 'ref', 'label'))
                );
            }
            $t_response['data'] = $t_ProductPhone;
            return $t_ProductPhone;
        } else {
            $t_response['status'] = 'error';
        }

    }

    /**
     * @author Vaiarii
     * Affiche les Value en fonction du champs selectionner dans llx_product_phone_raw
     * @param $filter_value
     * @return array
     */
    function gen_value_filter($filter_value){
        $t_filter_value = $this->get_filter_value($filter_value);

        $t_ProductPhone = array();

        if (isset($t_filter_value)) {
            foreach ($t_filter_value as $id => $productphone) {
                $t_ProductPhone[] = $productphone[$filter_value];
            }

            return $t_ProductPhone;
        }
    }

    /**
     * @author Vaiarii
     * Supprime l'élément selectionner dans llx_productphone_product
     * @param $fk_productPhone_raw
     * @param $fk_product
     */
    function unset_productphone_product($fk_productPhone_raw, $fk_product)
    {
        $sql = "DELETE FROM " . MAIN_DB_PREFIX . "product_phone_product";
        $sql .= " WHERE fk_product_phone_raw=" . $fk_productPhone_raw . " AND fk_product=" . $fk_product;
        $this->db->query($sql);
    }

    /**
     * @author Vaiarii
     * Récupère les filtre d'affichage
     * @return array
     */
    function get_filter()
    {

        $t_filter = array();

        $sql = "SELECT * FROM " . MAIN_DB_PREFIX . "c_product_phone_filter";
        $sql .= " WHERE active=1";
        $sql .= " ORDER BY `sort_order` ASC";
        $t_row = $this->db->query($sql);

        foreach ($t_row as $row) {
            if ($row) {
                $row['t_value'] = json_decode($row['value']);
            }
            $t_filter[ $row['field'] ] = $row;
        }
//        var_dump('get_filter ');
//        var_dump($t_filter);
        return $t_filter;
    }

    /**
     * @author Vaiarii
     * créer un nouveau filtre llx_c_product_phone_filter -- sans valeurs
     * @param $field
     * @param $type
     * @param $label
     * @param $active
     * @param $sort_order
     * @return int
     */
    function create_filter($field, $type, $label, $valeur, $active, $sort_order)
    {

        $sql = "INSERT INTO " . MAIN_DB_PREFIX . "c_product_phone_filter";
        $sql .= " (`field`,`type`,`label`,`value`,`active`,`sort_order`)";
        $sql .= " value";
        $sql .= " ('" . $field . "','" . $type . "','" . $label . "','" . $valeur . "','" . $active . "','" . $sort_order . "')";

        $result = $this->db->query($sql);
        if ($result > 0) {
            return 1;
        } else {
            return 0;
        }
    }


    /**
     * @author Vaiarii
     * créer un nouveau filtre dans llx_c_product_phone_filter
     * @param $field
     * @param $type
     * @param $label
     * @param $valeur
     * @param $active
     */
    function new_filter($field, $type, $label, $valeur, $active)
    {

        $sql = "INSERT INTO `" . MAIN_DB_PREFIX . "c_product_phone_filter`";
        $sql .= " (field,type,label,value,active)";
        $sql .= " VALUES";
        $sql .= " ('" . $field . "','" . $type . "','" . $label . "','" . $valeur . "','" . $active . "')";


        $this->db->query($sql);

    }

    /**
     * @author Vaiarii
     * Met a jours tous les champs llx_c_product_phone_filter
     * sauf le champ value
     * @param $rowid
     * @param $field
     * @param $type
     * @param $label
     * @param $active
     * @param $sort_order
     * @return int
     */
    function update_filter($rowid, $field, $type, $label, $active, $sort_order)
    {
        $sql = "UPDATE " . MAIN_DB_PREFIX . "c_product_phone_filter SET";
        $sql .= " field = '" . $field . "'";
        $sql .= " ,type = '" . $type . "'";
        $sql .= " ,label = '" . $label . "'";
        $sql .= " ,active = '" . $active . "'";
        $sql .= " ,sort_order = '" . $sort_order . "'";
        $sql .= " WHERE rowid = '" . $rowid . "'";

        $result = $this->db->query($sql);
        if ($result == true) {
            return 1;
        } else {
            return 0;
        }

    }

    /**
     * @author Vaiarii
     * Met a jours le champ Value dans llx_productphone
     * @param $rowid
     * @param $value
     * @return int
     */
    function update_filter_value($rowid, $value)
    {
        $sql = "UPDATE " . MAIN_DB_PREFIX . "c_product_phone_filter SET ";
        $sql .= " value = '" . $value . "'";
        $sql .= " WHERE rowid = '" . $rowid . "'";

        $result = $this->db->query($sql);

        if ($result == true) {
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * Return number of existing devices
     * Compte le nombre de marque dans la database
     * @return $nb_devices
     */
    function count_devices()
    {
        $t_dataDevices = array();

        $sql = "SELECT COUNT(*) AS nb_brands, SUM(p.nb_total_modele) AS nb_devices";
        $sql .= " FROM (SELECT COUNT(Brand) AS nb_total_modele";
        $sql .= " FROM " . MAIN_DB_PREFIX . "product_phone_raw AS ppr";
        $sql .= " GROUP BY ppr.Brand) AS p";

        $resql = $this->db->query($sql);

        if ($resql) {
            if ($this->db->num_rows($resql) > 0) {
                $row = (array)$this->db->fetch_object($resql);
                $t_dataDevices = $row;
            }
        }
        return $t_dataDevices;
    }

    /**
     * Upload l'image d'un produit dans llx_produt_phone
     * utiliser sur la page fiche.php
     * @param $image
     * @return int
     */
    function upload_image($t_image){

        $sql = "INSERT INTO " . MAIN_DB_PREFIX . "product_phone_picture";

        $sql.= " ('image_nom','img_taille','img_type','img_desc','img_blob','fk_product_phone_raw')";
//        print.= "VALUES ("..")";
        $result = $this->db->query($sql);
        if ($result == true){
            return 1;
        }else{
            return 0;
        }

    }

    /**
     * @author Vaiarii
     * Récupère le nom des champs dans llx_product_phone_raw
     * et ensuite les affiché dans une balise select
     * @return array
     */
    function get_field_filter()
    {

        $t_field_filter = array();
        $sql = "DESCRIBE " . MAIN_DB_PREFIX . "product_phone";

        $resql = $this->db->query($sql);

        if ($resql) {
            if ($this->db->num_rows($resql) > 0) {
                while ($row = (array)$this->db->fetch_object($resql)) {
                    $t_field_filter[] = $row;
                };
            }
        }
        return $t_field_filter;
    }

    /**
     * @author Vaiarii
     * Récupère les valeur du champs selectionner dans llx_product_phone
     * @param $filter_value
     * @return array
     */
    function get_filter_value($filter_value)
    {
        $t_filter_value = array();

        // tri a appliquer en fonction du champs
        $t_sql_order = array(
          'os_version' => 'INET_ATON(SUBSTRING_INDEX(CONCAT('.$filter_value.',".0.0.0"),".",4))',
//          'interne_memory' => 'INET_ATON(SUBSTRING_INDEX(CONCAT('.$filter_value.',".0.0.0"),".",4))',
        );

        $sql = "SELECT DISTINCT `" . $filter_value . "`";
        $sql.= " FROM " . MAIN_DB_PREFIX . "product_phone AS pp";
        $sql.= " LEFT JOIN " . MAIN_DB_PREFIX . "product_phone_raw AS ppr on pp.fk_product_phone_raw = ppr.rowid";
//        var_dump($sql);
        if( isset($t_sql_order[$filter_value]) ){
          $sql.= " ORDER BY " . $t_sql_order[$filter_value];
        } else {
          $sql.= " ORDER BY " . $filter_value;
        }
        //echo $sql;

        $resql = $this->db->query($sql);
        if ($resql) {
            if ($this->db->num_rows($resql) > 0) {
                while ($row = (array)$this->db->fetch_object($resql)) {
                    $t_filter_value[] = $row;
                }
            }
        }
        return $t_filter_value;
    }

    /**
     * TEST
     * Stock les valeur de manière succéssive
     * @param $value
     * @return array
     */
    function stock_value($_value)
    {

        $t_stock = array();

        if ($_value) {
            array_push($t_stock, $_value);
        }

        return $t_stock;

    }

    /**
     * @vaiarii
     * Permet de supprimer le filtre selectionner dans llx_c_product_phone_filter
     * @param $rowid
     * @return int
     */
    function delete_filter($rowid)
    {

        $sql = "DELETE FROM " . MAIN_DB_PREFIX . "c_product_phone_filter";
        $sql .= " where rowid= " . $rowid;

        $resql = $this->db->query($sql);

        if ($resql) {
            return 1;
        } else {
            return 0;
        }

    }


    /**
     * Permet d'afficher le catalogue en fonction du filtre selectionner
     */
    function show_filter_selected($filter)
    {


    }

    /**
     * récupère le label de toutes les catégorie
     * @return mixed
     */
    function get_categorie()
    {

        $t_categorie = array();

        $sql = " SELECT `label` FROM `" . MAIN_DB_PREFIX . "categorie`";

        $resql = $this->db->query($sql);
        if ($resql) {
            if ($this->db->num_rows($resql > 0)) {
                while ($row = (array)$this->db->fetch_object($resql)) {
                    $t_categorie[] = $row;
                };
            }
        }
        return $t_categorie;
    }

    /**
     * récupère les valeur dans llx_product_phone
     * @return array
     */
    function get_all_productPhone(){

        $t_productphone = array();

        $sql = " SELECT * FROM " . MAIN_DB_PREFIX ."product_phone ";

        $resql = $this->db->query($sql);

        if($resql){
            if($this->db->num_rows($resql > 0)){
                while ($row = (array)$this->db->fetch_object($resql)){
                    $t_productphone[] = $row;
                };
            }
        }

        return $t_productphone;
    }

    /**
     * Récupère les valeur du champs "value" de la table llx_c_product_phone_filter
     */
    function get_valueOfValue(){

        $t_filter_value = array();

        $sql = " SELECT value FROM " . MAIN_DB_PREFIX ."c_product_phone_filter ";

        $resql = $this->db->query($sql);

        if($resql){
            if($this->db->num_rows($resql > 0)){
                while ($row = (array)$this->db->fetch_object($resql)){
                    $t_filter_value[] = $row;
                };
            }
        }
        return $t_filter_value;
    }


}