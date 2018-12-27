<?php

namespace App\Presenters;

use Nette\Database;
use Nette;
use Nette\Application\UI;
use App\Presenters;
use Nette\Security\User;
use Nette\Database\Context;
use Nette\Security\Passwords;
use Latte\Engine;
use App\Model\MyDateTime;
use Nette\Utils\DateTime;
use Nette\Bridges\ApplicationLatte\UIMacros;
use App\Forms;
use Nette\Mail\Message;
use Nette\Mail\SendmailMailer;
use Nette\Mail\SmtpMailer;
use Nette\Application\UI\Presenter;

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
    
    public function renderRecoverpassword($username = null,$tokken = null){
        $user = $this->database->table('users')->where('TempToc', $tokken)->fetch();
        if($user !== ''){
            $now = new DateTime();
            if($now > $user->TocDate){
                //do new password
            }
        }
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

    protected function createComponentForgetPassword() {
        $form = new UI\Form;
        $form->addProtection('Vypršel časový limit, odešlete formulář znovu');
        $form->addEmail('email')->setRequired('toto je povinné');
        $form->addSubmit('send');
        $form->onSubmit[] = [$this, 'validateForget'];
        $form->onSuccess[] = [$this, 'successForget'];
        return $form;
    }

    public function validateForget(UI\Form $form) {
        $values = $form->getValues();
        if (!mb_strpos($values->email, '@')) {
            $form['email']->addError('toto neni email');
        }
    }

    public function successForget(UI\Form $form) {
        $values = $form->getValues();

        $this->template->success = "db";
        $forgetDb = $this->database
                ->table('users')
                ->where('Email', $values->email)
                ->limit(1)
                ->fetchField('UserName');
        $this->template->success = "db";
        if ($forgetDb !== '') {
            try{
            $now = new DateTime();
            $hash = Passwords::hash($now . $values->email);
            $this->database->table('users')->where('Email', $values->email)->update([
                'TempToc' => $hash,
                'TocDate' => new DateTime('+1 hour')
            ]);
            $message = new Message();
            $uri = $this->getHttpRequest()->getUrl();
            $params = [
                'username' => $forgetDb,
                'token' => $hash,
                'sitename' => $uri->host,
            ];
            $latte = new Engine();
            $latte->addProvider("uiControl", $this);
            $latte->addProvider("uiPresenter", $this);
            UIMacros::install($latte->getCompiler());
            $message->setFrom('noreply@Maturitniprojekty.com')
                ->addTo($values->email)
                ->setSubject('Zapomenuté heslo')
                ->setHtmlBody($latte->renderToString(__DIR__ . '/templates/rememberMail.latte', $params,null));
            
            $mailer = $this->setMailer(2);
            $mailer->send($message);
            $this->template->success = "uspech";
            }
            catch(Exception $e){
                $this->template->success = "neuspech";
            }
        }
        else{
            $this->template->success = "neuspech";
        }
    }
    
    /**
     * Method setMailer
     *
     * @param int $useSendmail
     *
     * @return \Nette\Mail\SendmailMailer|\Nette\Mail\SmtpMailer
     */
    protected function setMailer($useSendmail = 1)
    {
        if (1 === $useSendmail) {
            /** @var SendmailMailer mailer */
            $mailer = new SendmailMailer();
        } else {
            /** @var SmtpMailer mailer */
            $mailer = new SmtpMailer(
                [
                    'host' => 'smtp.gmail.com',
                    'username' => 'jezancz.22@gmail.com',
                    'password' => 'Jezula248',
                    'secure' => 'ssl',
                    'port' => 465,
                ]
            );
        }
 
        return $mailer;
    }

    
    
}
