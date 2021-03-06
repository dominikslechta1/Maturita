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
    private $project = null;

    // pro práci s vrstvou Database Explorer si předáme Nette\Database\Context
    public function __construct(Nette\Database\Context $connection, Nette\Database\IStructure $structure, Nette\Database\IConventions $conventions = null, Nette\Caching\IStorage $cacheStorage = null) {
        $this->database = $connection;
    }

    public function renderAdd($projectId = null) {
        if ($projectId !== null) {
            $this->project = $this->database->table('projects')->where('idProjects', $projectId)->fetch();
            $this->template->name = $this->project->Name;
        }
    }

    protected function createComponentAddprojectForm() {
        $form = new Nette\Application\UI\Form;
        if ($this->project !== null) {
            $form->addText('Name')->setRequired('Projekt musí mít název')->setValue($this->project->Name);
            $form->addTextArea('desc')->setValue($this->project->Desc);
            $form->addSelect('user', null, $this->database->table('users')->where('UserPrivilege', 3)->fetchPairs('idUsers', 'UserName'))->setDefaultValue($this->project->User)->setRequired('Projekt musí mít studenta');
            $form->addSelect('consultant', null, $this->database->table('users')
                    ->where('UserPrivilege', 2)
                    ->fetchPairs('idUsers', 'UserName'))
                    ->setDefaultValue($this->project->Consultant);
            $form->addSelect('oponent', null, $this->database->table('users')
                    ->where('UserPrivilege', 4)
                    ->fetchPairs('idUsers', 'UserName'))
                    ->setDefaultValue($this->project->Oponent);
            $form->addCheckbox('agree')->setValue($this->project->Public);
            $form->addHidden('preddefined', $this->project->idProjects);
        } else {
            $form->addText('Name')->setRequired('Projekt musí mít název');
            $form->addTextArea('desc');
            $form->addSelect('user', null, $this->database->table('users')->where('UserPrivilege', 3)->fetchPairs('idUsers', 'UserName'))->setRequired('Projekt musí mít studenta');
            $form->addSelect('consultant', null, $this->database->table('users')->where('UserPrivilege', 2)->fetchPairs('idUsers', 'UserName'))->setPrompt("");
            $form->addSelect('oponent', null, $this->database->table('users')->where('UserPrivilege', 4)->fetchPairs('idUsers', 'UserName'))->setPrompt("");
            $form->addCheckbox('agree');
            $form->addHidden('preddefined', 0);
        }
        $form->addSubmit('login');
        $form->onValidate[] = [$this, 'validateProject'];
        $form->onSuccess[] = [$this, 'saveProject'];
        return $form;
    }

    public function validateProject(UI\Form $form) {
        $values = $form->getValues();
        if ($values->desc > 256) {
            $form[desc]->addError('popis přesahuje maximální dovolený limit písmen');
        }
        $res = $this->database->table('projects')
                        ->where('Name', $values->Name)
                        ->where('Year', MyDateTime::getYear(\Nette\Utils\DateTime::from('0')))->count('*');
        if ($res > 0) {
            $form['Name']->addError('duplicitni nazev');
            $this->template->form = $form;
        }
    }

    public function saveProject(UI\Form $form) {
        $values = $form->getValues();
        if ($values->preddefined == 0) {
            try {
                $projectId = $this->database->table('projects')->insert([
                    'Name' => $values->Name,
                    'FileDir' => 'files',
                    'User' => $values->user,
                    'Consultant' => $values->consultant,
                    'Oponent' => $values->oponent,
                    'Year' => MyDateTime::getYear(\Nette\Utils\DateTime::from('0')),
                    'Public' => ($values->agree == true) ? 1 : 0,
                ]);
            } catch (mysqli_sql_exception $e) {
                $this->template->error = var_dump($e);
            }
        }
        else{
            try {
                $projectId = $this->database->table('projects')->where('idProjects',$values->preddefined)->update([
                    'Name' => $values->Name,
                    'FileDir' => 'files',
                    'User' => $values->user,
                    'Consultant' => $values->consultant,
                    'Oponent' => $values->oponent,
                    'Year' => MyDateTime::getYear(\Nette\Utils\DateTime::from('0')),
                    'Public' => ($values->agree == true) ? 1 : 0,
                ]);
            } catch (mysqli_sql_exception $e) {
                $this->template->error = var_dump($e);
            }
        }
        $this->redirect('Homepage:');
    }

//    public function validateProject(UI\Form $form) {
//        $values = $form->getValues();
//        $file = $values['file'];
//        if ($file->getContentType() !== 'application/pdf') {
//            $form['file']->addError('toto není pdf soubor');
//        }
//    }
//
//    public function saveProject(UI\Form $form) {
//        $values = $form->getValues();
//        if ($values->file->isOk()) {
//            //extension
//            $file_ext = strtolower(
//                    mb_substr(
//                            $values->file->getSanitizedName(), strrpos(
//                                    $values->file->getSanitizedName(), "."
//                            )
//                    )
//            );
//            //new name with rnd name
//            $file_name = uniqid(rand(0, 20), TRUE);
//            // move to save dir
//            $values->file->move('files/' . $file_name . $file_ext);
//            
//            
//            //projects insert
//            $projectId = $this->database->table('projects')->insert([
//                'Name' => $values->Name,
//                'FileDir' => 'files',
//                'User' => $this->getUser()->getId(),
//                'Consultant' => $values->consultant,
//                'Oponent' => $values->oponent,
//                'Year' => MyDateTime::getYear(\Nette\Utils\DateTime::from('0')),
//                'Public' => ($values->agree == true)? 1 : 0,
//            ]); 
//            //check for type
//            $n = [
//                'FileType' => $file_ext,
//                'Description' => ''
//            ];
//            $fileTypeId = $this->database->query('INSERT INTO filetypes ? ON DUPLICATE KEY UPDATE ?', $n,$n);
//            //file insert 
//            $this->database->table('files')->insert([
//                'FileName' => $file_name,
//                'Project' => $projectId,
//                'FileType' => $this->database->table('filetypes')->select('idFileTypes')->where('FileType', $file_ext)->fetchField()
//            ]);
//        }
//    }
}
