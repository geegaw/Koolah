<?php
/**
 * folders
 * 
 * @ignore
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * folders
 * 
 * @TODO: install script not yet complete
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\system\core
 */ 
     
    $rootFolder = array(
        'label' => 'root',
        'ref'     =>FOLDER_COLLECTION_ROOT,
        'children' => array(
        ), 
    );
    
    $pagesFolder = array(
        'label' => 'pages',
        'ref'     =>FOLDER_COLLECTION_ROOT.'pages',
        'children' => array(
        ), 
    );
    
    $widgetsFolder = array(
        'label' => 'widgets',
        'ref'     =>FOLDER_COLLECTION_ROOT.'widgets',
        'children' => array(
        ), 
    );


?>