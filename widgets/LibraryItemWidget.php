<?php

/**
 * This widget is used include the files functionality to a library item.
 *
 * @package humhub.modules.library
 * @author Matthias Wolf
 */
class LibraryItemWidget extends HWidget
{

    /**
     * Object to show files from
     */
    public $item = null;
    public $category = null;
    public $editable = null;

    /**
     * Executes the widget.
     */
    public function run()
    {
        if ($this->item->href != '') {
            $this->render('libraryLink', array(
            	'item' => $this->item,
            	'category' => $this->category,
            	'editable' => $this->editable,
            ));
        }
        else {
            $files = File::getFilesOfObject($this->item);
            $file = array_pop($files);
            // If there is no file attached, deliver a dummy object. That's better than completely breaking the rendering.
            if (!is_object($file)) $file = new File();
            $mime = HHtml::getMimeIconClassByExtension($file->getExtension());
            $this->render('libraryDocument', array(
            	'file' => $file,
            	'mime' => $mime,
            	'item' => $this->item,
            	'category' => $this->category,
            	'editable' => $this->editable,
            ));
        }
    }

}

?>