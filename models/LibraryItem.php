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
 * @property date $date
 * @property string $description
 * @property integer $sort_order
 *
 * @author Sebastian Stumpf
 * @author Matthias Wolf
 */
class LibraryItem extends HActiveRecordContent
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
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'library_item';
	}

        /**
         * We're overriding this method to fill findAll() and similar method result
         * with proper models.
         *
         * @param array $attributes
         * @return LibraryDocument
         */
        protected function instantiate($attributes){
            if ($attributes['href'] == '') $class = 'LibraryDocument';
            elseif ($attributes['href'] != '') $class = 'LibraryLink';
            else $class = get_class($this);
            $model=new $class(null);
            return $model;
        }

	/**
	 * Returns the Wall Output
	 */
	public function getWallOut()
	{
	}
	
	/**
	 * Returns a title/text which identifies this IContent.
	 *
	 * @return String
	 */
	public function getContentTitle()
	{
	}
	
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'category' => array(self::BELONGS_TO, 'LibraryCategory', 'category_id'),
		);
	}

        /**
         * @return array customized attribute labels (name=>label)
         */
        public function attributeLabels()
        {
                return array(
                        'id' => Yii::t('LibraryModule.base', 'ID'),
                        'category_id' => Yii::t('LibraryModule.base', 'LibraryCategory'),
                        'href' => Yii::t('LibraryModule.base', 'URL'),
                        'title' => Yii::t('LibraryModule.base', 'Title'),
                        'date' => Yii::t('LibraryModule.base', 'Document date'),
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
		$criteria->compare('category_id',$this->category_id);
		$criteria->compare('href',$this->href,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('sort_order',$this->sort_order);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
