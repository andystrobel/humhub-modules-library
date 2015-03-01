<?php
/**
 * Description of LibraryController.
 *
 * @package humhub.modules.library.controllers
 * @author Sebastian Stumpf
 * @author Matthias Wolf
 */

class LibraryController extends ContentContainerController
{
	/** access level of the user currently logged in. 0 -> no write access / 1 -> create documents and edit own documents / 2 -> full write access. **/
	public $accessLevel = 0;
	/** url parameter name for the guid. space -> sguid / user -> uguid. **/
	public $guidParamName = '';
	/** the url back to the library, used in the edit document view. **/
	public $libraryUrl = '';
	/** the url back to the modules, used in the config view. **/
	public $modulesUrl = '';

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
	 * Automatically loads the underlying contentContainer (User/Space) by using
	 * the uguid/sguid request parameter
	 *
	 * @return boolean
	 */
	public function init() {
		$retVal = parent::init();
		$this->accessLevel = $this->getAccessLevel();
		$this->guidParamName = $this->getGuidParamName();
		$this->libraryUrl = $this->getLibraryUrl();
		$this->modulesUrl = $this->getModulesUrl();
		return $retVal;
	}

	/**
	 * Get the access level to the library of the currently logged in user.
	 * @return number 0 -> no write access / 1 -> create in non-public categories and edit own documents / 2 -> full write access / 3 -> share/publish only
	 */
	private function getAccessLevel() {
		if($this->contentContainer instanceof User) {
			return $this->contentContainer->id == Yii::app()->user->id ? 2 : 0;
		}
		else if($this->contentContainer instanceof Space) {
			if ($this->contentContainer->isAdmin(Yii::app()->user->id)) return 2;
			if ($this->contentContainer->canShare(Yii::app()->user->id)) return 3;
			else return 1;
		}
	}

	/**
	 * Get the url back to the library, used in the edit document and edit category view.
	 * @return string
	 */
	private function getLibraryUrl() {
		if($this->contentContainer instanceof User) {
			return $this->createContainerUrl('//library/library/showLibrary');
		}
		else if($this->contentContainer instanceof Space) {
			return $this->createContainerUrl('//library/library/showLibrary');
		}
	}

	/**
	 * Get the url back to the modules, used in the config view.
	 * @return string
	 */
	private function getModulesUrl() {
		if($this->contentContainer instanceof User) {
			return $this->createContainerUrl('//user/account/editModules');
		}
		else if($this->contentContainer instanceof Space) {
			return $this->createContainerUrl('//space/admin/modules');
		}
	}

	/**
	 * Get the sublayout for the config view.
	 * @return string the url.
	 */
	private function getConfigSubLayout() {
		if($this->contentContainer instanceof User) {
			return "application.modules_core.user.views.account._layout";
		}
		else if($this->contentContainer instanceof Space) {
			return "application.modules_core.space.views.space._layout";
		}
	}

	/**
	 * Get the url parameter name for the guid.
	 * @return string space -> sguid / user -> uguid
	 */
	private function getGuidParamName() {
		if($this->contentContainer instanceof User) {
			return 'uguid';
		}
		else if($this->contentContainer instanceof Space) {
			return 'sguid';
		}
	}

	/**
	 * Cleans up documents without files and with multiple files attached.
	 */
	private function cleanupDocumentFiles($documents) {
		foreach ($documents as $document) {
			if (get_class($document) == 'LibraryDocument') {
				$files = File::getFilesOfObject($document);
				// Clean up files: Keep only the last uploaded file.
				// If no files exist, delete the document.
				// TODO: Fix the ugly mess with multiple uploaded files.
				$files = File::getFilesOfObject($document);
				$has_files = (count($files) > 0);
				$keep_file = array_pop($files);
				foreach ($files as $file) $file->delete();
				if (! $has_files) $document->delete();
			}
		}
	}

	/**
	 * Action that renders the list view.
	 * @see views/library/showLibrary.php
	 */
	public function actionShowLibrary() {

		$this->checkContainerAccess();
		$publishersOnly = $this->contentContainer->getSetting('publishersOnly', 'library');
		$categoryBuffer = LibraryCategory::model()->contentContainer($this->contentContainer)->findAll(array('order' => 'sort_order ASC'));

		$categories = array();
		$items = array();

		foreach($categoryBuffer as $category) {
			$categories[] = $category;
			// Clean up documents without files and with multiple files attached.
			$this->cleanupDocumentFiles(LibraryItem::model()->findAllByAttributes(array('category_id'=>$category->id), array('order' => 'sort_order ASC')));
			// We need to query here again in case some documents were deleted by the cleanup procedure.
			// TODO: Fix the ugly mess with multiple uploaded files.
			$items[$category->id] = LibraryItem::model()->findAllByAttributes(array('category_id'=>$category->id), array('order' => 'sort_order ASC'));
		}

		$this->render('showLibrary', array(
			$this->guidParamName => $this->contentContainer->guid,
			'categories' => $categories,
			'items' => $items,
			'publishersOnly' => $publishersOnly,
			'accessLevel' => $this->accessLevel,
		));
	}

	/**
	 * Action that renders the view to add or edit a category.<br />
	 * The request has to provide the id of the category to edit in the url parameter 'category_id'.
	 * @see views/library/editCategory.php
	 * @throws CHttpException 404, if the logged in User misses the rights to access this view.
	 */
	public function actionEditCategory() {

		$this->checkContainerAccess();

		if ($this->accessLevel != 2) {
			throw new CHttpException(404, Yii::t('LibraryModule.exception', 'You miss the rights to edit this category!'));
		}

		$category_id = (int) Yii::app()->request->getQuery('category_id');
		$category = LibraryCategory::model()->findByAttributes(array('id' => $category_id));
		$isCreated = false;

		if ($category == null) {
			$category = new LibraryCategory;
			$isCreated = true;
		}

		if (isset($_POST['LibraryCategory'])) {
			$_POST = Yii::app()->input->stripClean($_POST);
			$category->attributes = $_POST['LibraryCategory'];
			$category->content->attributes = $_POST['Content'];
			$category->content->container = $this->contentContainer;
			if ($category->validate()) {
				$items = LibraryItem::model()->findAllByAttributes(array('category_id'=>$category->id), array('order' => 'sort_order ASC'));
				foreach ($items as $item) {
					$item->content->visibility = $category->content->visibility;
					$item->save();
				}
				$category->save();
				$this->redirect(Yii::app()->createUrl('library/library/showlibrary', array($this->guidParamName => $this->contentContainer->guid)));
			}
		}

		$this->render('editCategory', array(
			$this->guidParamName => $this->contentContainer->guid,
			'category' => $category,
			'isCreated' => $isCreated,
		));
	}

	/**
	 * Action that deletes a given category.<br />
	 * The request has to provide the id of the category to delete in the url parameter 'category_id'.
	 * @throws CHttpException 404, if the logged in User misses the rights to access this view.
	 */
	public function actionDeleteCategory() {

		$this->checkContainerAccess();

		if ($this->accessLevel != 2) {
			throw new CHttpException(404, Yii::t('LibraryModule.exception', 'You miss the rights to delete this category!'));
		}

		$category_id = (int) Yii::app()->request->getQuery('category_id');
		$category = LibraryCategory::model()->findByAttributes(array('id' => $category_id));

		if ($category == null) {
			throw new CHttpException(404, Yii::t('LibraryModule.exception', 'Requested category could not be found.'));
		}

		$category->delete();

		$this->redirect(Yii::app()->createUrl('library/library/showlibrary', array (
			$this->guidParamName => $this->contentContainer->guid,
			)
		));
	}

	/**
	 * Action that renders the view to edit an item.<br />
	 * The request has to provide the id of the category the document should be created in, in the url parameter 'category_id'.<br />
	 * If an existing document should be edited, the document's id has to be given in 'document_id'.<br />
	 * @see views/library/editCategory.php
	 * @throws CHttpException 404, if the logged in User misses the rights to access this view.
	 */
	public function actionEditItem() {

		$this->checkContainerAccess();

		$item_id = (int) Yii::app()->request->getQuery('item_id');
		$category_id = (int) Yii::app()->request->getQuery('category_id');
		$item = LibraryItem::model()->findByAttributes(array('id' => $item_id));
		$category = LibraryCategory::model()->findByAttributes(array('id' => $category_id));
		$isCreated = false;

		$publicCategory = $category->content->isPublic();

		// access level 0 may neither create nor edit
		if($this->accessLevel == 0) {
			throw new CHttpException(404, Yii::t('LibraryModule.exception', 'You miss the rights to add/edit items!'));
		}
		// item has to exist
		else if ($item == null) {
			throw new CHttpException(404, Yii::t('LibraryModule.exception', 'The item you want to edit could not be found!'));
		}
		// access level 1 may edit own non-public items, 2 and 3 all documents
		else if($this->accessLevel == 1 && ($item->content->visiblity == '1' || $item->content->created_by != Yii::app()->user->id)) {
			throw new CHttpException(404, Yii::t('LibraryModule.exception', 'You miss the rights to edit this document!'));
		}

		// if form content is sent back, validate and save
		if (isset($_POST['LibraryDocument']) || isset($_POST['LibraryLink'])) {
			$_POST = Yii::app()->input->stripClean($_POST);
			if (isset($_POST['LibraryDocument'])) $item->attributes = $_POST['LibraryDocument'];
			if (isset($_POST['LibraryLink'])) $item->attributes = $_POST['LibraryLink'];
			$item->content->container = $this->contentContainer;
			$item->content->visibility = $category->content->visibility;
			// Clean up files: Keep only the last uploaded file.
			// TODO: Fix the ugly mess with multiple uploaded files.
			$files = File::getFilesOfObject($item);
			$has_files = (count($files) > 0);
			$keep_file = array_pop($files);
			foreach ($files as $file) $file->delete();
			// Validate and save item. At least one file needs to be attached with documents. Links don't have a file.
			if (isset($_POST['LibraryDocument']) && !$has_files) $item->addError('file', Yii::t('LibraryModule.base', 'You must upload a file.'));
			elseif ($item->validate()) {
				$item->save();
				$this->redirect(Yii::app()->createUrl('library/library/showlibrary', array ($this->guidParamName => $this->contentContainer->guid)));
			}
		}

		// If the item is a document, render document edit form
		if (get_class($item) == 'LibraryDocument') {
			$this->render('editDocument', array(
				$this->guidParamName => $this->contentContainer->guid,
				'document' => $item,
				'isCreated' => $isCreated,
			));
		}
		// If the item is a link, render link edit form
		elseif (get_class($item) == 'LibraryLink') {
			$this->render('editLink', array(
				$this->guidParamName => $this->contentContainer->guid,
				'link' => $item,
				'isCreated' => $isCreated,
			));
		}
		// Since all items are links or documents, this shouldn't happen. Redirect back anyway.
		else {
			$this->redirect(Yii::app()->createUrl('library/library/showlibrary', array ($this->guidParamName => $this->contentContainer->guid)));
		}
	}

	/**
	 * Action that renders the view to add a document.<br />
	 * The request has to provide the id of the category the document should be created in, in the url parameter 'category_id'.<br />
	 * @see views/library/editCategory.php
	 * @throws CHttpException 404, if the logged in User misses the rights to access this view.
	 */
	public function actionAddDocument() {

		$this->checkContainerAccess();

		$category_id = (int) Yii::app()->request->getQuery('category_id');
		$category = LibraryCategory::model()->findByAttributes(array('id' => $category_id));
		$publicCategory = $category->content->isPublic();
		$isCreated = false;
		$lastfile = '';

		// access level 0 may neither create nor edit
		if($this->accessLevel == 0) {
			throw new CHttpException(404, Yii::t('LibraryModule.exception', 'You miss the rights to add documents!'));
		}
		// access level 1 + 2 + 3 may create (level 1 only in non-public categories...)
		else {
			$document = new LibraryDocument();
			$document->date = date('Y-m-d');
			if (LibraryCategory::model()->findByAttributes(array('id' => $category_id)) == null) {
				throw new CHttpException(404, Yii::t('LibraryModule.exception', 'The category you want to create your document in could not be found!'));
			}
			if (($this->accessLevel == 1) && ($category->content->isPublic())) {
				throw new CHttpException(404, Yii::t('LibraryModule.exception', 'You miss the rights to add documents to public categories!'));
			}
			$document->category_id = $category_id;
			$isCreated = true;
		}

		// if form content is sent back, validate and save
		if (isset($_POST['LibraryDocument'])) {
			$_POST = Yii::app()->input->stripClean($_POST);
			$document->attributes = $_POST['LibraryDocument'];
			$document->content->container = $this->contentContainer;
			$document->content->visibility = $category->content->visibility;
			// Handle uploaded files. array_filter removes empty elements.
			$pre_files=array_filter(explode(",", Yii::app()->request->getParam('fileList')));
			$has_files = ((count($pre_files) > 0));
			$lastfile=array_pop($pre_files);
			// Validate and save item. At least one file needs to be attached.
			if ($document->validate()) {
				if (! $has_files) {
					$document->addError('file', Yii::t('LibraryModule.base', 'You must upload a file.'));
				}
				if ($has_files && $document->validate()) {
					$document->save();
					// Attach the uploaded file. If multiple were uploaded, just attach the last one.
					// TODO: Fix the ugly mess with multiple uploaded files.
					File::attachPrecreated($document, $lastfile);
					$this->redirect(Yii::app()->createUrl('library/library/showlibrary', array ($this->guidParamName => $this->contentContainer->guid)));
				}
			}
		}
		// Render document edit form
		$this->render('editDocument', array(
			$this->guidParamName => $this->contentContainer->guid,
			'document' => $document,
			'isCreated' => $isCreated,
		));
	}

	/**
	 * Action that renders the view to add a link.<br />
	 * The request has to provide the id of the category the link should be created in, in the url parameter 'category_id'.<br />
	 * @see views/library/editCategory.php
	 * @throws CHttpException 404, if the logged in User misses the rights to access this view.
	 */
	public function actionAddLink() {

		$this->checkContainerAccess();

		$category_id = (int) Yii::app()->request->getQuery('category_id');
		$category = LibraryCategory::model()->findByAttributes(array('id' => $category_id));
		$publicCategory = $category->content->isPublic();
		$isCreated = false;

		// access level 0 may neither create nor edit
		if($this->accessLevel == 0) {
			throw new CHttpException(404, Yii::t('LibraryModule.exception', 'You miss the rights to add links!'));
		}
		// access level 1 + 2 + 3 may create (level 1 only in non-public categories...)
		else {
			$link = new LibraryLink();
			if (LibraryCategory::model()->findByAttributes(array('id' => $category_id)) == null) {
				throw new CHttpException(404, Yii::t('LibraryModule.exception', 'The category you want to create your link in could not be found!'));
			}
			if (($this->accessLevel == 1) && ($category->content->isPublic())) {
				throw new CHttpException(404, Yii::t('LibraryModule.exception', 'You miss the rights to add links to public categories!'));
			}
			$link->category_id = $category_id;
			$isCreated = true;
		}

		// if form content is sent back, validate and save
		if (isset($_POST['LibraryLink'])) {
			$_POST = Yii::app()->input->stripClean($_POST);
			$link->attributes = $_POST['LibraryLink'];
			$link->content->container = $this->contentContainer;
			$link->content->visibility = $category->content->visibility;
			if ($link->validate()) {
				$link->save();
				$this->redirect(Yii::app()->createUrl('library/library/showlibrary', array ($this->guidParamName => $this->contentContainer->guid)));
			}
		}
		// Render link edit form
		$this->render('editLink', array(
			$this->guidParamName => $this->contentContainer->guid,
			'link' => $link,
			'isCreated' => $isCreated,
		));
	}


	/**
	 * Action that deletes a given document.<br />
	 * The request has to provide the id of the document to delete in the url parameter 'document_id'.
	 * @throws CHttpException 404, if the logged in User misses the rights to access this view.
	 */
	public function actionDeleteItem() {

		$this->checkContainerAccess();

		$item_id = (int) Yii::app()->request->getQuery('item_id');
		$item = LibraryItem::model()->findByAttributes(array('id' => $item_id));

		if ($item == null) {
			throw new CHttpException(404, Yii::t('LibraryModule.exception', 'Requested item could not be found.'));
		}
		// access level 1 may delete own documents, 2 and 3 all documents
		else if($this->accessLevel == 0 || $this->accessLevel == 1 && $item->content->created_by != Yii::app()->user->id) {
			throw new CHttpException(404, Yii::t('LibraryModule.exception', 'You miss the rights to delete this item!'));
		}

		$item->delete();

		$this->redirect(Yii::app()->createUrl('library/library/showlibrary', array ($this->guidParamName => $this->contentContainer->guid)));
	}

	/**
	 * Space Configuration Action for Admins
	 */
	public function actionConfig() {

		Yii::import('library.forms.*');
		$this->subLayout = $this->getConfigSubLayout();

		$form = new LibraryConfigureForm();

// 		uncomment the following code to enable ajax-based validation
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'library-configure-form') {
            echo CActiveForm::validate($form);
            Yii::app()->end();
        }

		if (isset($_POST['LibraryConfigureForm'])) {
			$_POST['LibraryConfigureForm'] = Yii::app()->input->stripClean($_POST['LibraryConfigureForm']);
			$form->attributes = $_POST['LibraryConfigureForm'];

			if ($form->validate()) {
				$this->contentContainer->setSetting('enableDeadLinkValidation', $form->enableDeadLinkValidation, 'library');
				$this->contentContainer->setSetting('publishersOnly', $form->publishersOnly, 'library');
				$this->contentContainer->setSetting('enableWidget', $form->enableWidget, 'library');
				$this->redirect(Yii::app()->createUrl('library/library/config', array ($this->guidParamName => $this->contentContainer->guid)));
			}
		} else {
			$form->enableDeadLinkValidation = $this->contentContainer->getSetting('enableDeadLinkValidation', 'library');
			$form->publishersOnly = $this->contentContainer->getSetting('publishersOnly', 'library');
			$form->enableWidget = $this->contentContainer->getSetting('enableWidget', 'library');
		}

		$this->render('config', array('model' => $form, $this->guidParamName => $this->contentContainer->guid));
	}

	/**
	 * Reorder Items action.
	 * @uses behaviors.ReorderContentBehavior
	 */
	public function actionReorderItems() {
		// validation
		try {
			$this->checkContainerAccess();
			if (($this->accessLevel != 2) && ($this->accessLevel != 3)) {
				throw new CHttpException(403, Yii::t('LibraryModule.exception', 'You miss the rights to reorder items!'));
			}
		} catch (CHttpException $e) {
			echo json_encode($this->reorderContent('LibraryItem', $e->statusCode, $e->getMessage()));
			return;
		}
		// generate json response
		echo json_encode($this->reorderContent('LibraryItem', 200, 'The item order was successfully changed.'));
	}

	/**
	 * Reorder Categories action.
	 * @uses behaviors.ReorderContentBehavior
	 */
	public function actionReorderCategories() {
		// validation
		try {
			$this->checkContainerAccess();
			if ($this->accessLevel != 2) {
				throw new CHttpException(403, Yii::t('LibraryModule.exception', 'You miss the rights to reorder categories!'));
			}
		} catch (CHttpException $e) {
			echo json_encode($this->reorderContent('LibraryCategory', $e->statusCode, $e->getMessage()));
			return;
		}
		// generate json response
		echo json_encode($this->reorderContent('LibraryCategory', 200, Yii::t('LibraryModule.base', 'The item order was successfully changed.')));
	}
}

?>
