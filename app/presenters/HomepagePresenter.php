<?php

namespace App\Presenters;

use Nette;
use Nette\Security\User;
use App\Model\MyDateTime;
use Nette\Utils\DateTime;

class HomepagePresenter extends Nette\Application\UI\Presenter
{
    private $database;
   public function __construct(Nette\Database\Context $connection, Nette\Database\IStructure $structure, Nette\Database\IConventions $conventions = null, Nette\Caching\IStorage $cacheStorage = null)
    {
       $this->database = $connection;
    }
    /**
     * show projects
     */
    public function renderDefault(){
        //show project to defined user
        $projects;
        if($this->getUser()->isInRole('administrator')){
            $projects = $this->database->table('projects')->fetchAll();
        }
        elseif($this->getUser()->isInRole('student')){
            $projects = $this->database->table('projects')
                    ->where('User',$this->getUser()->getId())->fetchAll();
        }
        elseif($this->getUser()->isInRole('consultant')){
            $projects = $this->database->table('projects')
                    ->where('Consultant = ? AND Year = ?', $this->getUser()->getId(), MyDateTime::getYear(DateTime::from('0')))
                    ->fetchAll();
        }
        elseif($this->getUser()->isInRole('oponent')){
            $projects = $this->database->table('projects')
                    ->where('Oponent = ? AND Year = ?', $this->getUser()->getId(),MyDateTime::getYear(DateTime::from('0')))
                    ->fetchAll();
        }
        else{
            $projects = $this->database->table('projects')
                    ->where("Public", 1)
                    ->where(" Year", MyDateTime::getYear(DateTime::from(0)))
                    ->fetchAll();
        }
        $this->template->projects = $projects;
    }    
}
