<div id="templates">
    <?php foreach( $types as $type ) :?>
        <!-- typeSection -->
        <div id="<?php echo $type; ?>sSection" class="tabSection collapsible">
            <div class="commandBar">
                <h3><?php echo ucfirst($type); ?>s</h3>
                <button type="button" class="toggle open">&#8211;</button>
           </div>
           <div class="collapsibleBody">    
                <div class="heading">
                    <h2><?php echo ucfirst($type); ?>s</h2>
                    <a href="template/?templateType=<?php echo $type; ?>" class="add">+</a>
                </div>
                <div id="<?php echo $type; ?>sList" class="templateList list"><ul></ul></div>
                <div class="import"><button type="button">&#8593;</button></div>
            </div>
        </div>
        <!-- /typeSection -->
    <?php endforeach ?>
    <input type="hidden" value="" id="activeTemplate" />
</div>