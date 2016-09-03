<?php
namespace Vokuro\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Submit;
use Phalcon\Forms\Element\Check;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Numeric;
use Phalcon\Forms\Element\File;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Identical;

class CreateKlasseForm extends Form
{

    public function initialize()
    {
        // Name
        $name = new Text('name', [
            'placeholder' => 'Name'
        ]);

        $name->addValidators([
            new PresenceOf([
                'message' => 'Bitte Namen angeben!'
            ])
        ]);

        $this->add($name);

        // Jahrgang
        $jahrgang = new Text('jahrgang', [
            'placeholder' => 'Jahrgang im Format 16/17'
        ]);

        $jahrgang->addValidator(new PresenceOf([
            'message' => 'Bitte Jahrgang angeben!'
        ]));

        $this->add($jahrgang);

        // Anzahl Benutzer
        $anzusr = new Numeric('anzusr', [
            'placeholder' => 'Anzahl Benutzer'
        ]);

        $anzusr->addValidator(new PresenceOf([
            'message' => 'Bitte Anzahl Benutzer angeben!'
        ]));

        $this->add($anzusr);

        // Anzahl DBs pro Benutzer
        $anzdbs = new Numeric('anzdb', [
            'placeholder' => 'Anzahl DBs'
        ]);

        $anzdbs->addValidator(new PresenceOf([
            'message' => 'Bitte Anzahl DBs angeben!'
        ]));

        $this->add($anzdbs);

        // CSV Datei
        $csvfile = new File('csvfile', [
            'placeholder' => 'CSV Datei der Schüler'
        ]);

        $csvfile->addValidator(new PresenceOf([
            'message' => 'Bitte CSV Datei hochladen!'
        ]));

        $this->add($csvfile);

        // Remember
        /**$remember = new Check('remember', [
            'value' => 'yes'
        ]);

        $remember->setLabel('Remember me');

        $this->add($remember);**/

        // CSRF
        /**$csrf = new Hidden('csrf');

        $csrf->addValidator(new Identical([
            'value' => $this->security->getSessionToken(),
            'message' => 'CSRF validation failed'
        ]));

        $csrf->clear();

        $this->add($csrf);**/

        $this->add(new Submit('Speichern', [
            'class' => 'btn btn-success'
        ]));
    }
}
