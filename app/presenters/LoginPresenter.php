<?php

namespace App\Presenters;

use Nette;
use Nette\Application\UI;
use App\Presenters;
use Nette\Security\User;


class LoginPresenter extends Nette\Application\UI\Presenter
{
    private $database;
    

    // pro práci s vrstvou Database Explorer si předáme Nette\Database\Context
    public function __construct(Nette\Database\Connection $database)
    {
        $this->database = $database;
        
    }
    protected function startup(){
        parent::startup();
        
        $user = $this->getUser();
  
        //$authenticate = \App\Model\MyAuthenticator::authenticate(['john','12345']);
    }
//    public function renderLogin(){
//        
//    }
    public function renderRegister(){
        $this->template->hash = "";
    }
    
    protected function createComponentLoginForm() {
        $form = new UI\Form;
        $form->getElementPrototype()->autocomplete = 'off';
        $form->addProtection('Vypršel časový limit, odešlete formulář znovu');
        $form->addEmail('email', 'Email:')->setRequired("email je dulezity");
        $form->addPassword('password', 'Heslo:')->setRequired('zadejte heslo');
        $form->addSubmit('login', 'Přihlásit');
        $form->onValidate[] = [$this, 'validateSignInForm'];
        $form->onSuccess[] = [$this,'loginFormSucceeded'];
        return $form;
    }

public function validateSignInForm($form)
{
    $values = $form->getValues();

    if ($values->password != "123456") { // validační podmínka
        $form->addError('Tato kombinace není možná.');
    }
}

public function loginFormSucceeded(UI\Form $form){
    
    
    $this->flashMessage('Byl jste úspěšně přihlášen.');
        $this->redirect('Homepage:default');
}





    protected function createComponentRegistrationForm()
    {
        $form = new UI\Form;
        $form->addProtection('Vypršel časový limit, odešlete formulář znovu');
        
        $form->addText('name', 'Jméno:')->setRequired("jmeno je volitelne ale dulezite");
        
        $form->addEmail('email', 'Email:')->setRequired("email je dulezity")->setHtmlAttribute('placeholder', 'user@example.com');
        
        $form->addPassword('password', 'Heslo:')->setRequired('Zvolte si heslo')->addRule(UI\Form::MIN_LENGTH, 'Heslo musí mít alespoň 3 znaky',5);
        
        $form->addPassword('passwordVerify', 'Heslo pro kontrolu:')
                ->setRequired('Zadejte prosím heslo ještě jednou pro kontrolu')
                ->addRule(UI\Form::EQUAL, 'Hesla se neshodují', $form['password']);
        
        $form->addSubmit('login', 'Registrovat');
        $form->onValidate[] = [$this, 'registrationFormValidate'];
        //$form->onSuccess[] = [$this, 'registrationFormSucceeded'];
        return $form;
    }
    
    public function registrationFormValidate(UI\Form $form){
        $rows = $this->database->fetchAll('SELECT * FROM users');
        foreach($rows as $row => $key){
            $this->template->hash = $key;
        }
    }

    // volá se po úspěšném odeslání formuláře
    public function registrationFormSucceeded(UI\Form $form)
    {
        $values = $form->getValues();
        try{
            $this->database->query('INSERT INTO users', [
            'UserName' => $values->name,
            'Password' => $values->password,
            'Email' => htmlspecialchars($values->email),
            'UserPrivilege' => '1',
        ]);
        $user->login($values->name,$values->password);
        $this->flashMessage('Byl jste úspěšně registrován.');
        $this->redirect('Homepage:default');
        }
        catch(Nette\Database\ConnectionException $e){
            
        }
    }
}
