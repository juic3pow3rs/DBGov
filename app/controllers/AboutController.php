<?php
namespace Vokuro\Controllers;

/**
 * Display the "About" page.
 */
class AboutController extends ControllerBase
{

    public function initialize()
    {
        $this->view->setVar('logged_in', is_array($this->auth->getIdentity()));
        $this->view->setTemplateBefore('public');
    }
    /**
     *
     * Default action. Set the public layout (layouts/public.volt)
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    public function impressumAction() {

    }

    public function faqAction() {


    }
}
