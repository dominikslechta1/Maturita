<?php

namespace App\Presenters;

use Nette;
use Nette\Security\User;
use App\Model\MyDateTime;
use Nette\Utils\DateTime;
use Nette\Database;
use Nette\Application\UI;
use App\Presenters;
use Nette\Database\Context;
use Nette\Security\Passwords;
use Latte\Engine;

class UserpagePresenter extends Nette\Application\UI\Presenter {

    private $database;

    public function __construct(Nette\Database\Context $connection, Nette\Database\IStructure $structure, Nette\Database\IConventions $conventions = null, Nette\Caching\IStorage $cacheStorage = null) {
        $this->database = $connection;
    }

    public function renderUserpage() {
        
    }

    public function renderChangepass() {
        
    }

    protected function createComponentChangePasswordForm() {
        $form = new UI\Form;
        $form->addProtection('Vypršel časový limit, odešlete formulář znovu');
        $form->addPassword('old')->setRequired('Zadej své staré heslo');
        $form->addPassword('new')->setRequired('Zvolte nové heslo');
        $form->addPassword('repeat')->setRequired('Opakuj heslo');
        $form->addSubmit('send');
        $form->onValidate[] = [$this, 'PasswordValidate'];
        $form->onSuccess[] = [$this, 'PasswordResetSuccess'];
        return $form;
    }

    public function PasswordValidate(UI\Form $form) {
        $values = $form->getValues();
        if (!password_verify($values->old, $this->database->table('users')->where('idUsers', $this->getUser()->getId())->fetchField('Password'))) {
            $form['old']->addError('Nesprávné staré heslo');
        } else {
            $form['old']->cleanErrors();
        }
        if ($values->new !== $values->repeat) {
            $form['repeat']->addError('Hesla se neshodují!');
        } else {
            $form['repeat']->cleanErrors();
        }
        if (mb_strlen($values->new) < 5) {
            $form['new']->addError('Krátké heslo!');
        } else {
            $form['new']->cleanErrors();
        }
        if ($values->old == $values->new) {
            $form['new']->addError('Heslo nesmí být stejné jako minulé');
        } else {
            $form['new']->cleanErrors();
        }
    }

    public function PasswordResetSuccess(UI\Form $form) {
        $values = $form->getValues();

        $this->database->table('users')->where('idUsers', $this->getUser()->getId())->update([
            'Password' => password_hash($values->new, PASSWORD_DEFAULT)
        ]);
        $this->redirect('Homepage:default');
    }

}
