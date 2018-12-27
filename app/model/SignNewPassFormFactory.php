<?php
 
namespace App\Model;
 
use Nette;
use Nette\Application\UI\Form;
use Nette\Security\User;
 
class SignNewPassFormFactory
{
 
    use Nette\SmartObject;
 
    /** @var FormFactory */
    private $factory;
 
    /** @var User */
    private $user;
 
    public function __construct(FormFactory $factory, User $user)
    {
        $this->factory = $factory;
        $this->user = $user;
    }
 
    /**
     * @return Form
     */
    public function create()
    {
        $form = $this->factory->create();
        $form->addHidden('username');
        $form->addHidden('password_hash');
        $form->addHidden('password_hash_validity');
 
        $form->addPassword('password', 'Nové heslo')
            ->setRequired('Vyplňte své nové heslo');
 
        $form->addSubmit('send', 'Odeslat');
 
        return $form;
    }
}
