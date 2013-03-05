<?php
/**
 * @author         Pierre-Henry Soria <ph7software@gmail.com>
 * @copyright      (c) 2012-2013, Pierre-Henry Soria. All Rights Reserved.
 * @license        GNU General Public License; See PH7.LICENSE.txt and PH7.COPYRIGHT.txt in the root directory.
 * @package        PH7 / App / System / Module / Forum / Form
 */
namespace PH7;

use PH7\Framework\Config\Config, PH7\Framework\Mvc\Request\HttpRequest;

class ForumForm
{

    public static function display()
    {
        if (isset($_POST['submit_forum']))
        {
            if (\PFBC\Form::isValid($_POST['submit_forum']))
                new ForumFormProcessing();

            Framework\Url\HeaderUrl::redirect();
        }

        $oForumModel = new ForumModel();

        $oCategoriesData = $oForumModel->getCategory(null, 0, 300);

        $aCategoriesName = array();
        foreach($oCategoriesData as $id)
            $aCategoriesName[$id->categoryId] = $id->title;

        unset($oForumModel);

        $oForm = new \PFBC\Form('form_forum', '100%');
        $oForm->configure(array('action' => ''));
        $oForm->addElement(new \PFBC\Element\Hidden('submit_forum', 'form_forum'));
        $oForm->addElement(new \PFBC\Element\Token('forum'));

        $oHttpRequest = new HttpRequest();
        $oForm->addElement(new \PFBC\Element\Select(t('Category Name:'), 'category_id',  $aCategoriesName, array('value'=>$oHttpRequest->get('category_id'), 'required' => 1)));
        unset($oHttpRequest);

        $oForm->addElement(new \PFBC\Element\Textbox(t('Forum Name:'), 'name', array('id'=>'str_name', 'onblur'=>'CValid(this.value,this.id,4,60)', 'required' => 1, 'validation'=>new \PFBC\Validation\RegExp(Config::getInstance()->values['module.setting']['url_title.pattern']))));
        $oForm->addElement(new \PFBC\Element\HTMLExternal('<span class="input_error str_name"></span>'));
        $oForm->addElement(new \PFBC\Element\Textarea(t('Description:'), 'description', array('id'=>'str_description', 'required' => 1, 'onblur'=>'CValid(this.value,this.id,4,255)', 'validation'=>new \PFBC\Validation\Str(4,255))));
        $oForm->addElement(new \PFBC\Element\HTMLExternal('<span class="input_error str_description"></span>'));
        $oForm->addElement(new \PFBC\Element\Button);
        $oForm->addElement(new \PFBC\Element\HTMLExternal('<script src="'.PH7_URL_STATIC.PH7_JS.'validate.js"></script>'));
        $oForm->render();
    }

}