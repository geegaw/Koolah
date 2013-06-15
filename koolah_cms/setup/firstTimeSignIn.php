<?php
/**
 * firstTimeSignIn
 * 
 * @ignore
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * install portion
 * 
 * @TODO: install script not yet complete
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\system\core
 */ 
 
	global $cmsMongo;
	
	$errors = null;
	if ( isset( $_POST['submitted'] ) ){
		$name = new TextInputTYPE();
			$name->id = 'superName';
			$name->required = true;
			$name->placeholder = "Name";
		$name->readInput(); 
		if ( $error = $name->getErrorMsg() )
			$errors[] = $error;
		
		$username = new TextInputTYPE();
			$username->id = 'superUsername';
			$username->required = true;
			$username->placeholder = "Username";
		$username->readInput(); 
		if ( $error = $username->getErrorMsg() )
			$errors[] = $error;
		
		$pass1 = new PasswordInputTYPE();
			$pass1->id = 'superPass1';
			$pass1->required = true;			
		$pass1->readInput(); 
		if ( $error = $pass1->getErrorMsg() )
			$errors[] = $error;
		
		$pass2 = new PasswordInputTYPE();
			$pass2->id = 'superPass1';
			$pass2->required = true;			
		$pass2->readInput();
		if ( $error = $pass2->getErrorMsg() )
			$errors[] = $error;
		if( $pass1->getValue() != $pass2->getValue() )
			$errors[] = 'passwords do not match'; 
		
		if ( !$errors ){
			$newSuper = new UserTYPE( $cmsMongo );
			$newSuper->setName( $name->getValue() );
			$newSuper->setUsername( $username->getValue() );
			$newSuper->setPassword( $pass1->getValue() );
			$newSuper->mkSuper();
			$status = $newSuper->save();			
		}
	}
?>

<!doctype html>
<html>
<head>
    <title>Sign In</title>    
    <?php 
    	koolahToolKit::includeCSS("reset.min" );
    	koolahToolKit::includeCSS("main" );
		koolahToolKit::includeCSS("setup" );
	?>
	<!--[if lt IE 9]><script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
	<?php	
		koolahToolKit::includeJS( "jquery.1.7.1.min" );
		koolahToolKit::includeJS( "jquery-ui-1.8.17.custom.min" );
		koolahToolKit::includeJS( "global" );
		koolahToolKit::includeJS( "signin" );
		koolahToolKit::includeJS( "forms" );
		koolahToolKit::includeJS( "setup" );
	?>                 
</head>
<body>
	<header>
        <div id="logo"><a href="<?php echo HOME ?>">logo</a></div>
   	</header>
	
	
	<section id="firstTime">
	<?php if (isset( $status ) && $status->success()): ?>
		<h1 class="success">SuperUser Succesfully Created</h1>
		<div class="savedInfo">
			name : <?php echo $name->getValue(); ?> <br />
			username : <?php echo $username->getValue(); ?> <br />
			password : <?php echo $pass1->getValue(); ?> <br />
			<div class="whatToDo">
				!!! Print this information for your records. !!!<br />
				Then click the logo and go back and sign in with these credentials.
			</div>
		</div>
		
	<?php else: ?>
		<h1>Create first SuperUser</h1>
		
		<?php if (isset( $status )): ?>
			<div id="saveErrors" class="error">
			<?php echo $status->errMsg; ?>
			</div>
		<?php endif ?>
		<?php if ( $errors ): ?>
			<ul id="formErrors" class="error">
			<?php
				foreach ( $errors as $error )
					echo "<li>$error</li>";
			?>
			</ul>
		<?php endif ?>
		
		<form id="firstSuperForm" action="#" method="post">
			<fieldset>
				<label for="superName">Name</label>
				<input type="text" class="required" name="superName" id="superName" placeholder="Name" value="" />
			</fieldset>
			<fieldset>
				<label for="superUsername">Username</label>
				<input type="text" class="required email" name="superUsername" id="superUsername" placeholder="Username" value="" />
				<div class="description">Must be an email.</div>
			</fieldset>
			<fieldset>
				<label for="superPass1">Password</label>
				<input type="password" class="required" name="superPass1" id="superPass1" value="" />
			</fieldset>
			<fieldset>
				<label for="superPass2">Confirm Password</label>
				<input type="password" class="required" name="superPass2" id="superPass2" value="" />
			</fieldset>
			<fieldset>
				<input type="submit" name="reset" id="reset" value="Reset" />
				<input type="submit" name="save" id="save" value="Save" />
				<input type="hidden" name="submitted" value="true" />
			</fieldset>
			
		</form>
		
	<?php endif ?>		
	</section>
	
			
<?php include( ELEMENTS_PATH."/footer.php" ); ?>