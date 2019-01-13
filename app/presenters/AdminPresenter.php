<?php

namespace App\Presenters;

use Nette;
use Nette\Security\User;
use App\Model\MyDateTime;
use Nette\Utils\DateTime;
use Nette\Application\UI\Form;

class AdminPresenter extends BasePresenter {

    private $database;

    public function __construct(Nette\Database\Context $connection, Nette\Database\IStructure $structure, Nette\Database\IConventions $conventions = null, Nette\Caching\IStorage $cacheStorage = null) {
        $this->database = $connection;
    }

    public function renderProjects($Year = 1) {
        if ($Year != '1') {
            $this->template->curyear = $Year;
            $projects = $this->database->table('projects')->where('Year', $Year)->order('Year DESC')->fetchAll();
        } else {
            $projects = $this->database->table('projects')->order('Year DESC')->fetchAll();
        }
        if (sizeof($projects, 0) < 1) {
            $projects = null;
        }
        $this->template->projects = $projects;
        

        $Years = $this->database->table('projects')->order('Year DESC')->select('DISTINCT Year')->fetchAll();
        $this->template->Years = $Years;
    }

    public function handlePubliced($id, $public) {
        $this->database->table('projects')
                ->where('idProjects', $id)
                ->update(['Public' => ($public == 1) ? '0' : '1']);
        if ($this->isAjax()) { // jde o ajax pozadavek?
            $this->redrawControl('all'); // invalidujes dotycnej snippet
        } else {
            $this->redirect('this');
        }
    }

    public function handleLocked($id, $locked) {
        $this->database->table('projects')->where('idProjects', $id)->update(['Locked' => ($locked == 1) ? '0' : '1']);
        if ($this->isAjax()) { // jde o ajax pozadavek?
            $this->redrawControl('all'); // invalidujes dotycnej snippet
        } else {
            $this->redirect('this');
        }
    }

    public function handleSortNews($id) {
        $this->redirect('this', array("Year" => ($id == 'all')?'1':$id));
    }
    
    public function handleAllLock($locked, $year){
        if($year == 'all'){
        $this->database->table('projects')->update(['Locked' => ($locked == 1) ? '1' : '0']);
        }else{
            $this->database->table('projects')->where('Year', $year)->update(['Locked' => ($locked == 1) ? '1' : '0']);
        }
        if ($this->isAjax()) { // jde o ajax pozadavek?
            $this->redrawControl('all'); // invalidujes dotycnej snippet
        } else {
            $this->redirect('this',array("Year" => ($year == 'all')?'1':$year));
        }
    }
    public function handleAllHide($hide,$year){
        if($year == 'all'){
        $this->database->table('projects')->update(['Public' => ($hide == 1) ? '0' : '1']);
        }else{
            $this->database->table('projects')->where('Year', $year)->update(['Public' => ($hide == 1) ? '0' : '1']);
        }
        if ($this->isAjax()) { // jde o ajax pozadavek?
            $this->redrawControl('all'); // invalidujes dotycnej snippet
        } else {
            $this->redirect('this',array("Year" => ($year == 'all')?'1':$year));
        }
    }

}
