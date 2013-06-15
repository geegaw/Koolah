<div id="filterArea">
    <div id="filterAreaFilters">
        <div class="heading">
            <h2>Filters</h2>
        </div>
        <form method="post"  class="filterArea">
            <fieldset>
                <label for="templateSearch">Filter:</label>
                <input type="text" id="templateSearch" placeholder="Filter" value="" />
            </fieldset>
            <fieldset>
                <input type="submit" id="filterFilesGo" class="noreset" value="Go"/>
                <input type="submit" id="filterFilesReset" class="noreset" value="Reset"/>
            </fieldset>
         </form>
    </div>
    <div id="msgBlock"></div>
    
    <div id="addPagesArea">
        <fieldset>
            <button id="mkFolder">+ Folder</button>
        </fieldset>
        <?php if ( $pageTemplates->length() ): ?>
            <fieldset class="newPageWidgetBlock">
                <button class="newPageWidget" id="newPage">+ Page</button>
                <fieldset class="newPageWidgetOptions hide">
                    <select id="pageTemplateList" class="newPageWidgetOption">
                        <option value="no_selection">Choose a Template</option>
                        <?php 
                            foreach ($pageTemplates->templates() as $template)
                                echo '<option class="pageTemplate" value="'.$template->getID().'">'.$template->label->label.'</option>';
                        ?>     
                    </select>
                    <button class="cancel">X</button>
                </fieldset>
            </fieldset>
        <?php endif; ?>
        <?php if ( $widgetTemplates->length() ): ?>
            <fieldset class="newPageWidgetBlock">
                <a class="newPageWidget" id="newWidget" href="#">+ Widget</a>
                <fieldset class="newPageWidgetOptions hide">
                    <select id="widgetTemplateList" class="newPageWidgetOption">
                        <option value="no_selection">Choose a Template</option>
                        <?php 
                            foreach ($widgetTemplates->templates() as $template)
                                echo '<option class="widget" value="'.$template->getID().'">'.$template->label->label.'</option>';
                        ?>
                    </select>
                    <button class="cancel">X</button>
                </fieldset>
            </fieldset>
        <?php endif; ?>
    </div>
</div>