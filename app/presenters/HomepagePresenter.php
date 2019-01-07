<?php

namespace App\Presenters;

use Nette;
use Nette\Security\User;
use App\Model\MyDateTime;
use Nette\Utils\DateTime;
use Nette\Application\UI\Form;

class HomepagePresenter extends Nette\Application\UI\Presenter {

    private $database;
    public $project;
    public $projectId = -1;

    public function __construct(Nette\Database\Context $connection, Nette\Database\IStructure $structure, Nette\Database\IConventions $conventions = null, Nette\Caching\IStorage $cacheStorage = null) {
        $this->database = $connection;
    }

    /**
     * show projects
     */
    public function renderDefault() {
        if ($this->getUser()->isInRole('administrator')) {
            $projects = $this->database->table('projects');
        } elseif ($this->getUser()->isInRole('student')) {
            $projects = $this->database->table('projects')
                    ->where('User', $this->getUser()->getId());
        } elseif ($this->getUser()->isInRole('consultant')) {
            $projects = $this->database->table('projects')
                    ->where('Consultant = ? AND Year = ?', $this->getUser()->getId(), MyDateTime::getYear(DateTime::from('0')));
        } elseif ($this->getUser()->isInRole('oponent')) {
            $projects = $this->database->table('projects')
                    ->where('Oponent = ? AND Year = ?', $this->getUser()->getId(), MyDateTime::getYear(DateTime::from('0')));
        } else {
            $projects = $this->database->table('projects')
                    ->where("Public", 1)
                    ->where(" Year", MyDateTime::getYear(DateTime::from(0)));
        }
        $projects = $projects->order('Year DESC')->fetchAll();
        if (sizeof($projects, 0) < 1) {
            $projects = null;
        }
        $this->template->projects = $projects;
    }

    /**
     * show one project selected by id
     * @param string $projectId
     */
    public function renderProject($projectId) {
        $project = $this->database->table('projects')->where('idProjects', $projectId)->fetch();
        if ($this->user->isInRole('administrator')) {
            $this->template->project = $project;
        } else if ($project->Public == 0) {
            if ($this->user->isLoggedIn() && ($this->user->getIdentity()->username == $project->ref('users', 'User')->UserName ||
                    $this->user->getIdentity()->username == $project->ref('users', 'Oponent')->UserName ||
                    $this->user->getIdentity()->username == $project->ref('users', 'Consultant')->UserName)) {
                $this->template->project = $project;
            } else {
                $this->error('Projekt je zamčen veřejnosti');
            }
        } else {
            $this->template->project = $project;
        }
        
        $this->project = $project;
        $this->projectId = $project->idProjects;

        //upload settings
        if (!isset($this->template->permissed)) {
            $this->template->permissed = "none";
        }
        if (!isset($this->template->upBtn)) {
            $this->template->upBtn = 'Přidat soubor';
        }
        if (!isset($this->template->upBtnState)) {
            $this->template->upBtnState = 'open';
        }
        if (!isset($this->template->btndis)) {
            $user = $this->user->roles[0];
            if ($user == 'administrator' ||
                    $user == 'oponent' ||
                    $user == 'consultant' ||
                    $user == 'student' && $this->project->Locked == 0) {
                $this->template->btndis = true;
            } else {
                $this->template->btndis = false;
            }
        }
    }

    public function handleDelete($id) {
        if ($this->user->isInRole('administrator')) {
            $this->database->table('files')->where('Project', $id)->delete();
            $this->database->table('projects')->where('idProjects', $id)->delete();
            $this->flashMessage('Záznam s id ' . $id . 'byl smazán');
            $this->redirect('Homepage:');
        } else {
            $this->flashMessage('Nemáš oprávnění smazat tento soubor', 'unsuccess');
        }
    }

    public function handlePubliced($id, $public) {
        $this->database->table('projects')
                ->where('idProjects', $id)
                ->update(['Public' => ($public == 1) ? '0' : '1']);
        if ($this->isAjax()) { // jde o ajax pozadavek?
            $this->redrawControl('Public'); // invalidujes dotycnej snippet
        } else {
            $this->redirect('this');
        }
    }

    public function handleLocked($id, $locked) {
        $this->database->table('projects')->where('idProjects', $id)->update(['Locked' => ($locked == 1) ? '0' : '1']);
        if ($this->isAjax()) { // jde o ajax pozadavek?
            $this->redrawControl('Locked'); // invalidujes dotycnej snippet
        } else {
            $this->redirect('this');
        }
    }

    public function handleUpdate($state) {
        $user = $this->user->roles[0];
        if ($user == 'administrator' || $user == 'oponent') {
            $this->uploadOpen($state);
        } elseif ($user == 'consultant') {
            $this->uploadOpen($state);
        } elseif ($user == 'student' && $this->project->Locked == 0) {
            $this->uploadOpen($state);
        } else {
            $this->flashMessage('Nemáš oprávnění přidávat soubory k tomuto projektu.', 'unsuccess');
        }
        
        $this->redrawControl('itemsContainer');
        $this->redrawControl('file');
        $this->redrawControl('scripts');
    }

    private function uploadOpen($state) {
        if ($state == "open") {
            $this->template->permissed = "unset";
            $this->template->upBtn = 'Zavřít';
            $this->template->upBtnState = 'close';
        } elseif ($state == "close") {
            $this->template->permissed = "none";
            $this->template->upBtn = 'Přidat soubor';
            $this->template->upBtnState = 'open';
        }
    }

    protected function createComponentUploadForm() {
        $form = new Form;
        $form->addUpload('file', null, false)
                ->addCondition(Form::FILLED)
                //->addRule(Form::MIME_TYPE, 'Povolené formáty jsou ZIP, RAR, PDF a TXT', 'application/x-rar, application/x-rar-compressed, application/rar, application/x-pdf, application/pdf, text/plain')
                ->setRequired('');
        $form->addTextArea('desc', null, null, 3);
        $form->addHidden('id', $this->projectId);
        $form->addSubmit('save');
        $form->onValidate[] = [$this, 'upValidate'];
        $form->onSuccess[] = [$this, 'upSuccess'];
        return $form;
    }

    public function upValidate(Form $form) {

        
        $values = $form->getValues();
        $file = $values['file'];
        $this->projectId = $values->id;
        if (!in_array($file->getContentType(), array('.pdf', '.rar', 'text/plain'))) {
            $form['file']->addError('soubor obsahuje neplatné přípony ' . $file->getContentType());
        }
        $this->uploadOpen('open');
        $this->redrawControl('itemsContainer');
        $this->redrawControl('file');
        $this->redrawControl('scripts');
    }

    public function upSuccess(Form $form) {
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
            //projects insert
            $projectId = $this->database->table('projects')
                    ->where('idProjects', $this->projectId)
                    ->update([
                'FileDir' => 'files'
            ]);
            //check for type
            $n = [
                'FileType' => $file_ext,
            ];
            $this->database->query('INSERT INTO filetypes ? ON DUPLICATE KEY UPDATE ?', $n, $n);

            //file insert 
            $this->database->table('files')->insert([
                'FileName' => $file_name,
                'Project' => $this->projectId,
                'FileType' => $this->database->table('filetypes')->select('idFileTypes')->where('FileType', $file_ext)->fetchField(),
                'Desc' => $values->desc
            ]);
            
            //after full success
            $this->uploadOpen('close');
        $this->redrawControl('itemsContainer');
        $this->redrawControl('file');
        $this->redrawControl('scripts');
        }
    }


}
