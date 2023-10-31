<?php

use RedBeanPHP\R as R;

class BaseController
{
    public function getInfo($table, $rowName, $username) // Zoek alle gegevens van een tabel row op username
    {
        $users = R::find($table, ' ' . $rowName . ' = ? ', [$username]);
        if (count($users) == 0) {
            error('404', 'Geen gebruiker gevonden met de naam \'' . $username . '\'');
        }
        foreach ($users as $user) {
            return $user;
        }
    }

    public function getAllBeans($typeOfBean) // Zoek alle gegevens van een tabel
    {
        $beans = R::findAll($typeOfBean, 'ORDER BY id DESC');
        return $beans;
    }

    public function notLoggedIn()
    {
        if (!isset($_SESSION['user_id'])) { // Als die niet ingelogd is stuur hem naar de login page met een error.
            $_SESSION['error'] = 'Je moet ingelogd zijn om deze pagina te zien';
            header("location: login");
            exit();
        }
    }

    public function isLoggedIn()
    {
        if (isset($_SESSION['user_id'])) { // Als gebruiker al ingelogd is, laat hem doorgaan.
            header("location: /");
            exit();
        }
    }
}
