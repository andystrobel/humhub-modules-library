<?php
/**
 * View to list all categories and their documents.
 *
 * @uses $categories an array of the categories to show.
 * @uses $items an array of arrays of the documents to show, indicated by the category id.
 *
 * @author Matthias Wolf
 */
?>

        <div id="library-empty-txt" <?php if (empty($categories)) {
            echo 'style="visibility:visible; display:block"';
        } ?>><?php echo Yii::t('LibraryModule.base', 'There have been no public categories added to this space yet.') ?> <i class="fa fa-frown-o"></i></div>

        <div class="library-categories">
            <?php foreach ($categories as $category) { ?>
                <div id="library-category_<?php echo $category->id ?>"
                     class="panel panel-default panel-library-category" data-id="<?php echo $category->id ?>">
                    <div class="panel-heading">
                        <div class="heading">
                            <?php echo $category->title; ?>
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
                                        <?php $this->widget('application.modules.library.widgets.LibraryItemWidget', array('item' => $item, 'category' => $category, 'editable' => false)); ?>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
