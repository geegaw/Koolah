<div id="userHistory" class="collapsible">
    <div class="commandBar">
        <h3>Recent</h3>
        <button type="button" class="toggle open">&#8211;</button>
    </div>
    <div id="userHistoryBody" class="collapsibleBody">    
        <div class="heading">
            <h2>Recent</h2>
        </div>
        <div id="userHistoryList" class="list">
            <ul>
                <?php foreach( $user->history->pageVisits(10, 0, true) as $pageVisit ): ?>
                    <li><a href="<?php echo $pageVisit->url; ?>"><?php echo $pageVisit->title; ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>                
     </div>
</div>