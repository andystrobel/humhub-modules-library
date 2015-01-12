                                        <li class="library-item mime <?php echo $mime; ?>" id="library-item_<?php echo $item->id; ?>"
                                            data-id="<?php echo $item->id; ?>">
                                            <a href="<?php echo $file->getUrl(); ?>" target="_blank">
                                                <span class="title"><?php echo $item->title; ?></span>
                                            </a>
                                            <span class="time" style="padding-right: 20px;"> - <?php echo Yii::app()->format->formatSize($file->size); ?> - <?php echo Yii::app()->dateFormatter->format('dd.MM.y', $item->date); ?></span>
                                            <?php if ($item->description != "") { ?><div class="document-description"><?php echo $item->description; ?></div><?php } ?>

                                            <div class="library-interaction-controls">
                                                <?php $this->widget('application.modules_core.comment.widgets.CommentLinkWidget', array('object' => $item, 'mode' => 'popup')); ?> &middot;
                                                <?php $this->widget('application.modules_core.like.widgets.LikeLinkWidget', array('object' => $item)); ?>
                                            </div>

                                            <?php // all admins and publishers or users that created the document may edit or delete it ?>
                                            <?php if ($editable) { ?>
                                                <div class="library-edit-controls library-editable">
                                                    <?php $this->widget('application.widgets.ModalConfirmWidget', array(
                                                        'uniqueID' => 'modal_documentdelete_' . $item->id,
                                                        'linkOutput' => 'a',
                                                        'class' => 'deleteButton btn btn-xs btn-danger" title="' . Yii::t('LibraryModule.base', 'Delete item'),
                                                        'title' => Yii::t('LibraryModule.base', '<strong>Confirm</strong> item deleting'),
                                                        'message' => Yii::t('LibraryModule.base', 'Do you really want to delete this item?'),
                                                        'buttonTrue' => Yii::t('LibraryModule.base', 'Delete'),
                                                        'buttonFalse' => Yii::t('LibraryModule.base', 'Cancel'),
                                                        'linkContent' => '<i class="fa fa-trash-o"></i>',
                                                        'linkHref' => $this->createUrl("//library/library/deleteItem", array('category_id' => $category->id, 'item_id' => $item->id, Yii::app()->getController()->guidParamName => Yii::app()->getController()->contentContainer->guid)),
                                                        'confirmJS' => 'function() {
                                                                                        $("#library-item_' . $item->id . '").remove();
                                                                                        $("#library-widget-item_' . $item->id . '").remove();
                                                                                        if($("#library-widget-category_' . $category->id . '").find("li").length == 0) {
                                                                                                $("#library-widget-category_' . $category->id . '").remove();
                                                                                        }
                                                                                        if($(".panel-library-widget").find(".media").length == 0) {
                                                                                                $(".panel-library-widget").remove();
                                                                                        }
                                                                                }'
                                                    ));
                                                    echo CHtml::link('<i class="fa fa-pencil"></i>', array('//library/library/editItem', 'item_id' => $item->id, 'category_id' => $category->id, Yii::app()->getController()->guidParamName => Yii::app()->getController()->contentContainer->guid), array('title' => Yii::t('LibraryModule.base', 'Edit item'), 'class' => 'btn btn-xs btn-primary')). ' '; ?>
                                                </div>
                                            <?php } ?>
                                        </li>