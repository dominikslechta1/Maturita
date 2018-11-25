<?php

namespace App\Model;

use Nette\Security\Permission;

class AuthorizatorFactory extends \Nette\Security\Permission
{
    /**
     * @return \Nette\Security\Permission
     */
    public static function create()
    {
        $acl = new \Nette\Security\Permission;

        // pokud chceme, můžeme role a zdroje načíst z databáze
        $acl->addRole('guest');
        
        $acl->addRole('student', 'guest');
        $acl->addRole('consultant','student');
        $acl->addRole('oponent','teacher');
        $acl->addRole('administrator','teacher');
        
        $acl->addResource('projects');
        $acl->addResource('files');
        $acl->addResource('score');
        
        $acl->allow('administrator', Permission::ALL, ['view', 'edit', 'add']);
        $acl->allow('guest','projects','view');
        $acl->deny('guest','files',['edit', 'add']);
        $acl->deny('guest','score',['edit','add']);

        return $acl;
    }
}