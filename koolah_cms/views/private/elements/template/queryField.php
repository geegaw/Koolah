<fieldset id="queryType" class="hide furtherInfo">
    <fieldset>
        <select id="queryTemplateType" class="required noReset">
            <option value="no_selection">Choose Template Type</option>
            <option value="page">Page</option>
            <option value="widget">Widget</option>
        </select>
        
        <select id="queryTemplateId" class="required hide">
            <option value="no_selection">Choose Template</option>
        </select>
    </fieldset>
    
    <fieldset><button type="button" id="addCondition" class="hide">+ Add Condition</button></fieldset>
    
    <fieldset id="queryConditionals"></fieldset>
</fieldset>

<fieldset id="queryCondition" class="queryCondition hide">
    <fieldset class="queryBoolean hide">
        <select class="queryBooleanFields">
            <option value="and">And</option>
            <option value="or">Or</option>
        </select>
    </fieldset>          
    
    <fieldset class="notFieldset">
        <input type="checkbox" value="0" class="not" />
        <label>Not</label>
    </fieldset>
    
    <fieldset>
        <select class="queryTemplateFields required">
            <option value="no_selection">Choose Field</option>
        </select>
        <select class="queryTemplateFieldComparisonOperator">
            <optgroup label="Standard">
                <option value="e">Equals</option>
                <option value="gt">Greater Than</option>
                <option value="gte">Greater Than Equals</option>
                <option value="lt">Less Than</option>
                <option value="lte">Less Than Equals</option>
            </optgroup>
            <optgroup label="Advanced">
                <option value="empt">Empty</option>
                <option value="re">Regex</option>
            </optgroup>
        </select>
        <input type="text" class="queryTemplateFieldExpr required " value="" placeholder="" />
    </fieldset>
    
    <fieldset><button type="button" class="remove">remove</button></fieldset>
</fieldset>

