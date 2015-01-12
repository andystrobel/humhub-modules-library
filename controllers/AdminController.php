<?php

/**
 * HumHub
 * Copyright © 2014 The HumHub Project
 *
 * The texts of the GNU Affero General Public License with an additional
 * permission and of our proprietary license can be found at and
 * in the LICENSE file you have received along with this program.
 *
 * According to our dual licensing model, this program can be used either
 * under the terms of the GNU Affero General Public License, version 3,
 * or under a proprietary license.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 */

/**
 * Description of AdminController
 *
 * @author Matthias Wolf
 */
class AdminController extends Controller
{

    public $subLayout = "application.modules_core.admin.views._layout";

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow',
                'expression' => 'Yii::app()->user->isAdmin()',
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Configuration Action for Super Admins
     */
    public function actionIndex() {

        Yii::import('library.forms.*');

        $form = new LibraryAdminForm;

        // uncomment the following code to enable ajax-based validation
        //if (isset($_POST['ajax']) && $_POST['ajax'] === 'LibraryAdminForm') {
        //    echo CActiveForm::validate($form);
        //    Yii::app()->end();
        //}

        if (isset($_POST['LibraryAdminForm'])) {
            $_POST['LibraryAdminForm'] = Yii::app()->input->stripClean($_POST['LibraryAdminForm']);
            $form->attributes = $_POST['LibraryAdminForm'];

            if ($form->validate()) {
                $form->globalPublicLibrary = HSetting::Set('globalPublicLibrary', $form->globalPublicLibrary, 'library');
                $form->disclaimerWidget = HSetting::Set('disclaimerWidget', $form->disclaimerWidget, 'library');
                $form->disclaimerTitle = HSetting::Set('disclaimerTitle', $form->disclaimerTitle, 'library');
                $form->disclaimerContent = HSetting::Set('disclaimerContent', $form->disclaimerContent, 'library');
                $this->redirect(Yii::app()->createUrl('library/admin/index'));
            }

        } else {
            $form->globalPublicLibrary = HSetting::Get('globalPublicLibrary', 'library');
            $form->disclaimerWidget = HSetting::Get('disclaimerWidget', 'library');
            $form->disclaimerTitle = HSetting::Get('disclaimerTitle', 'library');
            $form->disclaimerContent = HSetting::Get('disclaimerContent', 'library');
        }

        $this->render('index', array('model' => $form));
    }

}
