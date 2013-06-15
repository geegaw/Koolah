<?php if (isset($meta)): ?>
    <?php
        $creator = cmsToolKit::getUser( $meta->creationData->created_by );
        $lastMofidied = $meta->modificationHistory->lastModified();
        if ( $lastMofidied )
            $lastMofidiedBy = cmsToolKit::getUser( $lastMofidied->modified_by );
    ?>
    
    <div id="meta" class="collapsible">
        <div class="commandBar">    
            <h3>Meta Data</h3>
            <button type="button" class="toggle open">&#8211;</button>
        </div>    
        <div class="collapsibleBody">
            <?php if (isset($lastMofidiedBy)): ?>
                <div class="grouping">
                    <div> <span>Last Modified By:</span><span><?php echo cmsToolKit::displayUser($creator); ?></span> </div>
                    <div> <span>Last Modified On:</span><span><?php echo koolahToolKit::displayDate($lastMofidied->modified_at); ?></span> </div>
                </div>
            <?php endif; ?>
            <div class="grouping">
                <div> <span>Created By:</span><span><?php echo cmsToolKit::displayUser($creator); ?></span> </div>
                <div> <span>Created On:</span><span><?php echo koolahToolKit::displayDate($meta->creationData->created_at); ?></span> </div>
            </div>    
            <?php if (isset($metaFile)) include( META_PATH.$metaFile.'.php' ); ?>
        </div>
    </div>
<?php endif; ?>