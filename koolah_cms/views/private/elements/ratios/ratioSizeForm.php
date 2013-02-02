<section id="ratioSizeForm" class="collapsible hide" >
    <div class="commandBar">
        <h3></h3>
        <button type="button" class="toggle open">&#8211;</button>
    </div>
    <div id="ratioSizeFormBody" class="collapsibleBody">
        <div class="heading">
            <h2><span>New</span> Ratio Size</h2>
        </div>
        <form method="post">            
            <input type="hidden" id="ratioSizeID" value=""/>
            <fieldset>
                <label for="ratioSizeName">Ratio Name</label>
                <input type="text" id="ratioSizeName" class="required" placeholder="Name" />
            </fieldset>
            
            <fieldset>
                <fieldset class="halfWidth">
                    <label for="ratioSizeWidth">Width(px)</label>
                    <input type="text" id="ratioSizeWidth" class="required int" placeholder="w" />
                </fieldset>
                
                <fieldset class="halfWidth">
                    <label for="ratioSizeHeight">Height(px)</label>
                    <input type="text" id="ratioSizeHeight" class="required int" placeholder="h" />
                </fieldset>        
            </fieldset>
            <fieldset>
                <input type="submit" id="cancelSaveRatioSize" class="cancel noreset" value="Cancel" />
                <input type="submit" id="saveRatioSize" class="save noreset" value="Save" />
            </fieldset>
        </form>    
    </div>
</section> 