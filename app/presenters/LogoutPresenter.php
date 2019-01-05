<?php
namespace App\Presenters;
use Nette;
use Nette\Application\UI;

class LogoutPresenter extends Nette\Application\UI\Presenter
{
  protected function startup(){
    parent::startup();
    $user = $this->getUser();
    if($user->isLoggedIn()) {
      $user->logout();
    }
    $this->redirect('Homepage:');
  }
}