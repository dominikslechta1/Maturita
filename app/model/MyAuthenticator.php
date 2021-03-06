<?php

class MyAuthenticator implements \Nette\Security\IAuthenticator {

    private $database;

    public function __construct(Nette\Database\Context $connection, Nette\Database\IStructure $structure, Nette\Database\IConventions $conventions = null, Nette\Caching\IStorage $cacheStorage = null) {
        $this->database = $connection;
    }

    public function authenticate(array $credentials) {
        list($username, $password) = $credentials;
        $row = $this->database->table('users')
        ->where('Email', $username)->fetch();

        if (!$row) {
            throw new Nette\Security\AuthenticationException('User not found.');
        }
        if (!Nette\Security\Passwords::verify($password, $row->Password)) {
            throw new Nette\Security\AuthenticationException('Invalid Password.');
        }

        return new Nette\Security\Identity($row->idUsers, $row->ref('privileges','UserPrivilege')->Privilege, ['username' => $row->UserName, 'email' => $row->Email]);
    }

}
