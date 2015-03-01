<?php

/**
 * LibrarySidebarWidget displaying a list of documents.
 *
 * It is attached to the sidebar of the space/user, if the module is enabled in the settings.
 *
 * @package humhub.modules.library.widgets
 * @author Sebastian Stumpf
 * @author Matthias Wolf
 */
class LibrarySidebarWidget extends HWidget {

	public function run() {
		
		$container = $this->getContainer();
		if(!$container->getSetting('enableWidget', 'library')) {
			return;
		}
		$categoryBuffer = LibraryCategory::model()->contentContainer($container)->findAll(array('order' => 'sort_order ASC'));
		$categories = array();
		$documents = array();		
		$render = false;
			
		foreach($categoryBuffer as $category) {
			$itemBuffer = LibraryItem::model()->findAllByAttributes(array('category_id'=>$category->id), array('order' => 'sort_order ASC'));
			// categories are only displayed in the widget if they have sidebar view enabled and contain at least one item
			if($category->show_sidebar && !empty($itemBuffer)) {
				$categories[] = $category;
				$items[$category->id] = $itemBuffer;
				$render = true;
			}
		}
		
		// if none of the categories contains an item, the library widget is not rendered.
		if($render) {
			// register script and css files
			$assetPrefix = Yii::app()->assetManager->publish(dirname(__FILE__) . '/../resources', true, 0, defined('YII_DEBUG'));
			Yii::app()->clientScript->registerScriptFile($assetPrefix . '/library.js');
			Yii::app()->clientScript->registerCssFile($assetPrefix . '/library.css');
			$this->render ( 'libraryPanel', array ('container' => $container, 'categories' => $categories, 'items' => $items));
		}
	}	
	
	/**
	 * Get the Container this widget is embedded in.
	 * @throws CHttpException if the container could not be found.
	 * @return Either the User or the Space container.
	 */
	public function getContainer() {
		
		$container = null;
		
		$spaceGuid = Yii::app()->request->getQuery('sguid');
		$userGuid = Yii::app()->request->getQuery('uguid');
		
		if ($spaceGuid != "") {
		
			$container = Space::model()->findByAttributes(array('guid' => $spaceGuid));
		
			if ($container == null) {
				throw new CHttpException(404, Yii::t('SpaceModule.base', 'Space not found!'));
			}
		} elseif ($userGuid != "") {
		
			$container = User::model()->findByAttributes(array('guid' => $userGuid));
		
			if ($container == null) {
				throw new CHttpException(404, Yii::t('UserModule.base', 'Space not found!'));
			}
		} else {
			throw new CHttpException(500, Yii::t('base', 'Could not determine content container!'));
		}
		return $container;
	}
}

?>
