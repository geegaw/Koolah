<!-- tagsSection -->
<section id="tagsSection" class="collapsible">
    <div class="commandBar fullWidth"><h3>Tags</h3><button type="button" class="toggle open">&#8211;</button></div>
    <div  id="tagSectionBody" class="collapsibleBody">
        <div class="heading fullWidth">
            <h2>Tags</h2>
            <button id="addTag"> + </button>
        </div>
        <div class="filterArea">
            <fieldset class="filterInput">
                <label for="searchTag">Search Tags:</label>
                <input type="text" id="searchTag" placeholder="Tag Name"/>
            </fieldset>
            <fieldset class="filterCommands">
                <input type="submit" id="searchTagGo" class="noreset" value="Go"/>
                <input type="submit" id="searchTagReset" class="noreset" value="Reset"/>
            </fieldset>
            <div class="legend"> 
        </div>
        <div id="tagList" class="list"><ul></ul></div>
        <div id="tagsMsgBlock" class="msgBlock"></div>
    </div>
</section>
<!-- /tagsSection -->


<!-- tagForm -->
<form id="tagForm" class="hide" method="post">            
    <input type="hidden" id="tagID" value=""/>
    <legend><span>New</span> Tag</legend>
    <fieldset  class="halfWidth">
        <label for="tagName"> Tag Name: </label>
        <input type="text" id="tagName" class="required" placholder="Tag Name" />
    </fieldset>
    
    <fieldset  class="halfWidth">
        <input type="submit" id="cancelSaveTag" class="cancel noreset" value="Cancel" />
        <input type="submit" id="saveTag" class="save noreset" value="Save" />
    </fieldset>
</form>    
<!-- /tagForm --> 