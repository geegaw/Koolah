<div id="right">
    <div id="commandsSection" class="collapsible">
        <div class="commandBar">    
            <h3>Publication Commands</h3>
            <button type="button" class="toggle open">&#8211;</button>
        </div>    
        <div class="collapsibleBody">
            <div id="publicationCommands">
                <div id="publishUnpublish">
                    <a href="#" id="publish" class="<?php echo $published ?>">PUBLISHED</a>
                    <a href="#"id="unpublish" class="<?php echo $unpublished?>">OFFLINE</a>
                </div>
                <div id="publicationStatus">Current Status: <span class="status"><?php if ($page) echo $page->getPublicationStatus(); else echo 'Draft' ?></span></div>
                <div id="workflowCommands"><?php echo $template->mkWorkflow(); ?></div>
            </div>
            
            <div id="commands"  class="fullWidth">
                <input type="submit" value="reload" id="reload" class="reload" />
                <input type="submit" value="save" id="save" class="save" />
            </div>
        </div>
    </div>
    <div id="msgBlock" class="fullWidth"></div>
    <?php include (META_PATH . "/meta.php"); ?> 
</div>