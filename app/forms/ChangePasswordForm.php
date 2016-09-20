<?php
namespace Vokuro\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\Text;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\Confirmation;

class ChangePasswordForm extends Form
{

    public function initialize($entity = null, $options = null)
    {
        // Password
        $password1 = new Password('password1');

        $password1->addValidators([
            new PresenceOf([
                'message' => 'Bitte Passwort angeben'
            ]),
            new StringLength([
                'min' => 8,
                'messageMinimum' => 'Passwort ist zu kurz. Mindestens 8 Zeichen'
            ]),
            new Confirmation([
                'message' => 'Passwörter stimmen nicht überein',
                'with' => 'confirmPassword'
            ])
        ]);

        $this->add($password1);

        // Confirm Password
        $confirmPassword = new Password('confirmPassword');

        $confirmPassword->addValidators([
            new PresenceOf([
                'message' => 'Bitte Passwort bestätigen'
            ])
        ]);

        $this->add($confirmPassword);

        $email = new Text('email', [
            'placeholder' => 'Email'
        ]);

        $email->addValidators([
            new PresenceOf([
                'message' => 'Bitte Email angeben'
            ]),
            new Email([
                'message' => 'Email Format nicht zulässig'
            ])
        ]);

        $this->add($email);
    }
}
