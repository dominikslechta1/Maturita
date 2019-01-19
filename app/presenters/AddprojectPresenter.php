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

class AddprojectPresenter extends BasePresenter {

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
            //nazev
            $form->addText('Name')
                    ->setRequired('Projekt musí mít název')
                    ->setValue($this->project->Name);
            
            //popis
            $form->addTextArea('desc')->setValue($this->project->Desc);
            
            //student
            $form->addSelect('user', null, $this->database->table('users')->where('UserPrivilege', 3)->fetchPairs('idUsers', 'UserName'))
                    ->setDefaultValue($this->project->User)
                    ->setRequired('Projekt musí mít studenta')
                    ->setPrompt('');
            
            //consultant
            $form->addSelect('consultant', null, $this->database->table('users')->where('UserPrivilege', 2)->fetchPairs('idUsers', 'UserName'))
                    ->setOption('class', 'disabled')
                    ->setDefaultValue($this->project->Consultant)
                    ->setPrompt('');
            
            //oponent
            $form->addSelect('oponent', null, ($this->database->table('users')->where('UserPrivilege', 4)->fetchPairs('idUsers', 'UserName')))
                    ->setDefaultValue($this->project->Oponent)
                    ->setPrompt('');
            
            //verejne
            $form->addCheckbox('agree')->setValue($this->project->Public);
            
            //pokud uprava pak id
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
            $this->flashMessage('Písmena','success');
            $form['desc']->addError('popis přesahuje maximální dovolený limit písmen');
            
        }
        $res = $this->database->table('projects')
                        ->where('Name', $values->Name)
                        ->where('idProjects != ?', $values->preddefined)
                        ->where('Year', MyDateTime::getYear(\Nette\Utils\DateTime::from('0')))->count('*');
        if ($res > 0 && $values->preddefined == 0) {
            $this->flashMessage('duplicita','success');
            $form['Name']->addError('duplicitni nazev');
            
        }
    }

    public function saveProject(UI\Form $form) {
        $values = $form->getValues();
        if ($values->preddefined == 0 && $this->user->isInRole('administrator')) {
                $this->database->table('projects')->insert([
                    'Name' => $values->Name,
                    'FileDir' => 'files',
                    'User' => $values->user,
                    'Consultant' => $values->consultant,
                    'Oponent' => $values->oponent,
                    'Year' => MyDateTime::getYear(\Nette\Utils\DateTime::from('0')),
                    'Public' => ($values->agree == true) ? 1 : 0,
                    'Desc' => $values->desc,
                ]);
        }
        elseif($this->user->isInRole('administrator')){
            $this->database->table('projects')->where('idProjects',$values->preddefined)->update([
                    'Name' => $values->Name,
                    'FileDir' => 'files',
                    'User' => $values->user,
                    'Consultant' => $values->consultant,
                    'Oponent' => $values->oponent,
                    'Year' => MyDateTime::getYear(\Nette\Utils\DateTime::from('0')),
                    'Public' => ($values->agree == true) ? 1 : 0,
                    'Desc' => $values->desc,
                ]);
        }
        else{
                $this->database->table('projects')->where('idProjects',$values->preddefined)->update([
                    'Desc' => $values->desc,
                ]);
        }
        $this->flashMessage('Projekt uložen','success');
        $this->redirect('Homepage:');
        
    }
}
