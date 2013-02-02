<?php //echo phpinfo(); ?>
<?php include ('elements/header.php'); ?>

<h1> Let's Mongo'</h1>

<?php 
  $data1 = new TemplatesTYPE($cmsMongo);
  $data1->get();
  
  
?>
	<pre><?php var_dump($data1->prepare()); ?></pre>

<?php include ('elements/footer.php'); ?>