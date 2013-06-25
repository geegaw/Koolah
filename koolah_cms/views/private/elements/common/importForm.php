<form id="importForm" class="form hide">
    <legend>Import</legend>
    <fieldset>
        <label for="importFile">Upload File:</label>
        <input type="file" id="importFile" name="importFile" value=""/>
        <progress class="hide" max="100" value="0"></progress>
    </fieldset>
    
    <fieldset  id="importFormCommands">
        <input type="submit" class="cancel noreset" value="Cancel" />
        <input type="submit" class="save noreset" value="Save" />
    </fieldset>
    
    <div id="importFormMsgBlock"></div>
</form>
