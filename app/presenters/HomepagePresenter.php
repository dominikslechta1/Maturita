<?php

namespace App\Presenters;

use Nette;
use Nette\Security\User;
use App\Model\MyDateTime;
use Nette\Utils\DateTime;
use Nette\Application\UI\Form;
use Nette\Utils\FileSystem;
use Tracy\Debugger;

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
    public function renderDefault($Year = 1) {

        if ($Year != '1') {
            $this->template->curyear = $Year;


            if ($this->getUser()->isInRole('administrator')) {
                $projects = $this->database->table('projects')->where('Year', $Year);
            } elseif ($this->getUser()->isInRole('student')) {
                $projects = $this->database->table('projects')
                        ->where('User = ?, Year = ?', $this->getUser()->getId(), $Year);
            } elseif ($this->getUser()->isInRole('consultant')) {
                $projects = $this->database->table('projects')
                        ->where('Consultant = ? AND Year = ?', $this->getUser()->getId(), $Year);
            } elseif ($this->getUser()->isInRole('oponent')) {
                $projects = $this->database->table('projects')
                        ->where('Oponent = ? AND Year = ?', $this->getUser()->getId(), $Year);
            } else {
                $projects = $this->database->table('projects')
                        ->where("Public", 1)
                        ->where(" Year", $Year);
            }
        } else {
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
        }
        $projects = $projects->order('Year DESC')->fetchAll();
        if (sizeof($projects, 0) < 1) {
            $projects = null;
        }
        $this->template->projects = $projects;
        $Years = $this->database->table('projects')->order('Year DESC')->select('DISTINCT Year')->fetchAll();
        $this->template->Years = $Years;
    }

    /**
     * show one project selected by id
     * @param string $projectId
     */
    public function renderProject($projectId) {
        $project = $this->database->table('projects')->where('idProjects', $projectId)->fetch();
        if ($project->RqFile == null && $project->RqFilePdf != null) {
            $files = $this->database->table('files')->where('Project', $project->idProjects)->where('idFiles != ?', $project->RqFilePdf)->fetchAll();
        } elseif ($project->RqFile != null && $project->RqFilePdf == null) {
            $files = $this->database->table('files')->where('Project', $project->idProjects)->where('idFiles != ?', $project->RqFile)->fetchAll();
        } elseif ($project->RqFile == null && $project->RqFilePdf == null) {
            $files = $this->database->table('files')->where('Project', $project->idProjects)->fetchAll();
        } else {
            $files = $this->database->table('files')->where('Project', $project->idProjects)->where('idFiles != ?', $project->RqFile)->where('idFiles != ?', $project->RqFilePdf)->fetchAll();
        }


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
                try {
                    if ($this->user->getIdentity()->email == $project->ref('users', 'User')->Email ||
                            $this->user->getIdentity()->email == $project->ref('users', 'Oponent')->Email ||
                            $this->user->getIdentity()->email == $project->ref('users', 'Consultant')->Email) {
                        $this->template->files = $files;
                    }
                } catch (Exception $e) {
                    
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



        //req files
        $reqfile = $this->database->table('projects')->where('idProjects', $projectId)->fetchField('RqFile');
        $reqfilepdf = $this->database->table('projects')->where('idProjects', $projectId)->fetchField('RqFilePdf');

        if ($reqfile != null) {
            $this->template->reqshow = true;
            $this->template->reqfile = $this->database->table('files')->where('idFiles', $reqfile)->fetch();
        } else {
            $this->template->reqshow = false;
        }
        if ($reqfilepdf != null) {
            $this->template->reqshowpdf = true;
            $this->template->reqfilepdf = $this->database->table('files')->where('idFiles', $reqfilepdf)->fetch();
        } else {
            $this->template->reqshowpdf = false;
        }
    }

    public function handleSortNews($id) {
        $this->redirect('this', array("Year" => ($id == 'all') ? '1' : $id));
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
    public function handleDeleteFile($fileId = -1) {
        $fileId = $this->database->table('files')->where('idFiles', $fileId)->fetchField('idFiles');
        if ($fileId == -1 || $fileId == null) {
            $this->flashMessage('momentálně není tato akce k dispozici', 'unsuccess');
            return;
        }
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
                ->setRequired()
                ->addCondition(Form::FILLED);
        //->addRule(Form::MIME_TYPE, 'Povolené formáty jsou ZIP, RAR, PDF a TXT', 'text/plain')
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
            if ($uploaded != '') {
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

    public function validateUrl(Form $form) {
        $values = $form->getValues();
        if (strpos($values->url, 'http://') !== false || strpos($values->url, 'https://') !== false) {
            
        } else {
            $form['url']->addError('nespravna url');
            $this->flashMessage('nespravna url ', 'unsuccess');
            $this->redrawControl('flash');
        }
    }

    public function sendUrl(Form $form) {
        $values = $form->getValues();

        $this->database->table('projects')
                ->where('idProjects', $values->id)
                ->update([
                    'Url' => $values->url
        ]);
        $this->redrawControl('filesUp');
        $this->flashMessage('url ulozena', 'success');
    }

    //function delete url
    public function handleDeleteUrl($id = -1) {
        $projectid = $this->database->table('projects')->where('idProjects', $id)->fetchField('idProjects');
        if ($projectid < 1) {
            $this->flashMessage('spatne id ' . $id, 'unsuccess');
            return;
        }
        $n = $this->database->table('projects')->where('idProjects', $projectid)->update([
            'Url' => null
        ]);
        if ($n > 0) {
            $this->flashMessage('url úspěšně smazána', 'success');
        } else {
            $this->flashMessage('url nebyla smazána ' . $this->projectId, 'unsuccess');
            return;
        }
    }

    protected function createComponentReqFilesPdf() {
        //extension get
        $acexstring = ".pdf";
        $form = new Form;
//        $form->addUpload('file', null, false)
//                ->setAttribute('accept', $acexstring)
//                ->addCondition(Form::FILLED);
        $form->addUpload('filepdf', null, false)
                ->setAttribute('accept', $acexstring)
                ->addCondition(Form::FILLED);
        $form->addHidden('id', $this->projectId);
        $form->addSubmit('save');
        $form->onValidate[] = [$this, 'reqValidatePdf'];
        $form->onSuccess[] = [$this, 'reqSuccessPdf'];
        return $form;
    }

    public function reqValidatePdf(Form $form) {


        $values = $form->getValues();
//        if ($values->file->isOk()) {
//            $file_ext = strtolower(
//                    mb_substr(
//                            $values->file->getSanitizedName(), strrpos(
//                                    $values->file->getSanitizedName(), "."
//                            )
//                    )
//            );
//        }
        if ($values->filepdf->isOk()) {
            $file_ext = strtolower(
                    mb_substr(
                            $values->filepdf->getSanitizedName(), strrpos(
                                    $values->filepdf->getSanitizedName(), "."
                            )
                    )
            );
        }
        $this->projectId = $values->id;
        $field = $this->acceptedExtension();
//        if (isset($values->file) && !in_array($file_ext, $field)) {
////array('.pdf', '.rar', '.txt', '.zip')
//            $form['file']->addError('Soubor obsahuje neplatné přípony ' . $file_ext);
//            $this->flashMessage('Soubor obsahuje neplatné přípony ', 'unsuccess');
//        }
        if (isset($values->filepdf) && !in_array($file_ext, $field)) {
//array('.pdf', '.rar', '.txt', '.zip')
            $form['file']->addError('Soubor obsahuje neplatné přípony ' . $file_ext);
            $this->flashMessage('Soubor obsahuje neplatné přípony ', 'unsuccess');
        }
        $this->redrawControl('reqfile');
    }

    public function reqSuccessPdf(Form $form) {
        $values = $form->getValues();
        if ($values->filepdf->isOk()) {
            //extension
            $file_ext = strtolower(
                    mb_substr(
                            $values->filepdf->getSanitizedName(), strrpos(
                                    $values->filepdf->getSanitizedName(), "."
                            )
                    )
            );
            //new name with rnd name
            $file_name = uniqid(rand(0, 20), TRUE);
            // move to save dir
            $values->filepdf->move('files/' . $file_name . $file_ext);
            //projects insert
            //file insert 
            $uploaded = $this->database->table('files')->insert([
                'FileName' => $file_name,
                'Project' => $this->projectId,
                'FileType' => $this->database->table('filetypes')->select('idFileTypes')->where('FileType', $file_ext)->fetchField(),
                'Desc' => "",
                'Name' => ""
            ]);
            $this->database->table('projects')
                    ->where('idProjects', $this->projectId)
                    ->update([
                        'FileDir' => 'files',
                        'RqFilePdf' => $uploaded->idFiles
            ]);
            if ($uploaded != null) {
                $this->flashMessage('uspesne ulozeno ', 'success');
                $this->redirect('this');
            } else {

                //after full success
                $this->flashMessage('neuspesne ulozeno ', 'unsuccess');
                $this->redirect('this');
            }
        }
    }

    protected function createComponentReqFiles() {
        //extension get
        $acex = $this->acceptedExtension();
        $acexstring = implode(",", $acex);
        $form = new Form;
        $form->addUpload('file', null, false)
                ->setAttribute('accept', $acexstring)
                ->addCondition(Form::FILLED);

        $form->addHidden('id', $this->projectId);
        $form->addSubmit('save');
        $form->onValidate[] = [$this, 'reqValidate'];
        $form->onSuccess[] = [$this, 'reqSuccess'];
        return $form;
    }

    public function reqValidate(Form $form) {


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
        $this->projectId = $values->id;
        $field = $this->acceptedExtension();
        if (isset($values->file) && !in_array($file_ext, $field)) {
//array('.pdf', '.rar', '.txt', '.zip')
            $form['file']->addError('Soubor obsahuje neplatné přípony ' . $file_ext);
            $this->flashMessage('Soubor obsahuje neplatné přípony ', 'unsuccess');
        }
        $this->redrawControl('reqfile');
    }

    public function reqSuccess(Form $form) {
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



            //file insert 
            $uploaded = $this->database->table('files')->insert([
                'FileName' => $file_name,
                'Project' => $this->projectId,
                'FileType' => $this->database->table('filetypes')->select('idFileTypes')->where('FileType', $file_ext)->fetchField(),
                'Desc' => "",
                'Name' => ""
            ]);
            $this->database->table('projects')
                    ->where('idProjects', $this->projectId)
                    ->update([
                        'FileDir' => 'files',
                        'RqFile' => $uploaded->idFiles
            ]);
            if ($uploaded != null) {
                $this->flashMessage('uspesne ulozeno ', 'success');
                $this->redirect('this');
            } else {

                //after full success
                $this->flashMessage('neuspesne ulozeno ', 'unsuccess');
                $this->redirect('this');
            }
        }
    }

    //delete required file
    public function handleReqFileDelete($id = -1, $pdf = false) {
        $id = $this->database->table('files')->where('idFiles', $id)->fetchField('idFiles');
        if ($id == -1 || $id == null) {
            $this->flashMessage('tuto možnost není momentálně možné vykonant', 'unsuccess');
            return;
        } else {
            $file = $this->database->table('files')->where('idFiles', $id)->fetch();

            $fileFullName = $file->FileName . $file->ref('filetypes', 'FileType')->FileType;
            try {

                FileSystem::delete(__DIR__ . '\\..\\..\\www\\files\\' . $fileFullName);
            } catch (Exception $ex) {
                
            }
            $delete = $this->database->table('files')->where('idFiles', $id)->delete();
            $update;
            if ($pdf == false) {
                $update = $this->database->table('projects')->where('RqFile', $id)->update([
                    'RqFile' => "0"
                ]);
            } elseif ($pdf == true) {
                $update = $this->database->table('projects')->where('RqFilePdf', $id)->update([
                    'RqFilePdf' => "0"
                ]);
            }
            if ($delete > 0 && $update > 0) {
                $this->flashMessage('záznam byl smazán z databáze', 'success');
                $this->redirect('this');
            } else {
                $this->flashMessage('záznam nebyl smazán někde nastala chyba', 'unsuccess');
                $this->redirect('this');
            }
        }
    }
    protected function createComponentScore(){
        $form = new Form;
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
