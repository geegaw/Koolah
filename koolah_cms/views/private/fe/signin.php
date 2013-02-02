<?php 
    global $cmsMongo;
    
    $username = new TextInputTYPE();
        $username->id = 'username';
        $username->html_class = 'email';
        $username->required = true;
        $username->placeholder="Username";
    $pass = new PasswordInputTYPE();
        $pass->id = 'pass';
        $pass->required = true;           
    $submit = new SubmitInputTYPE();
        $submit->id = 'signIn';
        $submit->placeholder = 'Sign In!';
    
    $inputs = array($username, $pass, $submit);
    $signInForm = new FormsTYPE($inputs);   
        $signInForm->id = 'signInForm';
        $signInForm->action = '#';
    
    $validForm = true;
    $validUserPass = true;
    if ( $signInForm->isSubmitted() ){
        if ( $signInForm->validateForm() ){
            $user = new SessionUser();
            if ($user->verify( $username->getValue(), $pass->getValue() ) ){
			    $user->signin($username->getValue());
            	$goTo = HOME;
                if ( ( isset( $_SESSION['desired_page'] )) && (!empty($_SESSION['desired_page'])) && ($_SESSION['desired_page'] != SIGNIN)  )
                    $goTo = $_SESSION['desired_page'];
				header("Location: $goTo");
                exit;        
            }
            else
                $validUserPass = false;
        }
        else
            $validForm = false;
    }
?>
<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Sign In</title>    
    <?php if (ENV == 'dev'): ?> <script type="text/javascript"> var less = { env: 'development'};</script> <?php endif; ?>
    <?php 
    	//cmsToolKit::includeCSS( "reset.min" );
    	//cmsToolKit::includeCSS( "global" );
		cmsToolKit::includeCSS( "signin" );
	?>
	<!--[if lt IE 9]><script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
	<?php	
		cmsToolKit::includeJS( "lib/jquery.1.7.1.min" );
		cmsToolKit::includeJS( "lib/jquery-ui-1.8.17.custom.min" );
        if ( ENV=='dev' )
            cmsToolKit::includeJS( "lib/less.min" );
		if ( DEBUG )
			cmsToolKit::includeJS( "debug" );
		cmsToolKit::includeJS( "plugins" );
		
		include(PUBLIC_PATH.'/js/jsConf.php');
		cmsToolKit::includeJS( "global" );
		cmsToolKit::includeJS( "objects/core" );
		cmsToolKit::includeJS( "fe/signin" );
	    $username = cmsToolKit::getParam('username', $_POST, ''); 
	?>                 
</head>
<?php flush(); ?>

<body>
	<header>
        <div id="logo"><a href="<?php echo HOME ?>">logo</a></div>
   </header>
    
    <section id="signInSection">
        <form method="POST" action="#" class="" id="signInForm">
        	<legend>Sign in</legend>
        	<fieldset>
    			<label for="username">Username</label>
        		<input type="text" placeholder="Username" value="<?php echo $username ?>" class="email required" name="username" id="username">
        	</fieldset>
        	<fieldset>
        		<label for="pass">Password</label>
        		<input type="password" value="" class=" required" name="pass" id="pass">
        	</fieldset>
        	<?php
                if (!$validUserPass)
                    echo "<div class='error'>Invalid username/password combination. </div>";
                elseif( !$validForm )
                    $signInForm->printErrors();
            ?>
        	<fieldset>
	        	<input type="submit" value="Sign In!" class="submit" name="signIn" id="signIn">        	
	        	<input type="hidden" value="true" name="formSubmitted">
        	</fieldset>
        </form>
    </section>

<?php Loader::loadFile( ELEMENTS_PATH."/footer.php" );  ?>