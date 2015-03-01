<?php
/**
 * View to list all categories and their documents.
 *
 * @uses $categories an array of the categories to show.
 * @uses $items an array of arrays of the documents to show, indicated by the category id.
 * @uses $accesslevel the access level of the user currently logged in.
 *
 * @author Sebastian Stumpf
 * @author Matthias Wolf
 */
?>

        <div id="library-empty-txt" <?php if (empty($categories)) {
            echo 'style="visibility:visible; display:block"';
        } ?>><?php echo Yii::t('LibraryModule.base', 'There have been no items or categories added to this space yet.') ?> <i class="fa fa-frown-o"></i></div>

        <div class="library-categories">
            <?php foreach ($categories as $category) { ?>
                <div id="library-category_<?php echo $category->id ?>"
                     class="panel panel-default panel-library-category" data-id="<?php echo $category->id ?>">
                    <div class="panel-heading">
                        <div class="heading">
                            <?php echo $category->title; ?>
                            <?php $this->widget('application.modules_core.wall.widgets.WallEntryLabelWidget', array('object' => $category)); ?>
                            <?php if (Yii::app()->getController()->accessLevel != 0) { ?>
                                <div class="library-edit-controls library-editable">
                                    <?php if (Yii::app()->getController()->accessLevel == 2) {
                                        // admins may edit and delete categories
                                        $this->widget('application.widgets.ModalConfirmWidget', array(
                                            'uniqueID' => 'modal_categorydelete_' . $category->id,
                                            'linkOutput' => 'a',
                                            'class' => 'deleteButton btn btn-xs btn-danger" title="' . Yii::t('LibraryModule.base', 'Delete category'),
                                            'title' => Yii::t('LibraryModule.base', '<strong>Confirm</strong> category deleting'),
                                            'message' => Yii::t('LibraryModule.base', 'Do you really want to delete this category? All connected items will be lost!'),
                                            'buttonTrue' => Yii::t('LibraryModule.base', 'Delete'),
                                            'buttonFalse' => Yii::t('LibraryModule.base', 'Cancel'),
                                            'linkContent' => '<i class="fa fa-trash-o"></i>',
                                            'linkHref' => $this->createUrl("//library/library/deleteCategory", array('category_id' => $category->id, Yii::app()->getController()->guidParamName => Yii::app()->getController()->contentContainer->guid)),
                                            'confirmJS' => 'function() {
                                                                $("#library-category_' . $category->id . '").remove();
                                                                $("#library-widget-category_' . $category->id . '").remove();
                                                                if($(".panel-library-widget").find(".media").length == 0) {
                                                                        $(".panel-library-widget").remove();
                                                                }
                                                        }'
                                        ));
                                        echo CHtml::link('<i class="fa fa-pencil"></i>', array('//library/library/editCategory', 'category_id' => $category->id, Yii::app()->getController()->guidParamName => Yii::app()->getController()->contentContainer->guid), array('title' => Yii::t('LibraryModule.base', 'Edit category'), 'class' => 'btn btn-xs btn-primary')). ' ';
                                    }
                                    // users may add a document to an existing non-public category if not prohibited by publishersOnly setting. Only admins and publishers may add to public categories
                                    if ((!$category->content->isPublic() && !$publishersOnly) || (Yii::app()->getController()->accessLevel == 2) || (Yii::app()->getController()->accessLevel == 3)) {
                                        echo CHtml::link('<i class="fa fa-plus" style="font-size: 12px;"></i> '.Yii::t('LibraryModule.base', 'Add link'), array('//library/library/addLink', 'category_id' => $category->id, Yii::app()->getController()->guidParamName => Yii::app()->getController()->contentContainer->guid), array('title' => Yii::t('LibraryModule.base', 'Add link'), 'class' => 'btn btn-xs btn-info')). ' ';
                                        echo CHtml::link('<i class="fa fa-plus" style="font-size: 12px;"></i> '.Yii::t('LibraryModule.base', 'Add document'), array('//library/library/addDocument', 'category_id' => $category->id, Yii::app()->getController()->guidParamName => Yii::app()->getController()->contentContainer->guid), array('title' => Yii::t('LibraryModule.base', 'Add document'), 'class' => 'btn btn-xs btn-info'));
                                    }    ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="media">
                            <?php if (!($category->description == NULL || $category->description == "")) { ?>
                                <div class="media-heading"><?php echo $category->description; ?></div>
                            <?php } ?>
                            <div class="media-body">
                                <ul class="library-items">
                                    <?php foreach ($items[$category->id] as $item) { ?>
                                        <?php $editable = ((Yii::app()->getController()->accessLevel == 2 || Yii::app()->getController()->accessLevel == 3) || (!$publishersOnly && !$category->content->isPublic() && Yii::app()->getController()->accessLevel == 1 && $item->content->created_by == Yii::app()->user->id)) ? true : false; ?>
                                        <?php $this->widget('application.modules.library.widgets.LibraryItemWidget', array('item' => $item, 'category' => $category, 'editable' => $editable)); ?>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
        <?php if (Yii::app()->getController()->accessLevel != 0) { ?>
            <?php 
                // enable drag and drop reordering of documents for admins and publishers
                if ((Yii::app()->getController()->accessLevel == 2) || (Yii::app()->getController()->accessLevel == 3)) {
                        $this->widget('application.widgets.ReorderContentWidget', array('containerClassName' => 'library-items', 'sortableItemClassName' => 'library-item', 'url' => Yii::app()->createUrl('//library/library/reorderItems'), 'additionalAjaxParams' => array(Yii::app()->getController()->guidParamName => Yii::app()->getController()->contentContainer->guid)));
                }
                // enable drag and drop reordering and creation of categories for admins
                if (Yii::app()->getController()->accessLevel == 2) {
                        $this->widget('application.widgets.ReorderContentWidget', array('containerClassName' => 'library-categories', 'sortableItemClassName' => 'panel-library-category', 'url' => Yii::app()->createUrl('//library/library/reorderCategories'), 'additionalAjaxParams' => array(Yii::app()->getController()->guidParamName => Yii::app()->getController()->contentContainer->guid)));
            ?>
                 <div
                    class="library-add-category library-editable"><?php echo CHtml::link(Yii::t('LibraryModule.base', 'Add category'), array('//library/library/editCategory', 'category_id' => -1, Yii::app()->getController()->guidParamName => Yii::app()->getController()->contentContainer->guid), array('class' => 'btn btn-primary')); ?></div>
            <?php } ?>
        <?php } ?>
