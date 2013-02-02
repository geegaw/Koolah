<?php
    $title = 'users-admin';
    $js = array('permissions', 'editUser');
    $css = array('users');
    include( ELEMENTS_PATH."/header.php" );
?>    
<section id="users">        
    <div class="mainBlock">
        <div id="msgBlock"></div>
        <div id="userBlock">
            <?php
                $userForm = new FormsTYPE();
                    $userForm->id = "userForm";
                    $userForm->action = "#";                      
                $userName = new TextInputTYPE();
                    $userName->id = "userName";
                    $userName->label = "Username :";
                    $userName->required = true;
                    $userName->placeholder = "User Name";
                    $userName->value = $user->getUsername();
                    $userName->html_class="email";
                    $userName->fieldset = true;                    
                $name = new TextInputTYPE();
                    $name->id = "name";
                    $name->label = "Name: ";
                    $name->placeholder = "Name";
                    $name->value = $user->getName();
                    $name->required = true;
                    $name->fieldset = true;
                $pass1 = new PasswordInputTYPE();
                    $pass1->id = "pass1";
                    $pass1->label = "Password:";
                    $pass1->placeholder = "pass1";
                    $pass1->html_class = "password";
                    $pass1->description = "Password must 6-12 charachters long and contain at least one number.";
                    $pass1->fieldset = true;                    
                $pass2 = new PasswordInputTYPE();
                    $pass2->id = "pass2";
                    $pass2->label = "Confirm Password:";
                    $pass2->placeholder = "pass2";
                    $pass2->html_class = "password";
                    $pass2->fieldset = true;                    
                /*
                $cancel = new SubmitInputTYPE();
                    $cancel->id="cancel";
                    $cancel->placeholder="Cancel";
                    $cancel->html_class="cancel";
                */
                $save = new SubmitInputTYPE();
                    $save->id="save";
                    $save->placeholder="Save";
                    $save->html_class="save";
                $userForm->beginForm(); 
            ?>
            <div id="leftCol">    
                <fieldset id="userInfoArea">
                    <legend>User Info:</legend>
                    <?php 
                        $userName->printInput();
                        $name->printInput();
                        $pass1->printInput();
                        $pass2->printInput();
                    ?>
                </fieldset>
                <fieldset id="saveCancel">
                <?php
                    //$cancel->printInput();
                    $save->printInput();
                ?>
                </fieldset>
                <?php
                  $userForm->endForm();
                  $userForm->printJS();
                ?>                    
            </div>
        </div>
        <input type="hidden" id="userid" value="<?php echo $user->getID() ?>" />
    </div>
</section>
<?php    
    include( ELEMENTS_PATH."/footer.php" );
?>