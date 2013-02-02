<?php
    $title = 'template';
    $css = 'template';
    $js = array('objects/types/templates', 'fe/templates');
    include( ELEMENTS_PATH."/header.php" );
    
    $type = 'page';
	if ( isset($_REQUEST['templateType']))
		$type = $_REQUEST['templateType'];
    $id = null;
    if ( isset($_REQUEST['templateID']) )
       $id = $_REQUEST['templateID'];
    
	$customFields = new TemplatesTYPE( $cmsMongo );
    $q = array( 'templateType'=>'field' );
	$customFields->get(  $q );

	$types = FieldTypeTYPE::getTypes();
?>  


<section id="templateSection" class="fullWidth">
    <div id="backgroundFilter" class="hide">&nbsp;</div>        
    
    <input type="hidden" id="templateType" value="<?php echo $type?>" />
    <input type="hidden" id="templateID" value="<?php echo $id?>" />
    
    <!-- templatesSection -->
    <section id="templatesSection">
        
        <h2>
        	<?php 
        		if ($id)
        			echo 'Modify';
        		else
        			echo 'Create';
				echo " $type";
        	?>
        </h2>
        <!-- left -->
        <div id="templateInfo">
        	
        	<!-- newTemplateName -->
            <form id="newTemplateName" class="fullWidth">
                <fieldset id="nameField" >
                    <input type="text" id="templateName" class="required" placeholder="<?php echo ucfirst($type); ?> Name" value=""/>
                    <input type="hidden" id="templateNameRef" value=""/>
                </fieldset>                
            </form>
            <!-- /newTemplateName -->
            
            
            <div id="sections" class="tabSection fullWidth">
            	<?php if ( $type != 'field' ): ?>
            	<div id="tabNames" class="tabLabels fullWidth">
            	    <div class="tab active"><a href="#">General</a></div>
            	    <div id="addSection" class="tab"><a href="#">New Tab</a></div>
            	</div>
            	<?php endif; ?>
            	
            	<div id="general" class="tabsBody section fullWidth active">
            	    <div class="sectionBody fullWidth">
	            		<div class="fields fullWidth"></div>
	            		<div class="newField fullWidth hide"></div>
	            		<button type="button" class="addField">Add New Field</button>
	            		<?php if ( $type != 'field' ): ?><a href="#" class="delSection">delete section</a><?php endif ?>
            		</div>
            	</div>    
                
                
                <!-- newSectionName -->
                <div id="newSectionNameHolder" class="fullWidth hide">
                    <form id="newSectionName">
                        <fieldset id="sectionNameField" >
                            <input type="text" id="sectionName" class="required" placeholder="Section Name" value=""/>
                            <input type="submit" id="saveSectionName" class="hide" value="save" />
                        </fieldset>
                    </form>
                    <fieldset id="sectionNameConfirmCancel" >
                        <input type="submit" id="cancelNewSection" class="noreset" value="Cancel" />
                        <input type="submit" id="addNewSection" class="noreset" value="Add New Section" />
                        <input type="hidden" id="edittingSection" value="" />
                    </fieldset>
                </div>
                <!-- /newSectionName -->    	
            	        	
            </div>
            
            <!-- newFieldForm -->
            <div id="newFieldFormHolder" class="hide">
	            <form id="newFieldForm"  class="fullWidth">
	                <!-- newFieldCommands -->
	                <fieldset id="newFieldCommands" class="">
	                	
	                	<!-- newFieldCommandsMainCommands -->
	                	<fieldset id="newFieldCommandsMainCommands">
	                    	
	                    	<!-- mainInfo -->
	                    	<fieldset class="name">
	                        	<input type="text" class="required" id="newFieldName" placeholder="Field Name" />
	                        	<input type="hidden" id="newFieldNameRef" val="" />
	                        </fieldset>
	                        <fieldset class="type">
		                        <select id="fieldType" class="required">
		                        	<option value="no_selection">Type</option>
		                        	<?php
		                        		if ( $types ){
											foreach ($types as $type)
												echo '<option value="'.$type.'">'.$type.'</option>';
										}
		                        	?>
		                        </select>
	                        </fieldset>
	                    	<!-- /mainInfo -->
	                    	
	                    	<!-- furtherInfo -->
	                        <fieldset id="dropdown" class="hide furtherInfo">
		                        <label for="dropdownOptions">Choices:</label>
		                        <textarea class="required" id="dropdownOptions"></textarea>
		                        <div class="helpText">Simply press enter after each choice.</div>
		                    </fieldset>	                    
		                    <fieldset id="custom" class="hide furtherInfo">
		                        <select id="template">
		                        	<option value="no_selection">Choose Custom Field</option>
		                        	<?php
		                        		if( $customFields->templates() ){
											foreach ( $customFields->templates() as $customField){
												echo '<option value="'.$customField->getID().'">'.$customField->label->label.'</option>';	
											}
										}
		                        	?>
		                        </select>
		                    </fieldset>	                    
		                    <fieldset id="fileType" class="hide furtherInfo">
		                        <select id="fileTypeSelect">
		                        	<option value="no_selection">Choose File Type</option>
		                        	<option value="doc">Doc</option>
		                        	<option value="image">Image</option>
		                        	<option value="vid">Video</option>		                        	
		                        	<option value="audio">Audio</option>
		                        </select>
		                    </fieldset>
		                    <!-- /furtherInfo -->
		                    
	                	</fieldset>
	                	<!-- /newFieldCommandsMainCommands -->
	                	
	                	<!-- fieldCommandsRight -->
	                	<fieldset class="fieldCommandsRight">
	                    	<fieldset id="requiredMany">
		                    	<fieldset>
		                    		<input type="checkbox" id="isRequired" />
		                    		<label for="isRequired">required</label>
		                    	</fieldset>
		                    	<fieldset>
		                    		<input type="checkbox" id="many"/>
		                    		<label for="many">can be many</label>
		                    	</fieldset>
	                    	</fieldset>
	                    	<fieldset id="newFieldSaveCancel">
		                    	<input type="submit" class="add noreset" id="addNewFieldYes" value="Add"/>
		                        <input type="submit" class="cancel noreset" id="addNewFieldNo" value="Cancel"/>
		                    </fieldset>
	                	</fieldset>
	                	<!-- /fieldCommandsRight -->
	                	
	                </fieldset>
	                <!-- /newFieldCommands -->
	                <input type="hidden" id="edittingField" val="" />
	           </form>
           </div>
           <!-- /newFieldForm -->
                
        </div>
        <!-- /left -->
        
        <!-- right -->
        <div id="commands">                
            <fieldset>
                <fieldset><form action="."><input type="submit" id="reset" class="reset noreset" value="Reset" /></form></fieldset>
                <fieldset><input type="submit" id="save" class="save noreset" value="Save" /></fieldset>
            </fieldset>
            <div id="msgBlock" class="fullWidth"></div>
        </div>
        <!-- /right -->
        
    </section>
    <!-- /templatesSection -->
    
</section>
<?php include( ELEMENTS_PATH."/footer.php" ); ?>