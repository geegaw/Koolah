<section id="ratioListSection" class="collapsible">
   <div class="commandBar">
        <h3>Ratios</h3>
        <button type="button" class="toggle open">&#8211;</button>
   </div>
   <div id="ratioListBody" class="collapsibleBody">    
        <div class="heading">
            <h2>Ratios</h2>
            <button id="addRatio" class="add">+</button>
        </div>
        <div class="filterArea"> 
                <fieldset class="filterInput">
                    <label for="searchRatio">Search Ratios:</label>
                    <input type="text" id="filterRatio" placeholder="Ratio Name"/>
                </fieldset>
                <fieldset class="filterCommands">
                    <input type="submit" id="filterRatiosGo" value="Go"/>
                    <input type="submit" id="filterRatiosReset" value="Reset"/>                     
                 </fieldset>
            </div>     
        <div id="ratioList" class="list"><ul></ul></div>                
        <div class="msgBlock"></div>
     </div>
</section>                