<?php
/**
 * WallEntryWidget displaying a links content on the wall.
 *
 * @package humhub.modules.linklist.widgets
 * @author Sebastian Stumpf
 */
class LibraryLinkWallEntryWidget extends HWidget {

    public $link;

    public function run() {
        $this->render('linkWallEntry', array(
            'link' => $this->link
        ));
    }

}

?>