<?php

/**
 * LibrarySidebarWidget displaying a list of documents.
 *
 * It is attached to the sidebar of the global public library, if the disclaimer is enabled in the settings.
 *
 * @package humhub.modules.library.widgets
 * @author Matthias Wolf
 */
class DisclaimerWidget extends HWidget {

	public function run() {
	        $disclaimerWidget = HSetting::Get('disclaimerWidget', 'library');
	        $disclaimerTitle = HSetting::Get('disclaimerTitle', 'library');
	        $disclaimerContent = HSetting::Get('disclaimerContent', 'library');
	
	        if ($disclaimerWidget) $this->render('disclaimerPanel', array('disclaimerTitle' => $disclaimerTitle, 'disclaimerContent' => $disclaimerContent));
	}
}

?>
