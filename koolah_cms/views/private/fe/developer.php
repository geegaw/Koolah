<?php
    /***
     * Page Logic
     */
    $title = 'developer';
    $js = array('objects/types/templates', 'fe/developer', 'objects/elements/tools/importFile');
    $css = 'developer';
    include (ELEMENTS_PATH . "/header.php");
    
    $types = TemplateTYPE::getTypes();
    /***/
?>
<section id="developer">
    <?php include (ELEMENTS_PATH . "/developer/filterArea.php"); ?>
    <?php include (ELEMENTS_PATH . "/developer/templateList.php"); ?>
    <?php include (ELEMENTS_PATH . "/common/importForm.php"); ?>
</section>
<?php include (ELEMENTS_PATH . "/footer.php"); ?>