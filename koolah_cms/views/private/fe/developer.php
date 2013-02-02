<?php
    /***
     * Page Logic
     */
    $title = 'developer';
    $js = array('objects/types/templates', 'fe/developer');
    $css = 'developer';
    include (ELEMENTS_PATH . "/header.php");
    
    $types = TemplateTYPE::getTypes();
    /***/
?>
<section id="developer">

    <div id="filterArea">
        <fieldset>
            <label for="templateSearch">Filter:</label>
            <input type="text" id="templateSearch" placeholder="Filter" value="" />
        </fieldset>
        <ul>
            <?php foreach( $types as $type ) : ?>
            <li class="type">
                <input type="checkbox" id="filter<?php echo ucfirst($type); ?>" value="<?php echo $type; ?>s" checked="checked" >
                <label for="filter<?php echo ucfirst($type); ?>"><?php echo $type; ?>s</label>
            </li>
            <?php endforeach ?>
        </ul>
        <div id="msgBlock" class="fullWidth"></div>
    </div>
    
    <div id="templates">
        <?php foreach( $types as $type ) :?>
            <!-- typeSection -->
            <div id="<?php echo $type; ?>sSection" class="tabSection">
                <div class="heading fullWidth">
                    <h2><?php echo ucfirst($type); ?>s</h2>
                    <a href="template/?templateType=<?php echo $type; ?>" class="add">+</a>
                </div>
                <div id="<?php echo $type; ?>sList" class="templateList"><ul></ul></div>
            </div>
            <!-- /typeSection -->
        <?php endforeach ?>
        <input type="hidden" value="" id="activeTemplate" />
    </div>

</section>
<?php include (ELEMENTS_PATH . "/footer.php"); ?>