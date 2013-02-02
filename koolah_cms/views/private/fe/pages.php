<?php
    $title = 'pages';
    $js = array( 
        'objects/types/pages', 
        'objects/types/folders', 
        'fe/pages' 
    );
    $css = 'pages';
    include (ELEMENTS_PATH . "/header.php");
    
    $pageTemplates = new TemplatesTYPE();
    $pageTemplates->getPageTemplates();
    
    $widgetTemplates = new TemplatesTYPE();
    $widgetTemplates->getWidgetTemplates();
    
    //$root = new FolderTYPE();
    //$root->initRoot();
?>
<section id="pagesSection" class="fullWidth">
    <div id="main" class="center">

        <div id="pagesWidgets"  class="fullWidth">
            <div class="tabSection">
                <div id="tabs" class="tabLabels">
                    <div class="tab active"><a href="#pages">Pages</a></div>
                    <div class="tab"><a href="#widgets">Widgets</a></div>
                </div>
                
                <div id="list" class="tabsBody fullWidth">
                    <div id="pages" class="tabBody"></div>
                    <div id="widgets" class="tabBody hide"></div>    
                </div>
            </div>
            <div id="breadcrumbs" class="fullWidth">
                <div id="pagesBreadcrumbs" class="breadcrumbSection active">
                    <a class="breadcrumb" href="#">pages</a>
                </div>
                <div id="widgetsBreadcrumbs" class="breadcrumbSection hide">
                    <a class="breadcrumb" href="#">widgets</a>
                </div>
            </div>

        </div>

        <nav id="filterArea">
            <fieldset class="fullWidth">
                <label for="templateSearch">Filter:</label>
                <input type="text" id="templateSearch" placeholder="Filter" value="" />
            </fieldset>
            <fieldset class="fullWidth">
                <fieldset class="fullWidth">
                    <button id="mkFolder">New Folder</button>
                </fieldset>
                <?php if ( $pageTemplates->length() ): ?>
                    <fieldset class="fullWidth newPageWidgetBlock">
                        <button class="fullWidth newPageWidget" id="newPage">New Page</button>
                        <fieldset class="fullWidth newPageWidgetOptions hide">
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
                    <fieldset class="fullWidth newPageWidgetBlock">
                        <a class="fullWidth newPageWidget" id="newWidget" href="#">New Widget</a>
                        <fieldset class="fullWidth newPageWidgetOptions hide">
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
            </fieldset>
            <div id="msgBlock" class="fullWidth"></div>
        </nav>
    </div>
</section>
<?php include (ELEMENTS_PATH . "/footer.php"); ?>