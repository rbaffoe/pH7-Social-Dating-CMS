<?php
/**
 * @author         Pierre-Henry Soria <ph7software@gmail.com>
 * @copyright      (c) 2012-2013, Pierre-Henry Soria. All Rights Reserved.
 * @license        GNU General Public License; See PH7.LICENSE.txt and PH7.COPYRIGHT.txt in the root directory.
 * @package        PH7 / App / System / Module / Love Calculator / Config
 */
namespace PH7;
defined('PH7') or exit('Restricted access');
use PH7\Framework\Mvc\Router\UriRoute;

class Permission extends PermissionCore
{

    public function __construct()
    {
        parent::__construct();

        if(!UserCore::auth()) {
            Framework\Url\HeaderUrl::redirect(UriRoute::get('user','signup','step1'), $this->signUpMsg());
        }
    }

}