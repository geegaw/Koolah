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
    
    <?php koolahToolKit::includeCSS( 'koolah', true ); ?>    
    <?php if (isset($css))koolahToolKit::includeCSS($css ); ?>
    <!--[if lt IE 9]><script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
    
    <?php include(PUBLIC_PATH.'/js/jsConf.php');; ?>
    <script data-main="/public/js/main" src="/public/js/lib/require/require.min.js"></script>
    
	<?php $username = koolahToolKit::getParam('username', $_POST, ''); ?>                 
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