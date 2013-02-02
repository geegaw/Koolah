<!-- filesSection -->
<section id="filesSection" class="collapsible">
    <div class="commandBar">
        <h3>Files</h3>
        <button type="button" class="toggle open">&#8211;</button>
    </div>
    <div class="collapsibleBody">
        <?php include(ELEMENTS_PATH.'/files/fileList.php'); ?>
        <?php include(ELEMENTS_PATH.'/files/fileFilters.php'); ?>
        <?php if (isset($close)): ?>
            <button id="closeFileSection" class="close">Close</button>
        <?php endif; ?>  
    </div>
    <?php include(ELEMENTS_PATH.'/files/fileForm.php'); ?>
</section>
<!-- /filesSection -->