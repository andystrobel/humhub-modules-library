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

class LibraryLink extends LibraryDocument
{
	public $autoAddToWall = true;
	
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
                        'condition'=>"href IS NOT NULL",
                );
        }

	/**
	 * Returns the Wall Output
	 */
	public function getWallOut()
	{
		return Yii::app()->getController()->widget('application.modules.library.widgets.LibraryLinkWallEntryWidget', array('link' => $this), true);
	}
	
	/**
	 * Returns a title/text which identifies this IContent.
	 *
	 * e.g. Post: foo bar 123...
	 *
	 * @return String
	 */
	public function getContentTitle()
	{
		return Yii::t('LibraryModule.base', 'Link') . " \"" . Helpers::truncateText($this->title, 25) . "\"";
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('category_id', 'required'),
			array('category_id, sort_order', 'numerical', 'integerOnly'=>true),
			array('title, description', 'safe'),
			array('href', 'url', 'defaultScheme' => 'http'),
			array('href', 'DeadLibraryLinkValidator', 'type'=>'GET', 'timeout' => 2),
			array('href, title', 'required'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, category_id, href, title, description, sort_order', 'safe', 'on'=>'search'),
		);
	}

}
