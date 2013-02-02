<?php
    $templateID = cmsToolKit::getParam('template', $_GET);
    $pageID     = cmsToolKit::getParam('id', $_GET);
    
    $page = null;
    if ( $pageID ){
        $page = new PageTYPE();
        $page->getByID( $pageID );
        $templateID = $page->getTemplateID();
    }
    
    $template = null;
    if ( $templateID ){
        $template = new TemplateTYPE();
        $template->getByID( $templateID );
    }
    
    $published = 'inactive';
    $unpublished = 'active';
    if ( $page && $page->getPublicationStatus()=='published' ){
        $published = 'active';
        $unpublished = 'inactive';
    }
    
    
    if ( $page ){
        $title = $page->label->label;
        $meta = $page->getMeta();
    }
    elseif( $template ){
        $title = "New ".$template->label->label;
        $meta = $template->getMeta();
    }
    else
        $title = 'New Page/Widget';
    
    $metaFile = '/page/meta'; 
    $js = array(
        'objects/types/pages',
        'objects/types/uploads',
        'objects/types/tags', 
        'objects/types/ratios',
        "plugins/ckeditor/ckeditor", 
        "plugins/ckeditor/adapters/jquery",
        'fe/page', 
        
    );
    $css = 'page';
    include (ELEMENTS_PATH . "/header.php");
?>


<input type="hidden" id="templateID" value="<?php echo $templateID; ?>" />
<input type="hidden" id="templateType" value="<?php if ( $template ) echo $template->getType(); ?>" />
<input type="hidden" id="pageID" value="<?php echo $pageID ?>" />

<section id="page" class="<?php $type ?>">

<?php //debug::printr( $page, 1 ); ?>
    <?php include( ELEMENTS_PATH.'/page/templateInfo.php' ); ?>
    <?php include( ELEMENTS_PATH.'/page/rightSection.php' ); ?>
    <?php $close = true; include( ELEMENTS_PATH.'/page/fileForm.php' ); ?>
</section>
<div style="clear:both"><?php //debug::printr($page); ?></div>
<?php include (ELEMENTS_PATH . "/footer.php"); ?>