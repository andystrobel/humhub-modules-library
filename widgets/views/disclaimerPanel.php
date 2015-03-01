<?php 
/**
 * Sidebar widget view to show information/disclaimer in global public library.
 * 
 * @author Matthias Wolf
 */
?>
<div class="panel panel-default panel-library-widget">
    <div class="panel-heading"><strong><?php echo $disclaimerTitle == '' ? Yii::t('LibraryModule.base', 'Disclaimer') : $disclaimerTitle; ?></strong></div>
    <div class="panel-body">
	<p><?php echo $disclaimerContent == '' ? Yii::t('LibraryModule.base', 'Set a custom disclaimer text or disable the disclaimer widget in the library module configuration.') : $disclaimerContent; ?></p>
    </div>
</div>
