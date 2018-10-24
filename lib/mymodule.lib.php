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
 * \file    lib/mymodule.lib.php
 * \ingroup mymodule
 * \brief   Example module library.
 *
 * Put detailed description here.
 */

/**
 * Prepare admin pages header
 *
 * @return array
 */
function mymoduleAdminPrepareHead()
{
	global $langs, $conf;


    $langs->load("productphone@productphone");

    $h = 0;
    $head = array();

    $head[$h][0] = dol_buildpath("/productphone/admin/manage.php", 1);
    $head[$h][1] = $langs->trans("Gestion des fiches");
    $head[$h][2] = 'correspondance';
    $h++;
    $head[$h][0] = dol_buildpath("/productphone/admin/import.php", 1);
    $head[$h][1] = $langs->trans("Importation des fiches");
    $head[$h][2] = 'ficheproduit';
    $h++;
    $head[$h][0] = dol_buildpath("/productphone/admin/config.php", 1);
    $head[$h][1] = $langs->trans("Configuration du module");
    $head[$h][2] = 'configuration';
    $h++;


    // Show more tabs from modules
	// Entries must be declared in modules descriptor with line
	//$this->tabs = array(
	//	'entity:+tabname:Title:@mymodule:/mymodule/mypage.php?id=__ID__'
	//); // to add new tab
	//$this->tabs = array(
	//	'entity:-tabname:Title:@mymodule:/mymodule/mypage.php?id=__ID__'
	//); // to remove a tab
	complete_head_from_modules($conf, $langs, $object, $head, $h, 'mymodule');

	return $head;
}