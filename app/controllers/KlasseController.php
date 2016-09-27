<?php
namespace Vokuro\Controllers;

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Vokuro\Forms\CreateKlasseForm;
use Vokuro\Models\Klasse;
use Phalcon\Db\Adapter\Pdo\Mysql as Adapter;
use Vokuro\Models\Pdf;
use Phalcon\Http\Response;

class KlasseController extends ControllerBase
{

    public function initialize()
    {
        $this->view->setVar('logged_in', is_array($this->auth->getIdentity()));
        $this->view->setTemplateBefore('private');
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
    public function listAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            //$query = Criteria::fromInput($this->di, '\Vokuro\Models\Klasse', $_POST);
            //$this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "id";

        $this->view->cnt = 1;

        $klasse = Klasse::find($parameters);
        if (count($klasse) == 0) {

            echo "Keine Klassen vorhanden!";
            $this->view->cnt = 0;

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
                $this->flash->error("Klasse nicht gefunden");

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
            $this->tag->setDefault("liste_lehrer", $klasse->getListeLehrer());
            
        }
    }

    /**
     * Creates a new klasse
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
                $this->flash->error("Hochgeladene Datei ist keine CSV Datei");
                return;

            } elseif ($file->getSize() > 100000) {

                $this->dispatcher->forward([
                    'controller' => "klasse",
                    'action' => 'new',
                    'params' => array($form)
                ]);
                $this->flash->error("Datei überschreitet maximale Größe von 100kb");
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

        if($schueler == 1) {
            $this->dispatcher->forward([
                'controller' => "klasse",
                'action' => 'new',
                'params' => array($form)
            ]);
            $this->flash->error("Mehr als 40 Schüler in der CSV-Datei (Max. 40)");
            return;
        }

        if($schueler == 2) {
            $this->dispatcher->forward([
                'controller' => "klasse",
                'action' => 'new',
                'params' => array($form)
            ]);
            $this->flash->error("Leere CSV-Datei angegeben");
            return;
        }

        $anz_usr = count($schueler);

        $anz_db = $this->request->getPost("anzdb");
        $anonym = $this->request->getPost("anonym");

        $db_jahr = str_replace('/','',$jahr);
        $db_name = $name . $db_jahr;

        $dbs = array();
        $usr = array();

        $klasse = new Klasse();
        $klasse->setName($name);
        $klasse->setJahrgang($jahr);
        $klasse->setListeSchueler($db_name.'.pdf');
        $klasse->setListeLehrer($db_name.'_lehrer.pdf');

        $lhr = $db_name;
        $lhrpass = $this->randChars().$this->randChars();
        $this->createLehrer($lhr, $lhrpass);

        for ($i = 0; $i < $anz_usr; $i++) {
            $nr = $i+1;
            if ($anonym == 'ja') {
                $usr_name = $db_name.'usr'.$nr;
            } else {
                $usr_name = $db_name.$schueler[$i][0].$schueler[$i][1].$nr;
            }

            $pw1 = $this->randChars();
            $pw2 = $this->randChars();
            $pw = $pw1.$pw2;

            $usr[$i] = array($usr_name, $pw);

            $db = $this->createSchuelerSet($usr_name, $pw, $anz_db, $lhr);

            $dbs[$i] = $db;
        }

        $this->createGlobalDbs($usr, $lhr);

        $arr[0] = $lhr;
        $arr[1] = $lhrpass;

        $lhrarr[0] = $arr;

        $pdfheading[0] = $name;
        $pdfheading[1] = $jahr;

        $this->createSchuelerPdf(0, $pdfheading, $usr);
        $this->createrLehrerPdf(0, $pdfheading, $usr, $lhrarr);

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

        $this->dispatcher->forward([
        'controller' => "klasse",
        'action' => 'list'
        ]);

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
            $this->flash->error("Klasse existiert nicht " . $id);

            $this->dispatcher->forward([
                'controller' => "klasse",
                'action' => 'index'
            ]);

            return;
        }

        $klasse->setName($this->request->getPost("name"));
        $klasse->setJahrgang($this->request->getPost("jahrgang"));
        $klasse->setListeSchueler($this->request->getPost("liste_schueler"));
        $klasse->setListeLehrer($this->request->getPost("liste_lehrer"));

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

        $this->flash->success("Klasse erfolgreich aktualisiert");

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
            $this->flash->error("Klasse nicht gefunden");

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

        unlink('/srv/www/vokuro/public/pdf/'.$db_name.'.pdf');
        unlink('/srv/www/vokuro/public/pdf/'.$db_name.'_lehrer.pdf');

        $this->flash->success("Klasse erfolgreich gelöscht");

        $this->dispatcher->forward([
            'controller' => "klasse",
            'action' => "list"
        ]);
    }

    public function confirmAction($id) {

        $klasse = Klasse::findFirstByid($id);
        if (!$klasse) {
            $this->flash->error("Klasse nicht gefunden");

            $this->dispatcher->forward([
                'controller' => "klasse",
                'action' => 'index'
            ]);

            return;
        }

        $this->view->klasse = $klasse;

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

        $anz = count($usr);

        $connection = new Adapter(get_object_vars($this->config->database));
        $connection->connect();

        //1 = Nur lesen, 2 = Lesen & Schreiben
        $connection->execute('CREATE DATABASE IF NOT EXISTS '.$lhr.'_read');
        $connection->execute('CREATE DATABASE IF NOT EXISTS '.$lhr.'_write');

        //Dem Lehrer R&W Rechte auf beide DBs geben
        $connection->execute('GRANT ALL PRIVILEGES ON '.$lhr.'_read.* TO \''.$lhr.'\'@\'localhost\'');
        $connection->execute('GRANT ALL PRIVILEGES ON '.$lhr.'_write.* TO \''.$lhr.'\'@\'localhost\'');

        $connection->execute('FLUSH PRIVILEGES');

        for ($i = 0; $i < $anz; $i++) {

            //Dem aktuellen Schüler, R-Rechte auf die 1 geben
            $connection->execute('GRANT SELECT ON '.$lhr.'_read.* TO \''.$usr[$i][0].'\'@\'localhost\'');
            //Dem aktuellen Schüler R&W-Rechte auf die 2 geben
            $connection->execute('GRANT SELECT, INSERT, UPDATE ON '.$lhr.'_write.* TO \''.$usr[$i][0].'\'@\'localhost\'');

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
     * @return mixed
     * @todo Error Handling beim Datei öffnen und auslesen
     * @todo Schüler durchnummerieren und in die PDF eintragen
     */
    public function csvHandler($filename) {

        $uml = file_get_contents('/srv/www/vokuro/cache/temp/' . $filename);
        $uml = iconv('ISO-8859-15', 'UTF-8', $uml);
        $uml = preg_replace('/[^a-zA-Z0-9;,\r\n]/', '', $uml);
        $uml = strtolower($uml);
        file_put_contents('/srv/www/vokuro/cache/temp/' . $filename, $uml);

        $delimiter = $this->detectDelimiter('/srv/www/vokuro/cache/temp/' . $filename);

        $i = 0;
        $schueler = array();
        $csv = fopen('/srv/www/vokuro/cache/temp/' . $filename, "r");
        while (($line = fgetcsv($csv, 0, $delimiter)) !== FALSE) {
            $schueler[$i] = $line;
            $i++;
        }
        fclose($csv);
        unlink('/srv/www/vokuro/cache/temp/' . $filename);

        //Leere Zeilen entferne diese und korrigiere $i
        for ($j = 0; $j < $i; $j++){

            $schueler[$j] = array_filter($schueler[$j]);

            if (empty($schueler[$j])) {
                unset($schueler[$j]);
            }
        }

        array_filter($schueler);
        $i = count($schueler);

        //Fehler, wenn die CSV mehr als 40 Schüler beinhaltet
        if ($i > 40) {

            return 1;
        }

        //Fehler, wenn die CSV leer ist
        if (empty($schueler) == true) {

            return 2;
        }

        // Alle Daten außer Name und Vorname löschen, Name und Vorname jeweils auf die ersten beiden Buchstaben strippen
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

    /**
     * @param $csvFile
     * @return mixed
     * Code von: https://coderwall.com/p/qsldcq/php-csv-autodetect-delimiter
     */
    public function detectDelimiter($csvFile)
    {
        $delimiters = array(
            ';' => 0,
            ',' => 0,
            "\t" => 0,
            "|" => 0
        );

        $handle = fopen($csvFile, "r");
        $firstLine = fgets($handle);
        fclose($handle);
        foreach ($delimiters as $delimiter => &$count) {
            $count = count(str_getcsv($firstLine, $delimiter));
        }

        return array_search(max($delimiters), $delimiters);
    }

    public function createSchuelerPdf($ano = 0, $rawname, $usr) {

        $db_jahr = str_replace('/','',$rawname[1]);
        $filename = $rawname[0] . $db_jahr;

        $pdf = new Pdf();

        // Column headings
        $header = array('Name', 'Benutzername', 'Passwort');

        $pdf->AddPage();
        $pdf->SetFont('Arial','B',18);
        $pdf->Cell(40,10,'Klasse '.$rawname[0].' '.$rawname[1]);
        $pdf->Ln();

        // Überschrift über der Tabelle
        $pdf->SetFont('Arial','B',16);
        $pdf->Cell(40,10,utf8_decode('Zugangsdaten Schüler'));
        $pdf->Ln();

        //Hier kommt die Tabelle
        $pdf->SetFont('Arial','',14);
        $pdf->BasicTable($header, $usr);
        $pdf->Output('F', '/srv/www/vokuro/public/pdf/'. $filename .'.pdf');

    }

    public function createrLehrerPdf($ano = 0, $rawname, $usr, $lhr) {

        $db_jahr = str_replace('/','',$rawname[1]);
        $filename = $rawname[0] . $db_jahr;

        $pdf = new Pdf();

        // Column headings
        $header = array('Name', 'Benutzername', 'Passwort');

        $pdf->AddPage();
        $pdf->SetFont('Arial','B',18);
        $pdf->Cell(40,10,'Klasse '.$rawname[0].' '.$rawname[1]);
        $pdf->Ln();

        //Überschrift über der Tabelle
        $pdf->SetFont('Arial','B',16);
        $pdf->Cell(40,10,'Zugangsdaten Lehrer');
        $pdf->Ln();

        //Tabellen
        $pdf->SetFont('Arial','',14);
        $pdf->BasicTable($header, $lhr);
        $pdf->Ln();
        $pdf->SetFont('Arial','B',16);
        $pdf->Cell(40,10,utf8_decode('Zugangsdaten Schüler'));
        $pdf->Ln();
        $pdf->SetFont('Arial','',14);
        $pdf->BasicTable($header, $usr);
        $pdf->Output('F', '/srv/www/vokuro/public/pdf/'. $filename .'_lehrer.pdf');
    }

    public function downloadAction($file) {

        $response = new Response();
        $path = '/srv/www/vokuro/public/pdf/'.$file;
        $filetype = filetype($path);
        $filesize = filesize($path);
        $response->setHeader("Cache-Control", 'must-revalidate, post-check=0, pre-check=0');
        $response->setHeader("Content-Description", 'File Download');
        $response->setHeader("Content-Type", $filetype);
        $response->setHeader("Content-Length", $filesize);
        $response->setFileToSend($path, str_replace(" ","-",$file), true);
        $response->send();
        die();

    }

}
