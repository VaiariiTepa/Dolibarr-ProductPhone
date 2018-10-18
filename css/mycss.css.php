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
 * \file    css/mycss.css.php
 * \ingroup mymodule
 * \brief   Example CSS.
 *
 * Put detailed description here.
 */

header('Content-Type: text/css');
?>

#table_productphone_raw>tbody>tr.selected>td{ background-color:#FFFFCC }
#table_product>tbody>tr.selected>td{ background-color: #FFFFCC }

#table_productphone_product>tbody>tr.selected>td{ background-color: #FFFFCC }

#header_accordion>div>b.selected { background-color: #00adee }

div#cadre { background-color: coral }


.vertical-menu {
/*    border: 3px black solid;*/
    width: 300px;
    margin-left: 75%;
/*    margin-top: 1%;*/
    position: absolute;
    background-color: lightgrey;
}

.vertical-menu select {
    background-color: #eee; /* Grey background color */
    color: black; /* Black text color */
    display: block; /* Make the links appear below each other */
/*    padding: 12px; /* Add some padding */*/
    text-decoration: none; /* Remove underline from links */
}

.vertical-menu select:hover {
    background-color: #ccc; /* Dark grey background on mouse-over */
}

.vertical-menu select.active {
    background-color: #4CAF50; /* Add a green color to the "active/current" link */
    color: white;
}

#first_row
{
    border: 1px solid gray;
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    background-color: whitesmoke;
    border-radius: 10px 10px 10px 10px;
    box-shadow: 1px 1px 12px #555;
}

#global_div
{
/*    border: 2px solid black;*/
}

#title_contenue
{
    background-color: #C0C0C0;
    border-radius: 10px;
    border: 1px solid gray;
    width: 80%;
    margin-left: 4%;
    margin-right: 4%;
    margin-top: 1%;
    margin-bottom: 1%;
    height: 150px;
}

.titre_accordion
{
    font-size: small;
    font-family: Palatino, URW Palladio L, serif;
}

.DeviceName
{
    font-size: small;
    font-family: Comic Sans MS, Comic Sans, cursive;
}

#bandeau
{
    display: flex;
    flex-wrap: wrap;
}

*
{
/*    border: 1px solid red;*/
}

#label_value
{
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
}



#ref_header
{
/*    border: 1px solid grey;*/
    min-width: 300px;
    max-width: 300px;
    text-align: center;
    display: block;
}

#label_ref
{
/*    background-color: #C0C0C0;*/
/*    display: block;*/
/*    align-content: center;*/
/*    border: 1px solid grey;*/
/*    border-radius: 1px 1px 10px 10px;*/
/*    min-width: 300px;*/
/*    max-width: 300px;*/
}

#reference
{
    text-align: center;
    display: block;
}

#forfait_header
{
    font-family: URW Chancery L, cursive;
    min-width: 300px;
    max-width: 300px;
    text-align: center;
    display: block;
/*    border: 1px solid grey;*/
}

#label_forfait
{
    display: block;
    align-content: center;
    background-color: #C0C0C0;
    border: 1px solid grey;
    border-radius: 1px 1px 10px 10px;
    min-width: 300px;
    max-width: 300px;
}

#forfait
{
    text-align: center;
    display: block;

}

#price_header
{
    /*margin-left: 45%;*/
/*    border: 1px solid gray;*/
    border-radius: 1px 10px 0px 0px;
    font-family: Impact, fantasy;
    width: 100px;
    text-align: center;
    display: block;
}

#color_header_value
{
    font-family: Luminari, fantasy;
    min-width: 120px;
    max-width: 120px;
}

#label_color
{
    background-color: #C0C0C0;
    border: 1px solid grey;
    border-radius: 1px 1px 10px 10px;
    width: 120px;
    display: block;
    align-content: center;
}

#color_header
{
/*    border: 1px solid gray;*/
    border-radius: 10px 0px 1px 1px;
    width: 120px;
    display: flex;
    flex-wrap: wrap;
    text-align: center;
}

#couleur
{
    text-align: center;
    display: block;
}

#label_price
{
    background-color: #C0C0C0;
    border: 1px solid grey;
    border-radius: 1px 1px 10px 10px;
    min-width: 100px;
    max-width: 100px;
    display: block;
    align-content: center;
}

#prix
{
    text-align: center;
    display: block;
}

#cadre
{
    display: flex;
    flex-wrap: wrap;
}

#accordion
{
    display: block;
    float: left;
    width: 85%;
}

.contenue
{
/*border: 1px solid black;*/
}

#contenue_accordion
{
    display: flex;
    flex-wrap: wrap;
}

#table_contenue_accordion
{
    width: 78%;
    margin-left: 25px;
}

#photo
{
    border: 1px solid black;
    width: 200px;
    height: 300px;
    display: block;
    float: left;
}