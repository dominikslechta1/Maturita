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
        $form->addText('Name', 'Název projektu: ')->setRequired('Projekt musí mít název');
        $form->addText('consultant', 'konzultant: ')->setRequired('Projekt musí mít konzultanta');
        $form->addText('oponent', 'oponent: ')->setRequired('Projekt musí mít oponent');
        $form->addCheckbox('publicChbx','veřejné: ');
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
        }
    }

    public function saveProject(UI\Form $form) {
        $values = $form->getValues();
        if ($values->file->isOk()) {
            //extension
            $file_ext = strtolower(
                    mb_substr(
                            $values->file->getSanitizedName(), strrpos(
                                    $values->file->getSanitizedName(), "."
                            )
                    )
            );
            //new name with rnd name
            $file_name = uniqid(rand(0, 20), TRUE);
            // move to save dir
            $values->file->move('files/' . $file_name . $file_ext);
            $this->database->table('projects')->insert([
                'Name' => $values->Name,
                'FileDir' => 'files',
                'User' => $this->getUser()->getId(),
                'Consultant' => $values->consultant,
                'Oponent' => $values->oponent,
                'Year' => MyDateTime::getYear(\Nette\Utils\DateTime::from('0')),
                'Public' => ($values->publicChbx == true)? 1 : 0,
            ]); 
        }
    }

}
