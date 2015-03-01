<?php
/**
 * WallEntryWidget displaying a documents content on the wall.
 *
 * @package humhub.modules.library.widgets
 * @author Matthias Wolf
 */
class LibraryDocumentWallEntryWidget extends HWidget {

    public $document;

    public function run() {
        $this->render('documentWallEntry', array(
            'document' => $this->document
        ));
    }

}

?>