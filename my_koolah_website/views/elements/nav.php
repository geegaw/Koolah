<?php
	include( CONF_PATH."/taxonomy.php" );
    $mainNav = apiToolKit::getMenu('main_menu');
    $collections = apiToolKit::getPages('collection');
?>    
<nav class="hides">
	<ul class="viewType">
		<li>
			<a href="#" data-type="gallery" class="gallery active">
				<span></span><span></span>
				<span></span><span></span>
			</a>
		</li>
		<li>
			<a href="#" data-type="slide" class="slide">
				<span></span>
			</a>
		</li>
	</ul>
	<ul class="filters">
	<?php foreach ($taxonomy as $tag): ?>
			<li><a href="#" data-tag="<?php echo $tag['label']; ?>"><?php echo $tag['label']; ?></a>
				<?php if ($tag['sub']): ?>
					<ul class="hide">
					<?php foreach ($tag['sub'] as $sub): ?>
						<li><a href="#" data-tag="<?php echo $sub['label']; ?>"><?php echo $sub['label']; ?></a></li>
					<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</li>
	<?php endforeach; ?>\
	</ul>
	<aside>
		<div>
			<span></span>
			<span></span>
			<span></span>
		</div>
	</aside>
</nav>
