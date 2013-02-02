<section id="ratioForm" class="collapsible hide" >
    <div class="commandBar">
        <h3></h3>
        <button type="button" class="toggle open">&#8211;</button>
    </div>
    <div id="ratioFormBody" class="collapsibleBody">
        <div class="heading">
            <div class="title">
                <h2><span>New</span> Ratio</h2>
                <button id="addRatioSize">+</button>
            </div>
        </div>
        
        <form method="post">            
            <input type="hidden" id="ratioID" value=""/>
            <fieldset id="ratioInputs">
                <fieldset  class="fullWidth">
                    <label for="ratioName">Ratio Name</label>
                    <input type="text" id="ratioName" class="required" placeholder="Ratio Name" />
                </fieldset>
        
                <fieldset id="ratiofieldset">
                    <label id="ratiofieldsetLabel">Ratio (w:h)</label>
                    <fieldset>
                        <input type="text" id="ratioWidth" class="required int" placeholder="w" />                    
                    </fieldset>
                    
                    <label id="seperator">:</label>
                    <fieldset>
                        <input type="text" id="ratioHeight" class="required int" placeholder="h" />
                    </fieldset>        
                </fieldset>
            </fieldset>
            
            <fieldset  id="ratioCommands">
                <input type="submit" id="cancelSaveRatio" class="cancel noreset" value="Cancel" />
                <input type="submit" id="saveRatio" class="save noreset" value="Save" />
            </fieldset>
        </form>
        <div id="ratioSizesList" class="list"><ul></ul></div>
    </div>            
</section>