<?php

namespace App\Presenters;

use Nette;
use Nette\Database;
use Nette\Application\UI;
use App\Presenters;
use Nette\Security\User;
use Nette\Database\Context;

class AddprojectPresenter extends Nette\Application\UI\Presenter {

    private $database;

    // pro práci s vrstvou Database Explorer si předáme Nette\Database\Context
    public function __construct(Nette\Database\Context $connection, Nette\Database\IStructure $structure, Nette\Database\IConventions $conventions = null, Nette\Caching\IStorage $cacheStorage = null) {
        $this->database = $connection;
    }

    public function renderAdd() {
        
    }

    protected function createComponentAddprojectForm() {
        $form = new Nette\Application\UI\Form;
        ;
        $form->addText('Name', 'Název projektu')->setRequired('Projekt musí mít název');
        $form->addText('consultant', 'konzultant')->setRequired('Projekt musí mít konzultanta');
        $form->addUpload('file', 'Vyber projekt')
                ->setRequired('Je potřeba přidat projekt!');
        $form->addSubmit('login', 'Přidat');
        $form->onValidate[] = [$this, 'validateProject'];
        $form->onSuccess[] = [$this, 'saveProject'];
        return $form;
    }

    public function validateProject(UI\Form $form) {
        $values = $form->getValues();
        $file = $values['file'];
        if ($file->getContentType() !== 'application/pdf') {
            $form['file']->addError('toto není pdf soubor');
        } else {
            
        }
    }

    public function saveProject(UI\Form $form) {
        
    }

}
