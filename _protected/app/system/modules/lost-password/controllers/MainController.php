<?php
/**
 * @author         Pierre-Henry Soria <ph7software@gmail.com>
 * @copyright      (c) 2012-2013, Pierre-Henry Soria. All Rights Reserved.
 * @license        GNU General Public License; See PH7.LICENSE.txt and PH7.COPYRIGHT.txt in the root directory.
 * @package        PH7 / App / System / Module / Lost Password / Controller
 */
namespace PH7;

use
PH7\Framework\Util\Various,
PH7\Framework\Mvc\Model\Engine\Util\Various as VariousModel,
PH7\Framework\Mail\Mail,
PH7\Framework\Mvc\Router\UriRoute,
PH7\Framework\Url\HeaderUrl;

class MainController extends Controller
{

    private $sTitle;

    public function forgot($sMod = '')
    {
        $this->checkMod($sMod);

        $this->view->page_title = t('Forgot your password?');
        $this->view->h2_title = t('Password Reset');
        $this->output();
    }

    public function reset($sMod = '', $sMail = '', $sHash = '')
    {
        $this->checkMod($sMod);

        $sTable = VariousModel::convertModToTable($sMod);

        if ( ! (new UserCoreModel)->checkHashValidation($sMail, $sHash, $sTable) )
        {
            HeaderUrl::redirect($this->registry->site_url, t('Oops! Email or hash is invalid.'), 'error');
        }
        else
        {
            $sNewPassword = Various::genRndWord(8,40);

            (new UserCoreModel)->changePassword($sMail, $sNewPassword, $sTable, Various::genRnd(),  Various::genRnd());

            $this->view->content = t('Hello!<br />Your password has been changed to <em>"%0%"</em>.<br />Please change it next time you login.', $sNewPassword);

            $sMessageHtml = $this->view->parseMail(PH7_PATH_SYS . 'globals/' . PH7_VIEWS . PH7_TPL_NAME . '/mails/sys/mod/lost-password/recover_password.tpl', $sMail);

            $aInfo = [
                'to' => $sMail,
                'subject' => t('Your new password - %site_name%')
            ];

            if ( ! (new Mail)->send($aInfo, $sMessageHtml) )
                HeaderUrl::redirect($this->registry->site_url, Form::errorSendingEmail(), 'error');
            else
                HeaderUrl::redirect($this->registry->site_url, t('Your new password has been emailed to you.'));
        }

    }

    public function account()
    {
        if (UserCore::auth())
            $sUrl = UriRoute::get('user', 'account', 'index');
        elseif (AffiliateCore::auth())
            $sUrl = UriRoute::get('affiliate', 'account', 'index');
        elseif (AdminCore::auth())
            $sUrl = UriRoute::get(PH7_ADMIN_MOD, 'main', 'index');
        else
            $sUrl = HeaderUrl::redirect($this->registry->site_url);

        HeaderUrl::redirect($sUrl);

    }

    private function checkMod($sMod)
    {
        if ($sMod != 'user' && $sMod != 'affiliate' && $sMod != PH7_ADMIN_MOD)
            HeaderUrl::redirect($this->registry->site_url, t('Module not found!'), 'error');
    }

}