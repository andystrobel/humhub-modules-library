<?php

/**
 * This is the model class for table "library_document".
 * 
 * @package humhub.modules.library.models
 * The followings are the available columns in table 'library_document':
 * @property integer $id
 * @property integer $category_id
 * @property string $href
 * @property string $title
 * @property string $description
 * @property integer $sort_order
 *
 * @author Sebastian Stumpf
 * @author Matthias Wolf
 */

class LibraryDocument extends LibraryItem
{
	public $autoAddToWall = true;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return LibraryDocument the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

        /**
         * The default scope needs to be set so that a search only returns links when you search using the LibraryLink model
         *
         * @return array default scope
         */
        function defaultScope(){
                return array(
                        'condition'=>"href IS NULL",
                );
        }

	/**
	 * Returns the Wall Output
	 */
	public function getWallOut()
	{
		return Yii::app()->getController()->widget('application.modules.library.widgets.LibraryDocumentWallEntryWidget', array('document' => $this), true);
	}
	
	/**
	 * Returns a title/text which identifies this IContent.
	 * @return String
	 */
	public function getContentTitle()
	{
		return Yii::t('LibraryModule.base', "Document") . " \"" . Helpers::truncateText($this->title, 25) . "\"";
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('category_id', 'required'),
			array('category_id, sort_order', 'numerical', 'integerOnly'=>true),
			array('href, title, description', 'safe'),
			array('title, date', 'required'),
			array('href', 'url'),
			array('date', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, category_id, href, title, description, sort_order', 'safe', 'on'=>'search'),
		);
	}
	
}
