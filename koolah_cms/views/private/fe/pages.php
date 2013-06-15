<?php
    $title = 'pages';
    $js = array( 
        'objects/types/templates',
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
<section id="pagesSection">
    <section id="main" class="collapsible">
        <div class="commandBar"><h3>Pages</h3><button type="button" class="toggle open">&#8211;</button></div>
        <div id="pagesSectionBody" class="collapsibleBody">
            <?php include(ELEMENTS_PATH.'/pages/tabs.php'); ?>
            <?php include(ELEMENTS_PATH.'/pages/filterArea.php'); ?>
        </div>       
    </section>
    <?php include( ELEMENTS_PATH."/common/recent.php" ); ?>
</section>
<?php include (ELEMENTS_PATH . "/footer.php"); ?>