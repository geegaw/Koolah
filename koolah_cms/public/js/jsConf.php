<?php
/**
 * jsConf
 * 
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */ 
/**
 * conf file to pass php constants to javascript
 * 
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\cms\public\js
 */ 

    global $VALID_IMAGES;
    global $VALID_DOCS;
    global $VALID_VIDS;
    global $VALID_AUDIO;
    global $VALID_FILES;    
?>
<script>
	var AJAX_GET_URL = '<?php echo AJAX_GET_URL; ?>';
	var AJAX_GET_ONE_URL = '<?php echo AJAX_GET_ONE_URL; ?>';
	var AJAX_UPLOAD_URL = '<?php echo AJAX_UPLOAD_URL; ?>';
	var AJAX_IMPORT_URL = '<?php echo AJAX_IMPORT_URL; ?>';
	var AJAX_SAVE_URL = '<?php echo AJAX_SAVE_URL; ?>';
	var AJAX_DEL_URL = '<?php echo AJAX_DEL_URL; ?>';
	var AJAX_DEACTIVATE = '<?php echo AJAX_DEACTIVATE; ?>';
	var AJAX_REACTIVATE = '<?php echo AJAX_REACTIVATE; ?>';
	
	var FOLDER_COLLECTION_ROOT = '<?php echo FOLDER_COLLECTION_ROOT; ?>';
	
	var UPLOADS_PATH = '<?php echo UPLOADS_PATH; ?>';
	var MAX_FILE_SIZE = '<?php echo MAX_FILE_SIZE; ?>';
	
	var VALID_IMAGES = ['<?php echo implode("', '", $VALID_IMAGES); ?>'];
	var VALID_DOCS = ['<?php echo implode("', '", $VALID_DOCS); ?>'];
	var VALID_VIDS = ['<?php echo implode("', '", $VALID_VIDS); ?>'];
	var VALID_AUDIO  = ['<?php echo implode("', '", $VALID_AUDIO ); ?>'];
	var VALID_FILES = ['<?php echo implode("', '", $VALID_FILES); ?>'];
	
	var TEMPLATE_FIELD_TYPES = ['<?php echo implode("', '", FieldTypeTYPE::getTypes()); ?>'];
	
	var FM_URL = "<?php echo FM_URL; ?>";
	var PREVIEW_URL = "<?php echo INTERNAL_PREVIEW_URL; ?>"; 
	
	var IMPORT_FILE_TYPES = ['<?php echo implode("', '", explode(',',  IMPORT_FILE_TYPES)); ?>'];
</script>