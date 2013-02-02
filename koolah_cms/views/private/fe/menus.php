<?php
    $title = 'Menus';
    $js = array(
                'objects/types/Menus',
                'fe/menus'
            );
    $css = 'menus';
    include (ELEMENTS_PATH . "/header.php");
?>

<section id="menus" class="fullWidth">
    
    <div id="menusList" class="list">
        <div id="msgBlock"></div>
        <div class="heading fullWidth">
            <h1>Menus</h1>
            <button id="newMenu">New Menu</button>
        </div>
        <ul class="items fullWidth"></ul>
    </div>
    
    <div id="menuList" class="list hide">
        <input type="hidden" id="menuID"  value="" />
        <a href="#" id="closeMenuList" class="close">close X</a>
        <div class="heading fullWidth">
            <h1>Menu:<span></span></h1>
            <button id="newMenuItem">New Menu Item</button>
        </div>
        <ul class="items fullWidth"></ul>
    </div>
    
    <form id="newMenuForm" method="post" action="#" class="hide">
        <fieldset>
            <label for="menuName">Name</label>
            <input type="text" id="menuName" class="required" placeholder="Name" value=""/>
        </fieldset>
        
        <fieldset id="fullForm" class="hide">
            <fieldset>
                <label for="menuURL">URL</label>
                <input type="text" id="meuURL" class="required url" placeholder="URL" value=""/>
            </fieldset>
            <fieldset>
                <input type="checkbox" id="menuNewtab" />
                <label for="menuNewtab">Open in New Tab</label>
            </fieldset>
        </fieldset>
        
        <fieldset  class="commands">
            <input type="submit" id="cancelNewMenu" class="cancel" value="Cancel"  />
            <input type="submit" id="resetNewMenu" class="reset" value="Reset"  />
            <input type="submit" id="" class="save" value="Save" />
        </fieldset>
    </form>
    
</section>

<?php include (ELEMENTS_PATH . "/footer.php"); ?>