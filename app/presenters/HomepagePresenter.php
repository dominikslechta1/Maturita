<?php

namespace App\Presenters;

use Nette;
use Nette\Security\User;


class HomepagePresenter extends Nette\Application\UI\Presenter
{
    
   public function __construct(Nette\Database\Context $database)
    {
       
    }
    protected function startup(){
        parent::startup();
        
        $user = $this->getUser();
  
        //$authenticate = \App\Model\MyAuthenticator::authenticate(['john','12345']);
    }

}
