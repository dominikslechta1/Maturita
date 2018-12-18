<?php

namespace App\Presenters;

use Nette;
use Nette\Security\User;
use App\Model\MyDateTime;
use Nette\Utils\DateTime;

class HomepagePresenter extends Nette\Application\UI\Presenter {

    private $database;
    private $project;

    public function __construct(Nette\Database\Context $connection, Nette\Database\IStructure $structure, Nette\Database\IConventions $conventions = null, Nette\Caching\IStorage $cacheStorage = null) {
        $this->database = $connection;
    }

    /**
     * show projects
     */
    public function renderDefault() {
        if ($this->getUser()->isInRole('administrator')) {
            $projects = $this->database->table('projects')->fetchAll();
        } elseif ($this->getUser()->isInRole('student')) {
            $projects = $this->database->table('projects')
                            ->where('User', $this->getUser()->getId())->fetchAll();
        } elseif ($this->getUser()->isInRole('consultant')) {
            $projects = $this->database->table('projects')
                    ->where('Consultant = ? AND Year = ?', $this->getUser()->getId(), MyDateTime::getYear(DateTime::from('0')))
                    ->fetchAll();
        } elseif ($this->getUser()->isInRole('oponent')) {
            $projects = $this->database->table('projects')
                    ->where('Oponent = ? AND Year = ?', $this->getUser()->getId(), MyDateTime::getYear(DateTime::from('0')))
                    ->fetchAll();
        } else {
            $projects = $this->database->table('projects')
                    ->where("Public", 1)
                    ->where(" Year", MyDateTime::getYear(DateTime::from(0)))
                    ->fetchAll();
        }
        
        $this->template->projects = $projects;
    }

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
            $this->project = $project;
        }
    }

    public function handleDelete($id) {
        $this->database->table('files')->where('Project', $id)->delete();
        $this->database->table('projects')->where('idProjects', $id)->delete();
        $this->flashMessage('Záznam s id ' . $id . 'byl smazán');
        $this->redirect('Homepage:');
    }
    public function handlePubliced($id,$public){
        $this->database->table('projects')
                ->where('idProjects', $id)
                ->update(['Public' => ($public == 1) ? '0' : '1']);
        if($this->isAjax()){ // jde o ajax pozadavek?
          $this->redrawControl('Public'); // invalidujes dotycnej snippet

       }else{
          $this->redirect('this');
        }
    }
    public function handleLocked($id,$locked){
        $this->database->table('projects')->where('idProjects', $id)->update(['Locked' => ($locked == 1) ? '0' : '1']);
        if($this->isAjax()){ // jde o ajax pozadavek?
          $this->redrawControl('Locked'); // invalidujes dotycnej snippet

       }else{
          $this->redirect('this');
        }
    }
}
