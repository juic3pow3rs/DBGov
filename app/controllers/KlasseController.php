<?php
namespace Vokuro\Controllers;

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Vokuro\Forms\CreateKlasseForm;
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

        if (!empty($this->dispatcher->getParams())) {

            $param = $this->dispatcher->getParams();
            $form = $param[0];
            $this->view->form = $form;
        } else {

            $form = new CreateKlasseForm();
            $this->view->form = $form;
        }

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
    /**
     * @todo: evtl. Filesize-Check einbauen, Inhalt der CSV auf ~50 begrenzen
     * @todo: Funktion zum PDF-erstellen
     */
    public function createAction() {

        $form = new CreateKlasseForm();
        $this->view->form = $form;

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "klasse",
                'action' => 'new'
            ]);
            return;
        }

        if (!$form->isValid($this->request->getPost())) {

            $this->dispatcher->forward([
                'controller' => "klasse",
                'action' => 'new',
                'params' => array($form)
            ]);
            return;

        } elseif ($this->request->hasFiles(true) == 0) {

            $this->dispatcher->forward([
                'controller' => "klasse",
                'action' => 'new',
                'params' => array($form)
            ]);
            $this->flash->error("Keine CSV Datei hochgeladen!");
            return;
        }

        $name = $this->request->getPost("name");
        $jahr = $this->request->getPost("jahrgang");

        if ($this->checkKlasse($name, $jahr) != 0) {

            $this->flash->error("Klasse existiert bereits!");
            $this->dispatcher->forward([
                'controller' => "klasse",
                'action' => 'new'
            ]);
            return;
        }

        foreach ($this->request->getUploadedFiles() as $file) {

            if ($file->getExtension() != 'csv') {

                $this->dispatcher->forward([
                    'controller' => "klasse",
                    'action' => 'new',
                    'params' => array($form)
                ]);
                $this->flash->error("Hochgeladene Datei ist keine CSV Datei!");
                return;

            } else {

                $filename = $this->randChars() . $this->randChars() . '.' . $file->getExtension();
                // Move the file into the application
                $file->moveTo('/srv/www/vokuro/cache/temp/' . $filename);
            }
        }

        //@todo Wenn Error Handling eingebaut, abfangen
        // evtl. in die foreach schleife?
        $schueler = $this->csvHandler($filename);
        $anz_usr = count($schueler);

        $anz_db = $this->request->getPost("anzdb");
        $anonym = $this->request->getPost("anonym");

        $db_jahr = str_replace('/','',$jahr);
        $db_name = $name . $db_jahr;

        $dbs = array();
        $usr = array();

        echo count($schueler);
        echo "<pre>";
        print_r($schueler);
        echo "</pre>";

        echo '<pre>';
        echo 'Checkbox:';
        print_r($anonym);
        echo '</pre>';

        echo '<pre>';
        echo 'Name in der DB:';
        print_r($db_name);
        echo '</pre>';

        $klasse = new Klasse();
        $klasse->setName($name);
        $klasse->setJahrgang($jahr);
        $klasse->setListeSchueler("placeholder");
        $klasse->setListeSchuelerAno("placeholder");
        $klasse->setListeLehrer("placeholder");
        $klasse->setListeLehrerAno("placeholder");

        $lhr = $db_name;
        $lhrpass = $this->randChars().$this->randChars();
        $this->createLehrer($lhr, $lhrpass);

        for ($i = 0; $i < $anz_usr; $i++) {

            if ($anonym == 'ja') {
                $usr_name = $db_name.'usr'.$i;
            } else {
                $usr_name = $db_name.$schueler[$i][0].$schueler[$i][1].$i;
            }

            $pw1 = $this->randChars();
            $pw2 = $this->randChars();
            $pw = $pw1.$pw2;

            $usr[$i] = array($usr_name, $pw);

            $db = $this->createSchuelerSet($usr_name, $pw, $anz_db, $lhr);

            $dbs[$i] = $db;
        }

        $this->createGlobalDbs($usr, $lhr);

        echo '<pre>';
        echo 'Alle Schüler DBs<br>';
        print_r($dbs);
        echo '</pre>';

        echo '<pre>';
        echo 'Alle Schüler<br>';
        print_r($usr);
        echo '</pre>';

        echo '<pre>';
        echo 'Lehrer<br>';
        print_r($lhr);
        echo '<br>';
        print_r($lhrpass);
        echo '</pre>';

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

        $this->flash->success("Klasse erfolgreich erstellt");

    }

    /**
     * Saves a klasse edited
     *
     */
    public function saveAction()
    {

        $form = new CreateKlasseForm();
        $this->view->form = $form;

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

        $name = $klasse->getName();
        $jahr = $klasse->getJahrgang();
        $db_jahr = str_replace('/','',$jahr);
        $db_name = $name . $db_jahr;


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

        $connection = new Adapter(get_object_vars($this->config->database));
        $connection->connect();

        $dbset = $connection->query("SELECT CONCAT('DROP DATABASE `', SCHEMA_NAME, '`;') FROM `information_schema`.`SCHEMATA` WHERE SCHEMA_NAME LIKE ?", array($db_name.'%'));

        $db = $dbset->fetchAll();

        $userset = $connection->query("SELECT user FROM mysql.user WHERE user like ?", array($db_name.'%'));

        $user = $userset->fetchAll();

        for ($i = 0; $i < $dbset->numRows(); $i++) {
            $connection->execute($db[$i][0]);
            $connection->execute('FLUSH PRIVILEGES');
        }

        for ($j = 0; $j < $userset->numRows(); $j++) {
            $connection->execute('DROP USER \''.$user[$j][0].'\'@\'localhost\'');
            $connection->execute('FLUSH PRIVILEGES');
        }

        $this->flash->success("klasse was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "klasse",
            'action' => "index"
        ]);
    }

    public function createSchuelerSet($name, $pass, $anz, $lhr) {

        $dbs = array();

        $connection = new Adapter(get_object_vars($this->config->database));
        $connection->connect();

        $connection->execute('CREATE USER \''.$name.'\'@\'localhost\' IDENTIFIED BY \''.$pass.'\'');

        for ($i = 1; $i <= $anz; $i++) {

            $connection->execute('CREATE DATABASE IF NOT EXISTS '.$name.'_'.$i);
            $connection->execute('GRANT ALL PRIVILEGES ON '.$name.'_'.$i.'.* TO \''.$name.'\'@\'localhost\'');
            $connection->execute('GRANT ALL PRIVILEGES ON '.$name.'_'.$i.'.* TO \''.$lhr.'\'@\'localhost\'');

            $dbs[$i-1] = $name.'_'.$i;
        }

        $connection->execute('FLUSH PRIVILEGES');

        return $dbs;

    }

    public function createLehrer($name, $pass) {

        $connection = new Adapter(get_object_vars($this->config->database));
        $connection->connect();

        $connection->execute('CREATE USER \''.$name.'\'@\'localhost\' IDENTIFIED BY \''.$pass.'\'');

        $connection->execute('FLUSH PRIVILEGES');

    }

    public function createGlobalDbs($usr, $lhr) {
        //Hier die globalen DBs erstellen und Schüler R/W auf eine und R auf die andere geben, Lehrer hat bei beiden R/W Rechte

        $anz = count($usr);

        $connection = new Adapter(get_object_vars($this->config->database));
        $connection->connect();

        //1 = Nur lesen, 2 = Lesen & Schreiben
        $connection->execute('CREATE DATABASE IF NOT EXISTS '.$lhr.'_1');
        $connection->execute('CREATE DATABASE IF NOT EXISTS '.$lhr.'_2');

        //Dem Lehrer R&W Rechte auf beide DBs geben
        $connection->execute('GRANT ALL PRIVILEGES ON '.$lhr.'_1.* TO \''.$lhr.'\'@\'localhost\'');
        $connection->execute('GRANT ALL PRIVILEGES ON '.$lhr.'_2.* TO \''.$lhr.'\'@\'localhost\'');

        $connection->execute('FLUSH PRIVILEGES');

        for ($i = 0; $i < $anz; $i++) {

            //Dem aktuellen Schüler, R-Rechte auf die 1 geben
            $connection->execute('GRANT SELECT ON '.$lhr.'_1.* TO \''.$usr[$i][0].'\'@\'localhost\'');
            //Dem aktuellen Schüler R&W-Rechte auf die 2 geben
            $connection->execute('GRANT SELECT, INSERT, UPDATE ON '.$lhr.'_2.* TO \''.$usr[$i][0].'\'@\'localhost\'');

        }

        $connection->execute('FLUSH PRIVILEGES');

    }

    public function checkKlasse($name, $jahr) {

        $connection = new Adapter(get_object_vars($this->config->database));
        $connection->connect();

        $resultset = $connection->query("SELECT * FROM klasse WHERE name = ? AND jahrgang = ?", array($name, $jahr));

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


    /**
     * @param $filename
     * @return array
     * @todo Error Handling beim Datei öffnen und auslesen
     */
    public function csvHandler($filename) {

        $uml = file_get_contents('/srv/www/vokuro/cache/temp/' . $filename);
        $uml = iconv('ISO-8859-15', 'UTF-8', $uml);
        $uml = preg_replace('/[^a-zA-Z0-9;\r\n]/', '', $uml);
        $uml = strtolower($uml);
        file_put_contents('/srv/www/vokuro/cache/temp/' . $filename, $uml);

        $i = 0;
        $schueler = array();
        $csv = fopen('/srv/www/vokuro/cache/temp/' . $filename, "r");
        while (($line = fgetcsv($csv, 0, ';')) !== FALSE) {
            $schueler[$i] = $line;
            $i++;
        }
        fclose($csv);
        unlink('/srv/www/vokuro/cache/temp/' . $filename);

        for ($j = 0; $j < $i; $j++) {
            $cnt = count($schueler[$j]);
            for ($k = 2; $k < $cnt; $k++) {
                unset($schueler[$j][$k]);
            }
            $schueler[$j][0] = substr($schueler[$j][0], 0, 2);
            $schueler[$j][1] = substr($schueler[$j][1], 0, 2);
        }

        return $schueler;
    }

}
