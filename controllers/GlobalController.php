<?php
/**
 * Description of LibraryController.
 *
 * @package humhub.modules.library.controllers
 * @author Matthias Wolf
 */
class GlobalController extends Controller
{
        public $subLayout = "_layout";
        public $currentLibrary = "";
        public $publicLibraries = array();

	public function behaviors() {
		return array(
			'HReorderContentBehavior' => array(
				'class' => 'application.behaviors.HReorderContentBehavior',
			)
		);
	}

	/**
	 * @return array action filters
	 */
	public function filters() {
		return array(
			'accessControl', // perform access control for CRUD operations -> redirect to login if access denied
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules() {
		return array(
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'users' => array('@'),
			),
			array('deny', // deny all users
				'users' => array('*'),
			),
		);
	}

	/**
	 * Init expanding parent init with custom initializations
	 *
	 * @return boolean
	 */
	public function init() {
		$retVal = parent::init();
		// Add custom init code here...
		$this->publicLibraries = $this->initPublicLibraries();
		return $retVal;
	}

	/**
	 * Retrieve all public libraries
	 *
	 * @return array Spaces with public library categories
	 */
	public function initPublicLibraries() {
	    $spaces = array();
	    $libraryCategory = LibraryCategory::model()->findAll();
	    // get all workspaces with public library categories
	    foreach ($libraryCategory as $cat) {
	        $attr = $cat->content->getAttributes();
	        if ($attr['visibility'] == 1 && $attr['space_id'] != '') {
	            $spaces[$attr['space_id']] = 1;
	        }
	    }
	    foreach ($spaces as $id => $space) {
	        $workspace = Space::model()->findByPk($id);
	        $spaces[$id] = $workspace->name;
	    }
	    return $spaces;
	}

	/**
	 * Action that renders the global library view.
	 * @see views/global/index.php
	 */
	public function actionIndex() {
	    // Redirect to public library with lowest ID.
	    if (count ($this->publicLibraries) > 0) {
        $pubLib = array_keys($this->publicLibraries);
  	    $id = min($pubLib);
  	    $this->redirect(Yii::app()->createUrl('library/global/showLibrary',array('id' => $id)));
      }
      else {
        $this->render('showLibrary', array(
          'categories' => array()
        ));
      }
	}

	/**
	 * Action that renders the list view.
	 * @see views/global/showLibrary.php
	 */
	public function actionShowLibrary() {
		$this->currentLibrary = (int) Yii::app()->request->getQuery('id');
		$contentContainer = Space::model()->findByPk($this->currentLibrary);

		$categoryBuffer = LibraryCategory::model()->contentContainer($contentContainer)->findAll(array('order' => 'sort_order ASC'));

		$categories = array();
		$items = array();

		foreach($categoryBuffer as $category) {
			// only show public categories
			if ($category->content->attributes['visibility'] == 1) {
				$categories[] = $category;
				$items[$category->id] = LibraryItem::model()->findAllByAttributes(array('category_id'=>$category->id), array('order' => 'sort_order ASC'));
			}
		}
		$this->render('showLibrary', array(
			'categories' => $categories,
			'items' => $items,
		));
	}

}

?>
