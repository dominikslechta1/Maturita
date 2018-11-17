<?php

namespace App\Presenters;

use Nette\Database;
use Nette;
use Nette\Application\UI;
use App\Presenters;
use Nette\Security\User;
use Nette\Database\Context;

class LoginPresenter extends Nette\Application\UI\Presenter {

    private $database;

    // pro práci s vrstvou Database Explorer si předáme Nette\Database\Context
    public function __construct(Nette\Database\Context $connection, Nette\Database\IStructure $structure, Nette\Database\IConventions $conventions = null, Nette\Caching\IStorage $cacheStorage = null) {
        $this->database = $connection;
    }

    protected function startup() {
        parent::startup();

        $user = $this->getUser();

        //$authenticate = \App\Model\MyAuthenticator::authenticate(['john','12345']);
    }

//    public function renderLogin(){
//        
//    }
    public function renderRegister() {
        
    }

    protected function createComponentLoginForm() {
        $form = new UI\Form;
        $form->getElementPrototype()->autocomplete = 'off';
        $form->addProtection('Vypršel časový limit, odešlete formulář znovu');
        $form->addEmail('email', 'Email:')->setRequired("email je dulezity")->setHtmlAttribute('placeholder', 'user@example.com');
        $form->addPassword('password', 'Heslo:')->setRequired('zadejte heslo');
        $form->addSubmit('login', 'Přihlásit');
        $form->onValidate[] = [$this, 'validateSignInForm'];
        $form->onSuccess[] = [$this, 'loginFormSucceeded'];
        return $form;
    }

    public function validateSignInForm($form) {
        $values = $form->getValues();
        try {
            $this->getUser()->login($values->email, $values->password);
            $this->getUser()->setExpiration('1 days');
            $this->redirect('Homepage:');
        } catch (Nette\Security\AuthenticationException $e) {
            if ($e->getMessage() == 'User not found.') {
                $form['email']->addError('uživatel neexistuje');
            }
            elseif ($e->getMessage() == 'Invalid Password.') {
                 $form['password']->addError('neplatné heslo');
            }
        }
    }

    public function loginFormSucceeded(UI\Form $form) {


        $this->flashMessage('Byl jste úspěšně přihlášen.');
        $this->redirect('Homepage:default');
    }

//registration form
    protected function createComponentRegistrationForm() {
        $form = new UI\Form;
        $form->addProtection('Vypršel časový limit, odešlete formulář znovu');

        $form->addText('name', 'Jméno:')->setRequired("jmeno je volitelne ale dulezite");

        $form->addEmail('email', 'Email:')->setRequired("email je dulezity")->setHtmlAttribute('placeholder', 'user@example.com');

        $form->addPassword('password', 'Heslo:')->setRequired('Zvolte si heslo')->addRule(UI\Form::MIN_LENGTH, 'Heslo musí mít alespoň 3 znaky', 5);

        $form->addPassword('passwordVerify', 'Heslo pro kontrolu:')
                ->setRequired('Zadejte prosím heslo ještě jednou pro kontrolu')
                ->addRule(UI\Form::EQUAL, 'Hesla se neshodují', $form['password']);
        $form->addSelect('privilege', 'privilege', $this->database->table('privileges')->fetchPairs('Privilege'));

        $form->addSubmit('login', 'Registrovat');
        $form->onValidate[] = [$this, 'registrationFormValidate'];
        $form->onSuccess[] = [$this, 'registrationFormSucceeded'];
        return $form;
    }

    //registration
    public function registrationFormValidate(UI\Form $form) {
        $values = $form->getValues();
        $rows = 0;
        $rows = $this->database->table('users')->where('Email = ?', $values->email)->count('*');
        if ($rows > 0) {
            $form['email']->addError('tento email jiz existuje!');
        }
    }

    // volá se po úspěšném odeslání formuláře
    public function registrationFormSucceeded(UI\Form $form) {
        $values = $form->getValues();
        try {
            $this->database->table('users')->insert([
                'UserName' => $values->name,
                'Password' => password_hash($values->password, PASSWORD_DEFAULT),
                'Email' => htmlspecialchars($values->email),
                'UserPrivilege' => 'student',
            ]);
            $this->getUser()->login($values->email, $values->password);
            $this->getUser()->setExpiration('1 days');
            $this->flashMessage('Byl jste úspěšně registrován.');
            $this->redirect('Homepage:default');
        } catch (Nette\Database\ConnectionException $e) {
            
        }
    }

}
