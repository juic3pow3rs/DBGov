<?php
namespace Vokuro\Controllers;

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Vokuro\Models\Klasse;
use Phalcon\Db\Adapter\Pdo\Mysql as Adapter;

class KlasseController extends ControllerBase
{

    public function initialize()
    {
        $this->view->setTemplateBefore('public');
    }

    /**
     * Index action
     */
    public function indexAction()
    {

        $connection = new Adapter(get_object_vars($this->config->database));
        $connection->connect();

        /*
        $this->createSchuelerSet('testhape', 'testhape' , 3);
        $this->createSchuelerSet('testanmu', 'testhape' , 3);
        $this->createSchuelerSet('testjowi', 'testhape' , 3);
        $this->createSchuelerSet('testfrle', 'testhape' , 3);
        */

        $name = '9b';
        $jahr = '16';
        $rand = $this->randChars();

        $rnd_name = $name.$jahr.$rand;

        echo $rnd_name;

        $this->persistent->parameters = null;

    }

    /**
     * Searches for klasse
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, '\Vokuro\Models\Klasse', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "id";

        $klasse = Klasse::find($parameters);
        if (count($klasse) == 0) {
            $this->flash->notice("The search did not find any klasse");

            $this->dispatcher->forward([
                "controller" => "klasse",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $klasse,
            'limit'=> 10,
            'page' => $numberPage
        ]);

        $this->view->page = $paginator->getPaginate();
    }

    /**
     * Displays the creation form
     */
    public function newAction()
    {

    }

    /**
     * Edits a klasse
     *
     * @param string $id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {

            $klasse = Klasse::findFirstByid($id);
            if (!$klasse) {
                $this->flash->error("klasse was not found");

                $this->dispatcher->forward([
                    'controller' => "klasse",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->id = $klasse->id;

            $this->tag->setDefault("id", $klasse->getId());
            $this->tag->setDefault("name", $klasse->getName());
            $this->tag->setDefault("jahrgang", $klasse->getJahrgang());
            $this->tag->setDefault("rnd_name", $klasse->getRndName());
            $this->tag->setDefault("liste_schueler", $klasse->getListeSchueler());
            $this->tag->setDefault("liste_schueler_ano", $klasse->getListeSchuelerAno());
            $this->tag->setDefault("liste_lehrer", $klasse->getListeLehrer());
            $this->tag->setDefault("liste_lehrer_ano", $klasse->getListeLehrerAno());
            
        }
    }

    /**
     * Creates a new klasse
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "klasse",
                'action' => 'index'
            ]);

            return;
        }

        $name = $this->request->getPost("name");
        $jahr = $this->request->getPost("jahrgang");
        $anz_usr = $this->request->getPost("anz_usr");
        $anz_db = $this->request->getPost("anz_db");
        $rand = $this->randChars();
        $rnd_name = $name.$jahr.$rand;

        echo '<pre>';
        echo 'Eindeutiger Name bestehend aus Name, Jahrgang und 3 zufälligen Buchstaben:';
        print_r($rnd_name);
        echo '</pre>';

        if ($this->checkUser($rnd_name) != 0) {

            $this->flash->error("Klasse existiert bereits!");

            $this->dispatcher->forward([
                'controller' => "klasse",
                'action' => 'new'
            ]);
        }

        $klasse = new Klasse();
        $klasse->setName($name);
        $klasse->setJahrgang($jahr);
        $klasse->setRndName($rnd_name);
        $klasse->setListeSchueler("placeholder");
        $klasse->setListeSchuelerAno("placeholder");
        $klasse->setListeLehrer("placeholder");
        $klasse->setListeLehrerAno("placeholder");


        for ($i = 1; $i <= $anz_usr; $i++) {

            $usr_name = $rnd_name.$i;
            $pw1 = $this->randChars();
            $pw2 = $this->randChars();
            $pw = $pw1.$pw2;

            echo '<pre>';
            echo 'Name und Passwort des Schülers<br>';
            print_r($usr_name);
            echo '<br>';
            print_r($pw);
            echo '</pre>';

            $this->createSchuelerSet($usr_name, $pw, $anz_db);
        }



        if (!$klasse->save()) {
            foreach ($klasse->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "klasse",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("klasse was created successfully");

        /**$this->dispatcher->forward([
            'controller' => "klasse",
            'action' => 'index'
        ]);**/
    }

    /**
     * Saves a klasse edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "klasse",
                'action' => 'index'
            ]);

            return;
        }

        $id = $this->request->getPost("id");
        $klasse = Klasse::findFirstByid($id);

        if (!$klasse) {
            $this->flash->error("klasse does not exist " . $id);

            $this->dispatcher->forward([
                'controller' => "klasse",
                'action' => 'index'
            ]);

            return;
        }

        $klasse->setName($this->request->getPost("name"));
        $klasse->setJahrgang($this->request->getPost("jahrgang"));
        $klasse->setRndName($this->request->getPost("rnd_name"));
        $klasse->setListeSchueler($this->request->getPost("liste_schueler"));
        $klasse->setListeSchuelerAno($this->request->getPost("liste_schueler_ano"));
        $klasse->setListeLehrer($this->request->getPost("liste_lehrer"));
        $klasse->setListeLehrerAno($this->request->getPost("liste_lehrer_ano"));
        

        if (!$klasse->save()) {

            foreach ($klasse->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "klasse",
                'action' => 'edit',
                'params' => [$klasse->id]
            ]);

            return;
        }

        $this->flash->success("klasse was updated successfully");

        $this->dispatcher->forward([
            'controller' => "klasse",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a klasse
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $klasse = Klasse::findFirstByid($id);
        if (!$klasse) {
            $this->flash->error("klasse was not found");

            $this->dispatcher->forward([
                'controller' => "klasse",
                'action' => 'index'
            ]);

            return;
        }

        if (!$klasse->delete()) {

            foreach ($klasse->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "klasse",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("klasse was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "klasse",
            'action' => "index"
        ]);
    }

    public function createSchuelerSet($name, $pass, $anz) {

        $connection = new Adapter(get_object_vars($this->config->database));
        $connection->connect();

        $connection->execute('CREATE USER \''.$name.'\'@\'localhost\' IDENTIFIED BY \''.$pass.'\'');

        for ($i = 1; $i <= $anz; $i++) {

            $connection->execute('CREATE DATABASE IF NOT EXISTS '.$name.'_'.$i);
            $connection->execute('GRANT ALL PRIVILEGES ON '.$name.$i.'.* TO \''.$name.'\'@\'localhost\'');
        }

        $connection->execute('FLUSH PRIVILEGES');

    }

    public function checkUser($name) {

        $connection = new Adapter(get_object_vars($this->config->database));
        $connection->connect();

        $resultset = $connection->query("SELECT user FROM mysql.user WHERE user like ?", array($name.'%'));

        if ($resultset->numRows() != 0) {
            return 1;
        } else {
            return 0;
        }

    }

    public function randChars() {

        $rand = substr(str_shuffle(str_repeat("abcdefghijklmnopqrstuvwxyz", 3)), 0, 3);
        return $rand;
    }

}
