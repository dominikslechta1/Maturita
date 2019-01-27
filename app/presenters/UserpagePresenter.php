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

class UserpagePresenter extends BasePresenter {

    private $database;
    private $usera;

    public function __construct(Nette\Database\Context $connection, Nette\Database\IStructure $structure, Nette\Database\IConventions $conventions = null, Nette\Caching\IStorage $cacheStorage = null) {
        $this->database = $connection;
    }

    public function renderUserpage() {
        
    }

    public function renderChangepass() {
        
    }

    public function renderUsero($id = null) {
        if (BasePresenter::isQueryunSec(array($id))) {
            $this->error("neplatné znaky v url", "405");
        }
        $usero = $this->database->table('users')->where('idUsers', $id)->order('UserPrivilege', "ASC")->fetch();
        if (sizeof($usero, 0) < 1) {
            $usero = null;
        }

        $this->template->usera = $usero;
    }

    public function renderUseredit($id = null) {
        if (BasePresenter::isQueryunSec(array($id))) {
            $this->error("neplatné znaky v url", "405");
        } else {
            $usero = $this->database->table('users')->where('idUsers', $id)->fetch();
            if (sizeof($usero, 0) < 1) {
                $usero = null;
            }
            $this->usera = $usero;
            $this->template->usera = $usero;
        }
    }

    public function handleDelete($id) {
        if (BasePresenter::isQueryunSec(array($id))) {
            $this->error("neplatné znaky v url", "405");
        }
        if ($this->user->isInRole('administrator') && $this->user->getIdentity()->getId() != $id) {
            $v = $this->database->table('files')->where('Project', [
                                $this->database->table('projects')
                                ->where('User =? OR Oponent = ? OR Consultant = ?', $id, $id, $id)
                                ->select('idProjects')
                    ])->delete();

            $this->database->table('projects')
                    ->where('User =? OR Oponent = ? OR Consultant = ?', $id, $id, $id)
                    ->delete();
            $this->database->table('users')->where('idUsers', $id)->delete();
            $this->flashMessage($v . ' Uživatel s id ' . $id . 'byl smazán', "success");
            $this->redirect('Admin:users');
        } else {
            $this->flashMessage('Nemáš oprávnění smazat tento soubor', 'unsuccess');
        }
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

    protected function createComponentEditUserForm() {
        $form = new UI\Form;
        $a = $this->usera;
        $form->addProtection('Vypršel časový limit, odešlete formulář znovu');
        if($a !== null){
        
        $form->addText('name')->setRequired()->setValue($a->UserName);
        $form->addEmail('email')->setRequired()->setValue($a->Email);
        $form->addHidden('preddefined')->setValue($a->idUsers);
        }else{
        $form->addText('name')->setRequired();
        $form->addEmail('email')->setRequired();
        $form->addHidden('preddefined'); 
        }
        $form->addSubmit('edit');
        $form->onValidate[] = [$this, 'editValidate'];
        $form->onSuccess[] = [$this, 'editSuccess'];
        return $form;
    }

    public function editValidate(UI\Form $form) {
        $values = $form->getValues();
        $esc = $this->database->table('users')->where('Email = ? AND idUsers != ?',$values->email,$values->preddefined)->count();
        if($esc > 0){
            $form['email']->addError('Tento email je již v databázi');
        }
    }

    public function editSuccess(UI\Form $form) {
        $values = $form->getValues();
        if (BasePresenter::isQueryunsec(array($values->preddefined))) {
            $this->error(null, "405");
        } else {
            if (BasePresenter::isQueryunsec($values)) {
                $this->error(null, "405");
            }
            $this->database->table('users')->where('idUsers', $values->preddefined)->update([
                'UserName' => $values->name,
                'Email' => $values->email
            ]);
            $this->flashMessage('Uživatel byl upraven','success');
            $this->redirect('Admin:users');
        }
    }

}
