<?php
namespace Vokuro\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Numeric;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Regex;

class CreateKlasseForm extends Form
{

    /**
     * @todo: csrf wieder rein
     */
    public function initialize()
    {
        // Name
        $name = new Text('name', [
            'placeholder' => 'Name im Format 12d'
        ]);

        $name->addValidators([
            new PresenceOf([
                'message' => 'Bitte Namen angeben!'
            ]),
            new Regex([
                'pattern' => '/([5-9]|1[0-3])[a-z]{1,2}/',
                'message' => 'Bitte Name in Form von 10a, 9d oder 12an (naturwissenschaftlich) angeben!'
            ])
        ]);

        $this->add($name);

        // Jahrgang
        $jahrgang = new Text('jahrgang', [
            'placeholder' => 'Jahrgang im Format 16/17'
        ]);

        $jahrgang->addValidators([
            new PresenceOf([
                'message' => 'Bitte Jahrgang angeben!'
            ]),
            new Regex([
                'pattern' => '/\d\d\/\d\d/',
                'message' => 'Bitte Jahrgang in Form von 16/17, 17/18 etc. angeben'
            ])
        ]);

        $this->add($jahrgang);

        // Anzahl DBs pro Benutzer
        $anzdb = new Numeric('anzdb', [
            'placeholder' => 'Anzahl DBs pro Schüler',
            'min' => '1',
            'max' => '3'
        ]);

        $anzdb->addValidator(new PresenceOf([
            'message' => 'Bitte Anzahl DBs angeben!'
        ]));

        $this->add($anzdb);

        // CSRF
        /**$csrf = new Hidden('csrf');

        $csrf->addValidator(new Identical([
            'value' => $this->security->getSessionToken(),
            'message' => 'CSRF validation failed'
        ]));

        $csrf->clear();

        $this->add($csrf);**/

        /**$this->add(new Submit('Speichern', [
            'class' => 'btn btn-success'
        ]));**/
    }

    public function messages($name)
    {
        if ($this->hasMessagesFor($name)) {
            foreach ($this->getMessagesFor($name) as $message) {
                $this->flash->error($message);
            }
        }
    }
}
