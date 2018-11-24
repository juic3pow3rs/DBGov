<?php
/**
 * Created by PhpStorm.
 * User: Andi
 * Date: 20.09.2016
 * Time: 11:58
 */

namespace Vokuro\Controllers;


class ImpressumController extends ControllerBase
{

    public function initialize()
    {
        $this->view->setVar('logged_in', is_array($this->auth->getIdentity()));
        $this->view->setTemplateBefore('public');
    }

    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    public function editImpressumAction() {

    }

    public function editAction() {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "impressum",
                'action' => 'index'
            ]);
            return;
        }

        $name = $this->request->getPost("name");
        $strassenr = $this->request->getPost("strassenr");
        $ort = $this->request->getPost("ort");
        $telefon = $this->request->getPost("telefon");
        $mail = $this->request->getPost("mail");

        $impressum = file_get_contents($_SERVER['APACHE_DOCUMENT_ROOT'].'/app/views/about/impressum.volt.template');

        $vars = array(
            '$name' => $name,
            '$strassenr' => $strassenr,
            '$ort' => $ort,
            '$telefon' => $telefon,
            '$mail' => $mail
        );

        $impressum = strtr($impressum, $vars);

        file_put_contents($_SERVER['APACHE_DOCUMENT_ROOT'].'/app/views/about/impressum.volt', $impressum);

        if (file_exists($_SERVER['APACHE_DOCUMENT_ROOT'].'/cache/volt/_app_app_views_about_impressum.volt.php') == true) {
            unlink($_SERVER['APACHE_DOCUMENT_ROOT'].'/cache/volt/_app_app_views_about_impressum.volt.php');
        }

        $this->flash->success("Impressum erfolgreich geändert");

        $this->dispatcher->forward([
            'controller' => "about",
            'action' => 'impressum'
        ]);

    }


}