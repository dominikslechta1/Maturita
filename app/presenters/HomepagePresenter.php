<?php

namespace App\Presenters;

use Nette;
use Nette\Security\User;


class HomepagePresenter extends Nette\Application\UI\Presenter
{
    private $database;
   public function __construct(Nette\Database\Context $connection, Nette\Database\IStructure $structure, Nette\Database\IConventions $conventions = null, Nette\Caching\IStorage $cacheStorage = null)
    {
       $this->database = $connection;
    }
    public function renderDefault(){
        $this->template->projects = $this->database->table('projects')->select('*')->fetchAll();
    }
    
}
