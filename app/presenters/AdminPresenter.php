<?php

namespace App\Presenters;

use Nette;
use Nette\Security\User;
use App\Model\MyDateTime;
use Nette\Utils\DateTime;
use Nette\Application\UI\Form;

class AdminPresenter extends Nette\Application\UI\Presenter {

    private $database;

    public function __construct(Nette\Database\Context $connection, Nette\Database\IStructure $structure, Nette\Database\IConventions $conventions = null, Nette\Caching\IStorage $cacheStorage = null) {
        $this->database = $connection;
    }

    public function renderProjects() {
        $projects = $projects = $this->database->table('projects')->order('Year DESC')->fetchAll();
        if (sizeof($projects, 0) < 1) {
            $projects = null;
        }
        $this->template->projects = $projects;
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

}
