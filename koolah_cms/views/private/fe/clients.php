<?php
    $title = 'admin';
    $js = array('clients');
    include( ELEMENTS_PATH."/header.php" );
?>    
           
    <div class="leftBlock">
        <?php 
            $active = 'clients';
            include('elements/navs/adminOptions.php') 
        ?>
    </div>
    
    <div class="mainBlock">
        <ul id="clientList">
        <?php 
            $clients = new ClientsTYPE($cmsDB);
            if ($clients->numClients > 0)
            {
                foreach ($clients->clients as $client)
                {
                    echo '<li>
                            <div class="client">'.$client->getCompanyName().'</div>
                            <div class="edit"><a href="'.$client->getID().'" class="edit">&nbsp;edit</a></div>
                          </li>';
                } 
            }     
        ?>
        </ul>
        
        <div id="addNewClient"><a href="">Add New Client</a></div>
        
        <div id="aClientForm" class="hide">
            <?php
                $companyName = new TextInputTYPE();
                    $companyName->id = "companyName";
                    $companyName->required = true;
                    $companyName->placeholder = "*Company Name";
                $companyContact = new SelectInputTYPE();
                    $companyContact->id = "companyContact";
                    $companyContact->placeholder = "Company Primary Contact";
                $cancel = new SubmitInputTYPE();
                    $cancel->id="cancel";
                    $cancel->placeholder="Cancel";
                    $cancel->html_class="cancel";
                $save = new SubmitInputTYPE();
                    $save->id="save";
                    $save->placeholder="Save";
                    $save->html_class="save";
                $inputs = array($companyName, $companyContact, $cancel, $save);
                $clientForm = new FormsTYPE($inputs);
                    $clientForm->id = "clientForm";
                    $clientForm->action = "#";                   
                $clientForm->simplePrintForm();
            ?>
            <input type="hidden" id="clientid" value="" />
        </div>
    </div>
<?php    
    include( ELEMENTS_PATH."/footer.php" );
?>