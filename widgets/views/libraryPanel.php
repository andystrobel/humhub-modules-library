<?php 
/**
 * Sidebar widget view to list all categories and their items.
 * 
 * @uses $categories an array of the categories to show.
 * @uses $items an array of arrays of the items to show, indicated by the category id.
 * 
 * @author Sebastian Stumpf
 * @author Matthias Wolf
 */
?>
<div class="panel panel-default panel-library-widget">
    <div class="panel-heading"><strong><?php echo Yii::t('LibraryModule.base', 'Quick'); ?></strong> <?php echo Yii::t('LibraryModule.base', 'library'); ?></div>
    <div class="library-body">
    	<div class="scrollable-content-container">
	    	<?php foreach($categories as $category) { ?>
	    	<div id="library-widget-category_<?php echo $category->id;?>" class="media">
	    		<div class="media-heading"><?php echo $category->title; ?></div>
				<ul class="media-list">
					<?php foreach($items[$category->id] as $item) { ?>
						<?php
						if ($item->href == '') {
							$files = File::getFilesOfObject($item);
							$file = array_pop($files);
							// If there is no file attached, deliver a dummy object. That's better than completely breaking the rendering.
							if (!is_object($file)) $file = new File();
							$item->href = $file->getUrl();
						}
						?>
						<li id="library-widget-item_<?php echo $item->id;?>"><a href="<?php echo $item->href; ?>" title="<?php echo $item->description; ?>" target="_blank"><?php echo $item->title; ?></a></li>
					<?php } ?>
				</ul>
			</div>
			<?php } ?>
		</div>
    </div>
</div>
