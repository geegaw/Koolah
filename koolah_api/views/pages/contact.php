<?php
    $active = array('contact');
    $css = array('contact');
    $js = array('contact');
    include( ELEMENTS_PATH."/header.php" );
?> 

<div id='contactUs'>
       <h1> Contact </h1>
       <form id="contactForm" method="post" >
           <fieldset>
               <label for="name">Name:</label>
               <input id="name" type="text" class="required" value="" placeholder="Name" />
           </fieldset>
           
           <fieldset>
               <label for="email">Email:</label>
               <input id="email" type="text" class="required email" value="" placeholder="Email" />
           </fieldset>
           
           <fieldset>
               <label for="msg">Mesage:</label>
               <textarea id="msg" class="required"></textarea>
           </fieldset>
           
           <fieldset><button type="submit" id="send">Send</button></fieldset>       
       </form>
       
       <?php if ( $page->telephone): ?>
           <?php foreach( $page->telephone as $tel ): ?>
                <a href="tel:<?php echo $tel['phone']; ?>"><?php echo $tel['phone']; ?></a>
           <?php endforeach; ?>
       <?php endif; ?>
       
       <?php if ( $page->email): ?>
           <?php foreach( $page->email as $email ): ?>
                <a href="mailto:<?php echo $email['email']; ?>"><?php echo $email['email']; ?></a>
           <?php endforeach; ?>
       <?php endif; ?>
</div>
<div class="rightImage"><?php echo htmlTools::loadImage($page->background_image); ?></div>
<?php include( ELEMENTS_PATH."/footer.php" ); ?>