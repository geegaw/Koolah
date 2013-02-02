<form id="fileForm" class="hide" method="post" enctype="multipart/form-data">
    <input type="hidden" id="fileID" value="" />    
    <legend><span>New</span> File</legend>
    <div id="uploadPreview"><img src="" />&nbsp;</div>
    <div id="fileFormArea">
        <fieldset>
            <label for="fileName">Name</label>
            <input type="text" id="fileName" class="required" value="" placeholder="Name" />
        </fieldset>
        
        <fieldset class="hide">
            <label for="fileAlt">Alt Text:</label>
            <input type="text" id="fileAlt" value="" placeholder="Alt Text:" />
        </fieldset>
        
        <fieldset>
            <label for="fileDescription">Description:</label>
            <textarea id="fileDescription" class="fullWidth"></textarea>
        </fieldset>
        
        <fieldset>
            <label for="fileTagAdd">Tags:</label>
            <input type="text"  id="fileTagAdd" class="fullWidth" placeholder="Add tag"  value="" />                
            <div id="fileTagArea" class="fullWidth pods"></div>
        </fieldset>
        
        <fieldset id="uploadField">            
            <label for="fileInput">Upload File:</label>
            <input type="file" id="fileInput" name="file" value=""/>
            <progress id="fileUploadProgress" class="fullWidth hide" max="100" value="0"></progress>
        </fieldset>     
        
        <fieldset class="commands">
            <input type="submit" id="cancelSaveFile" class="cancel noreset" value="Cancel" />
            <input type="submit" id="saveFile" class="save noreset" value="Save"/>     
            <div id="fileFormMsgBlock">&nbsp;</div>      
        </fieldset>
    </div>                    
</form>
