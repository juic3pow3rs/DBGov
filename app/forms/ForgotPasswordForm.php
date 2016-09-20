<?php
namespace Vokuro\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Submit;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;

class ForgotPasswordForm extends Form
{

    public function initialize()
    {
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

        $this->add(new Submit('Send', [
            'class' => 'btn btn-primary'
        ]));
    }
}
