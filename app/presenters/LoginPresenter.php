<?php

namespace App\Presenters;

use Nette;
use Nette\Application\UI;
use Nette\Security\Passwords;
use Latte\Engine;
use Nette\Utils\DateTime;
use Nette\Bridges\ApplicationLatte\UIMacros;
use Nette\Mail\Message;
use Nette\Mail\SendmailMailer;
use Nette\Mail\SmtpMailer;
use App\Model;

class LoginPresenter extends Nette\Application\UI\Presenter {

    /** @var Model\SignNewPassFormFactory @inject */
    public $signNewPassFactory;
    private $database;
    
    private $mailer;

    // pro práci s vrstvou Database Explorer si předáme Nette\Database\Context
    public function __construct(\Nette\Mail\IMailer $mailer, Nette\Database\Context $connection, Nette\Database\IStructure $structure, Nette\Database\IConventions $conventions = null, Nette\Caching\IStorage $cacheStorage = null) {
        $this->database = $connection;
        $this->mailer = $mailer;
    }

    public function renderLogin() {
        
    }

    public function renderRegister() {
        
    }

    public function renderRecoverpassword($username = null, $token = null) {
        $user = $this->database->table('users')->where('TempToc', $token)->fetch();
        if ($user !== '') {
            $now = new DateTime();
            if ($now < $user->TocDate) {
                /** @var Form $form */
                $form = $this['newPassForm'];
                if (!$form->isSubmitted()) {
                    $row = [
                        'username' => $username,
                        'hash' => $token,
                    ];
                    $form->setDefaults($row);
                }
            }
        }
    }
    
    /**
     * Method createComponentNewPassForm
     *
     * @return \Nette\Application\UI\Form
     */
    protected function createComponentNewPassForm()
    {
        $form = $this->signNewPassFactory->create();
 
        $form->onSuccess[] = function (UI\Form $form) {
            $values = $form->getValues();
            $this->database->table('users')->where('TempToc',$values->hash)->update([
                'Password' => password_hash($values->password,PASSWORD_DEFAULT),
                'TempToc' => '',
                'TocDate' => NULL
            ]);
            
            $this->flashMessage('Heslo bylo změněno', 'success');
            $this->redirect('Login:login');
        };
 
        return $form;
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
        $forgetDb = $this->database
                ->table('users')
                ->where('Email', $values->email)
                ->limit(1)
                ->fetchField('UserName');
        if ($forgetDb !== '') {
            try {
                $now = new DateTime();
                $hash = Passwords::hash($now . $values->email);
                $userexist = $this->database->table('users')->where('Email', $values->email)->update([
                    'TempToc' => $hash,
                    'TocDate' => new DateTime('+1 hour')
                ]);
                if ($userexist == '') {
                    $this->alert('uživatel neexistuje');
                    return;
                }
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
                        ->setHtmlBody($latte->renderToString(__DIR__ . '/templates/rememberMail.latte', $params, null));

                $mailer = $this->mailer;
                $mailer->send($message);
                $this->alert('Podívej se do emailu.');
            } catch (Exception $e) {
                $this->alert('Někde nastala chyba!');
            }
        } else {
            $this->alert('Uživatel není v databázi!');
        }
    }

    /**
     * Method setMailer
     *
     * @param int $useSendmail
     *
     * @return \Nette\Mail\SendmailMailer|\Nette\Mail\SmtpMailer
     */
//    protected function setMailer($useSendmail = 1) {
//        if (1 === $useSendmail) {
//            /** @var SendmailMailer mailer */
//            $mailer = new SendmailMailer();
//        } else {
//            /** @var SmtpMailer mailer */
//            $mailer = $this->mailer;
//        }
//
//        return $mailer;
//    }

    public function alert($msg) {
        $this->template->alert = htmlspecialchars($msg);
    }

}
