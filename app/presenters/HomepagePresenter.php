<?php

namespace App\Presenters;

use Nette;
use Nette\Security\User;
use App\Model\MyDateTime;
use Nette\Utils\DateTime;
use Nette\Application\UI\Form;
use Nette\Utils\FileSystem;

class HomepagePresenter extends BasePresenter {

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
        $files = $this->database->table('files')->where('Project', $projectId)->fetchAll();

        if ($files == null) {
            $files = null;
        }
        if ($this->user->isInRole('administrator')) {
            $this->template->files = $files;
            $this->template->project = $project;
        } else if ($project->Public == 0) {
            //if public 0 then check for right user
            if ($this->user->isLoggedIn() && ($this->user->getIdentity()->email == $project->ref('users', 'User')->Email ||
                    $this->user->getIdentity()->email == $project->ref('users', 'Oponent')->Email ||
                    $this->user->getIdentity()->email == $project->ref('users', 'Consultant')->Email)) {
                $this->template->files = $files;
                $this->template->project = $project;
            } else {
                $this->error('Projekt je zamčen veřejnosti');
            }
        } else {
            if ($this->user->isLoggedIn()) {
                try{
                if ($this->user->getIdentity()->email == $project->ref('users', 'User')->Email ||
                        $this->user->getIdentity()->email == $project->ref('users', 'Oponent')->Email ||
                        $this->user->getIdentity()->email == $project->ref('users', 'Consultant')->Email) {
                    $this->template->files = $files;
                }
                } catch (Exception $e){
                    
                }
            }
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

    public function handleUpdate($state, $project) {
        $user = $this->user->roles[0];
        if ($user == 'administrator' || $user == 'oponent') {
            $this->uploadOpen($state);
        } elseif ($user == 'consultant') {
            $this->uploadOpen($state);
        } elseif ($user == 'student' && $project == 0) {
            $this->uploadOpen($state);
        } else {
            $this->flashMessage('Nemáš oprávnění přidávat soubory k tomuto projektu.', 'unsuccess');
        }

        $this->redrawControl('itemsContainer');
        $this->redrawControl('file');
        $this->redrawControl('scripts');
    }

//deletes file from database and server folder
    public function handleDeleteFile($fileId) {
        $user = $this->user->roles[0];
        if ($user == 'administrator') {

            $file = $this->database->table('files')->where('idFiles', $fileId)->fetch();

            $fileFullName = $file->FileName . $file->ref('filetypes', 'FileType')->FileType;
            try {

                FileSystem::delete(__DIR__ . '\\..\\..\\www\\files\\' . $fileFullName);
            } catch (Exception $ex) {
                
            }
            $this->database->table('files')->where('idFiles', $fileId)->delete();
            $this->flashMessage('Úspěšně smazáno', 'success');
            $this->redirect('this');
        } else {
            $this->flashMessage('Nemáš oprávnění smazat tento soubor', 'unsuccess');
        }
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
        //extension get
        $acex = $this->acceptedExtension();
        $acexstring = implode(",", $acex);
        $form = new Form;
        $form->addUpload('file', null, false)
                ->setAttribute('accept', $acexstring)
                ->addCondition(Form::FILLED)
                //->addRule(Form::MIME_TYPE, 'Povolené formáty jsou ZIP, RAR, PDF a TXT', 'text/plain')
                ->setRequired('');
        $form->addText('name', null, null, 80)->setRequired('Soubor musí mít název');
        $form->addTextArea('desc', null, null, 3);
        $form->addHidden('id', $this->projectId);
        $form->addSubmit('save');
        $form->onValidate[] = [$this, 'upValidate'];
        $form->onSuccess[] = [$this, 'upSuccess'];
        return $form;
    }

    public function upValidate(Form $form) {


        $values = $form->getValues();
        if ($values->file->isOk()) {
            $file_ext = strtolower(
                    mb_substr(
                            $values->file->getSanitizedName(), strrpos(
                                    $values->file->getSanitizedName(), "."
                            )
                    )
            );
        }
        $file = $values['file'];
        $this->projectId = $values->id;
        $field = $this->acceptedExtension();
        if (!in_array($file_ext, $field)) {
//array('.pdf', '.rar', '.txt', '.zip')
            $form['file']->addError('Soubor obsahuje neplatné přípony ' . $file_ext);
            $this->flashMessage('Soubor obsahuje neplatné přípony ', 'unsuccess');
        }
        if ($values->name == '') {
            $form['name']->addError('Musí mít název');
            $this->flashMessage('Název', 'unsuccess');
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
            $this->database->table('projects')
                    ->where('idProjects', $this->projectId)
                    ->update([
                        'FileDir' => 'files'
            ]);

            //file insert 
            $uploaded = $this->database->table('files')->insert([
                'FileName' => $file_name,
                'Project' => $this->projectId,
                'FileType' => $this->database->table('filetypes')->select('idFileTypes')->where('FileType', $file_ext)->fetchField(),
                'Desc' => $values->desc,
                'Name' => $values->name
            ]);
            if ($uploaded == '') {
                $this->flashMessage('uspesne ulozeno ', 'success');
                $this->redirect('this');
            } else {

                //after full success
                $this->flashMessage('neuspesne ulozeno ', 'unsuccess');
                $this->redirect('this');
            }
        }
    }

    
    
    //url upload function form
    protected function createComponentUrlForm() {
        $form = new Form;
        $form->addHidden('id', $this->projectId);
        $form->addText('url');
        $form->addSubmit('upload');
        $form->onValidate[] = [$this, 'validateUrl'];
        $form->onSuccess[] = [$this, 'sendUrl'];
        return $form;
    }
    
    public function validateUrl(Form $form){
        $values = $form->getValues();
        if(strpos($values->url, 'http://') !== false || strpos($values->url, 'https://') !== false){
            
        }else{
            $form['url']->addError('nespravna url');
            $this->flashMessage('nespravna url ' , 'unsuccess');
            $this->redrawControl('flash');
        }
    }
    
    public function sendUrl(Form $form){
        $values = $form->getValues();
        
    $this->database->table('projects')
            ->where('idProjects', $values->id)
            ->update([
        'Url' => $values->url
    ]);
    $this->redrawControl('filesUp');
    $this->flashMessage('url ulozena','success');
    }
    
    
    
    
    //function extension get to get accepted file extension for upload
    public function acceptedExtension() {
        $n = $this->database->table('filetypes')->fetchAll();
        $field = array();
        foreach ($n as $id => $item) {
            array_push($field, $item->FileType);
        }
        return $field;
    }

}
