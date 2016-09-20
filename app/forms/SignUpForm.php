<?php
namespace Vokuro\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\Submit;
use Phalcon\Forms\Element\Check;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\Confirmation;

class SignUpForm extends Form
{

    public function initialize($entity = null, $options = null)
    {
        $name = new Text('name');

        $name->setLabel('Name');

        $name->addValidators([
            new PresenceOf([
                'message' => 'Bitte Name angeben'
            ])
        ]);

        $this->add($name);

        // Email
        $email = new Text('email');

        $email->setLabel('E-Mail');

        $email->addValidators([
            new PresenceOf([
                'message' => 'Bitte Email angeben'
            ]),
            new Email([
                'message' => 'Email Format unzulässig'
            ])
        ]);

        $this->add($email);

        // Password
        $password = new Password('password');

        $password->setLabel('Password');

        $password->addValidators([
            new PresenceOf([
                'message' => 'Bitte Passwort angeben'
            ]),
            new StringLength([
                'min' => 8,
                'messageMinimum' => 'Passwort zu kurz. Mindestens 8 Zeichen'
            ]),
            new Confirmation([
                'message' => 'Passwörter stimmen nicht überein',
                'with' => 'confirmPassword'
            ])
        ]);

        $this->add($password);

        // Confirm Password
        $confirmPassword = new Password('confirmPassword');

        $confirmPassword->setLabel('Confirm Password');

        $confirmPassword->addValidators([
            new PresenceOf([
                'message' => 'Bitte Passwort bestätigen'
            ])
        ]);

        $this->add($confirmPassword);

        // Remember
        $terms = new Check('terms', [
            'value' => 'yes'
        ]);

        $terms->setLabel('TOS zustimmen');

        $terms->addValidator(new Identical([
            'value' => 'yes',
            'message' => 'TOS müssen aktzeptiert werden'
        ]));

        $this->add($terms);

        // CSRF
        $csrf = new Hidden('csrf');

        $csrf->addValidator(new Identical([
            'value' => $this->security->getSessionToken(),
            'message' => 'CSRF Validierung fehlgeschlagen'
        ]));

        $csrf->clear();

        $this->add($csrf);

        // Sign Up
        $this->add(new Submit('Sign Up', [
            'class' => 'btn btn-success'
        ]));
    }

    /**
     * Prints messages for a specific element
     */
    public function messages($name)
    {
        if ($this->hasMessagesFor($name)) {
            foreach ($this->getMessagesFor($name) as $message) {
                $this->flash->error($message);
            }
        }
    }
}
