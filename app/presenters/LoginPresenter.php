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

    public function renderLogin() {
        
    }

    public function renderRegister() {
    }

    protected function createComponentLoginForm() {
        $form = new UI\Form;
        $form->getElementPrototype()->autocomplete = 'off';
        $form->addProtection('Vypršel časový limit, odešlete formulář znovu');
        $form->addEmail('email')->setRequired("email je dulezity");
        $form->addPassword('password')->setRequired('zadejte heslo');
        $form->addSubmit('login');
        $form->onValidate[] = [$this, 'validateLoginForm'];
        $form->onSuccess[] = [$this, 'loginFormSucceeded'];
        return $form;
    }

    public function validateLoginForm(UI\Form $form) {
        $values = $form->getValues();
        try {
            $this->getUser()->login($values->email, $values->password);
            $this->getUser()->setExpiration('1 days');
            $this->redirect('Homepage:');
        } catch (Nette\Security\AuthenticationException $e) {
            if ($e->getMessage() == 'User not found.') {
                $form['email']->addError('uživatel neexistuje');
            } elseif ($e->getMessage() == 'Invalid Password.') {
                $form['password']->addError('neplatné heslo');
            }
        }
    }

    public function loginFormSucceeded(UI\Form $form) {


        $this->flashMessage('Byl jste úspěšně přihlášen.');
        $this->redirect('Homepage:default');
    }

    
    
    //register part
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
                'UserPrivilege' => $values->privilege,
            ]);
            $this->flashMessage('Byl jste úspěšně registrován.');
            $this->redirect('Homepage:default');
        } catch (Nette\Database\ConnectionException $e) {
            
        }
    }

    function createComponentSignUpForm() {
        $form = new UI\Form;
        $form->addProtection('Vypršel časový limit, odešlete formulář znovu');

        $form->addText('name')->setRequired("jmeno je volitelne ale dulezite");

        $form->addEmail('email')->setRequired("email je dulezity")->setHtmlAttribute('placeholder', 'user@example.com');

        $form->addPassword('password')->setRequired('Zvolte si heslo')->addRule(UI\Form::MIN_LENGTH, 'Heslo musí mít alespoň 5 znaků', 5);

        $form->addPassword('passwordVerify')
                ->setRequired('Zadejte prosím heslo ještě jednou pro kontrolu')
                ->addRule(UI\Form::EQUAL, 'Hesla se neshodují', $form['password']);
        $form->addSelect('privilege', null, $this->database->table('privileges')->fetchPairs('idPrivileges', 'Privilege'));
        $form->addSubmit('login');
        $form->onValidate[] = [$this, 'registrationFormValidate'];
        $form->onSuccess[] = [$this, 'registrationFormSucceeded'];
        return $form;
    }

}
