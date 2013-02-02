<?php
    require( '../config/config.php' );
    
    include_once('../elements/objects/customMongo.php');
    $cmsMongo = new customMongo('cms');
	$status = $cmsMongo->status;
	
	if ( $status->success() ){
	    $new = true;	
	    include_once( '../elements/objects/types/TemplateTYPE.php' );
		
		if ( isset( $_POST['id']) && ($_POST['id'] != null)){
	        $new = false;	
	        $template = new TemplateTYPE($cmsMongo);
	        $template->getByID ($_POST['id'] );
		}
		elseif(isset( $_POST['type']) && ($_POST['type'] != null)){
			$template = new TemplateTYPE($cmsMongo, $_POST['type']);
		}
		else{
			$status->setFalse('insufficient information');
			echo json_encode( array('status'=>$status->msg) );
			return;
		}
	    
		if( $status->success() ){
		    if ( isset( $_POST['name']))
			   $template->label->label = $_POST['name'];		
			
			$template->sections->clear();
			if ( isset( $_POST['sections'])){
				$sections = $_POST['sections'];
				foreach( $sections as $section ){
					$templateSection = new TemplateSectionTYPE( $cmsMongo );
					$templateSection->name = $section['name'];
					//$templateSection->fields->clear();			
							
					if ( isset($section['fields']) && count($section['fields']) ){
						$fields = $section['fields'];
						
						foreach ( $fields as $jsonfield ){
							$field = new FieldTYPE( $cmsMongo );	
							if ( isset($jsonfield['id']) )
								$field->getByID( $jsonfield['id'] );
							else{
								if ( isset($jsonfield['name']) )	
									$field->setLabel( $jsonfield['name'] );
								if ( isset($jsonfield['type']) ){
									$type = new FieldTypeTYPE();
									$type->type = $jsonfield['type'];
									if ( isset($jsonfield['options']) )
										$type->options = $jsonfield['options'];
									$field->setType( $type );
								}
								if ( isset($jsonfield['required']) )	
									$field->required = $jsonfield['required'];
								if ( isset($jsonfield['many']) )	
									$field->many = $jsonfield['many'];
							}
							$templateSection->fields->append( $field );
						}
					}	
					$template->sections->append( $templateSection );
				}
			}
			$status = $template->save();
		}
	}	
    echo json_encode( array('status'=>$status->msg, 'id'=>$template->getID()) );    
?>
