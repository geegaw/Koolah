<!-- fileFilters -->
<div id="fileFilters">
    <div class="heading">
        <h2>Filters</h2>
    </div>
    <form method="post"  class="filterArea"> 
        <fieldset class="filterInput">
            <label for="filterFileName">Search Files:</label>
            <input type="text" id="filterFileName" placeholder="File Name"/>
        </fieldset>
        <fieldset  class="filterInput">
            <label for="filterFileType">File Types</label>
            <select id="filterFileType">
                <option value="all">All File Types</option>
                <option value="Audio">Audio</option>
                <option value="Doc">Document</option>
                <option value="Image">Images</option>
                <option value="Vid">Videos</option>
            </select>
        </fieldset>
        <fieldset  class="filterInput">
            <label for="filterFileExt">Extension</label>
            <input type="text" id="filterFileExt" class="tagSearchInput" placeholder="Ext" />
        </fieldset>
        
        <fieldset  class="filterInput tag">
            <label for="filterFileTag">Tag</label>
            <input type="text" id="filterFileTag" class="tagSearchInput" placeholder="Tag" />
        </fieldset>
        
        <fieldset class="filterCommands">
            <input type="submit" id="filterFilesGo" class="noreset" value="Go"/>
            <input type="submit" id="filterFilesReset" class="noreset" value="Reset"/>
        </fieldset>
    </form>
    <div id="filesMsgBlock" class="msgBlock">&nbsp;</div>
</div>
<!-- /fileFilters -->