<?php

namespace App\Presenters;

use Nette;
use Nette\Database;
use Nette\Application\UI;
use App\Presenters;
use Nette\Security\User;
use Nette\Database\Context;
use Nette\Utils\FileSystem;
use App\Model\MyDateTime;

class UserpagePresenter extends Nette\Application\UI\Presenter {

    public function renderUserpage() {
        
    }

    protected function createComponentNewpasswordForm() {
        $form = new Nette\Application\UI\Form;
        $form->addPassword('old');
        $form->addPassword('new')->setRequired('Zvolte nové heslo')->addRule(UI\Form::MIN_LENGTH, 'Heslo musí mít alespoň 5 znaků', 5);
        $form->addPassword('repeat')
                ->setRequired('Zadejte prosím heslo ještě jednou pro kontrolu')
                ->addRule(UI\Form::EQUAL, 'Hesla se neshodují', $form['repeat']);
        $form->addSubmit('send');

        $form->onSuccess[] = [$this, 'PasswordResetSuccess'];
        return $form;
    }

    private function PasswordResetSuccess(UI\Form $form) {
        
    }

}
