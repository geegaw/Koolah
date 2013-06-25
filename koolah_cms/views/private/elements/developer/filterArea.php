<div id="filterArea"  class="collapsible">
   <div class="commandBar">
        <h3>Filter</h3>
        <button type="button" class="toggle open">&#8211;</button>
   </div>
   <div class="collapsibleBody">
       <div class="heading">
            <h2>Filter</h2>
        </div>
        <div class="filterArea">    
            <fieldset>
                <label for="templateSearch">Filter:</label>
                <input type="text" id="templateSearch" placeholder="Filter" value="" />
            </fieldset>
            <fieldset id="filterTypes">
                <?php foreach( $types as $type ) : ?>
                <fieldset>
                    <input type="checkbox" id="filter<?php echo ucfirst($type); ?>" class="typeFilter" value="<?php echo $type; ?>s" checked="checked" >
                    <label for="filter<?php echo ucfirst($type); ?>"><?php echo $type; ?>s</label>
                </fieldset>
                <?php endforeach ?>
            </fieldset>
        </div>
        <div id="msgBlock" class="fullWidth"></div>
    </div>         
</div>