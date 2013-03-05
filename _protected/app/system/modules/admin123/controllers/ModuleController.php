<?php
/**
 * @author         Pierre-Henry Soria <ph7software@gmail.com>
 * @copyright      (c) 2012-2013, Pierre-Henry Soria. All Rights Reserved.
 * @license        GNU General Public License; See PH7.LICENSE.txt and PH7.COPYRIGHT.txt in the root directory.
 * @package        PH7 / App / System / Module / Admin / Controller
 */
namespace PH7;
use PH7\Framework\File\File;

class ModuleController extends Controller
{

    private $oModule, $sModulesDirModuleFolder, $sTitle;

    public function __construct()
    {
        parent::__construct();

        $this->oModule = new Module;

        $this->view->oFile = new File;
        $this->view->oModule = $this->oModule;
    }

    public function index()
    {
        if ($this->httpRequest->postExists('submit_mod_install'))
        {
            if ($this->oModule->checkModFolder(Module::INSTALL, $this->httpRequest->post('submit_mod_install')))
            {
                $this->sModulesDirModuleFolder = $this->httpRequest->post('submit_mod_install'); // Module Directory Path
                $this->install();
            }
        }
        elseif ($this->httpRequest->postExists('submit_mod_uninstall'))
        {

            if ($this->oModule->checkModFolder(Module::UNINSTALL, $this->httpRequest->post('submit_mod_uninstall')))
            {
                $this->sModulesDirModuleFolder = $this->httpRequest->post('submit_mod_uninstall'); // Module Directory Path
                $this->unInstall();
            }
        }
        else
        {
            $this->sTitle = t('Module Manager');
            $this->view->page_title = $this->sTitle;
            $this->view->h1_title = $this->sTitle;

            $this->output();
        }
    }

    private function install()
    {
        $this->sTitle = t('Installing Module');
        $this->view->page_title = $this->sTitle;
        $this->view->h1_title = $this->sTitle;

        $this->oModule->setPath($this->sModulesDirModuleFolder);

        $this->oModule->run(Module::INSTALL); // Run Install Module!

        $this->view->content = $this->oModule->readInstruction(Module::INSTALL);

        $this->manualTplInclude('install.tpl');
        $this->output();
    }

    private function unInstall()
    {
        $this->sTitle = t('Uninstalling Module');
        $this->view->page_title = $this->sTitle;
        $this->view->h1_title = $this->sTitle;

        $this->setPath($this->sModulesDirModuleFolder);
        $this->oModule->run(Module::UNINSTALL); // Run Uninstall Module!

        $this->view->content = $this->oModule->readInstruction(Module::UNINSTALL);

        $this->manualTplInclude('uninstall.tpl');
        $this->output();
    }

    public function __destruct()
    {
        unset($this->oModule, $this->sModulesDirModuleFolder, $this->sTitle);
    }

}