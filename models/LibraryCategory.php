<?php

/**
 * This is the model class for table "library_category".
 * 
 * @package humhub.modules.library.models
 *
 * The followings are the available columns in table 'library_category':
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property string $show_sidebar
 * @property integer $sort_order
 *
 * @author Sebastian Stumpf
 * @author Matthias Wolf
 */

class LibraryCategory extends HActiveRecordContent
{
	public $autoAddToWall = false;
	//public $isPublic;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return LibraryCategory the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'library_category';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sort_order', 'numerical', 'integerOnly'=>true),
			array('title, description, show_sidebar, isPublic', 'safe'),
			array('title', 'required'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, title, description, sort_order', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'items' => array(self::HAS_MANY, 'LibraryItem', 'category_id'),
		);
	}


	public function afterDelete()
	{
		foreach($this->items as $item) {
			$item->delete();
		}
		parent::afterDelete();
	}
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('LibraryModule.base', 'ID'),
			'title' => Yii::t('LibraryModule.base', 'Title'),
			'description' => Yii::t('LibraryModule.base', 'Description'),
			'sort_order' => Yii::t('LibraryModule.base', 'Sort order'),
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('sort_order',$this->sort_order);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
        /**
         * Returns the public state of the contect object
         *
         * @return boolean
         */
	public function isPublic()
	{
		return $this->content->isPublic();
	}
	
}