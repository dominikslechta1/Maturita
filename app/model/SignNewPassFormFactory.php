<?php
 
namespace App\Model;
 
use Nette\Application\UI\Form;

class SignNewPassFormFactory
{
 
 
    public function __construct()
    {
    }
 
    /**
     * @return Form
     */
    public function create()
    {
        $form = new Form;
        $form->addText('username')->setDisabled();
        $form->addHidden('hash');
 
        $form->addPassword('password')
            ->setRequired('Vyplňte své nové heslo')->addRule(Form::MIN_LENGTH, 'Minimální délka hesla je 5 znaků', 5);
 
        $form->addSubmit('send');
 
        return $form;
    }
}
