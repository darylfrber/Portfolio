<?php

use RedBeanPHP\R as R;

class UserController extends BaseController
{
    public function login(): void // Toon login pagina
    {
        $this->isLoggedIn(); // Als gebruiker al ingelogd is, stuur hem terug naar feed page.
        $data = [];
        render('/user/login.twig', $data);
        exit();
    }

    public function loginPost(): void // Bekijk of de ingegeven gegevens overeenkomen met die van de database
    {
        $data = json_decode(file_get_contents('php://input'), true);
        header('Content-Type: application/json');
        $username = $data['username'];
        $password = $data['password'];

        if (empty($username) || empty($password)) {
            // Gebruiker heeft geen gegevens ingevuld
            $error = "Vul alle velden in";
            echo json_encode(['error' => $error]);
            exit;
        }

        $user_details = R::find('user', ' username = ?', [$username]);
        if (count($user_details) == 1) { // Kijken of er een gebruiker is gevonden
            foreach ($user_details as $user) {
                if (password_verify($password, $user['password'])) {
                    // Wachtwoord is correct, log de gebruiker in.
                    $_SESSION['user_id'] = $user['id'];
                    echo json_encode(['succes' => true]);
                    exit();
                } else {
                    // Gebruiker heeft verkeerd wachtwoord opgegeven
                    $error = "Wachtwoord is incorrect";
                    echo json_encode(['error' => $error]);
                    exit;
                }
            }
        } else {
            // Gebruiker heeft geen bestaande gebruikersnaam opgegeven
            $error = "Er is geen gebruiker met de naam '$username'";
            echo json_encode(['error' => $error]);
            exit;
        }
    }

    public function register(): void // Toon login pagina
    {
        $this->isLoggedIn(); // Als gebruiker al ingelogd is, stuur hem terug naar feed page.
        $data = [];
        render('/user/register.twig', $data); // Laad de pagina
        exit();
    }

    public function registerPost(): void // Bekijk of de ingegeven gegevens overeenkomen met die van de database
    {
        $data = json_decode(file_get_contents('php://input'), true);
        header('Content-Type: application/json');
        $username = $data['username'];
        $password = $data['password'];
        $confirm_password = $data['confirm_password'];

        if (empty($username) || empty($password) || empty($confirm_password)) {
            $error = "Vul alle velden in";
            echo json_encode(['error' => $error]);
            exit;
        }
        $user_details = R::find('user', ' username = ?', [$username]); // ? is username
        if (count($user_details) > 0) { // Kijk of deze gebruikersnaam al bestaat
            $error = "Er bestaat al een gebruiker met de naam '$username'";
            echo json_encode(['error' => $error]);
            exit;
        }
        if ($password != $confirm_password) { // Controleer of herhaalde wachtwoord zelfde is als wachtwoord
            $error = "Wachtwoorden komen niet overeen";
            echo json_encode(['error' => $error]);
            exit;
        }

        $register = R::dispense('user'); // Maak een nieuwe gebruiker in table user
        $register->username = $username;
        $register->password = password_hash($password, PASSWORD_DEFAULT);
        // rank 0 = user, rank 1 = admin
        $register->rank = 0;
        $id = R::store($register);
        // Gebruiker is aangemaakt, log de gebruiker in
        $_SESSION['user_id'] = $id;
        echo json_encode(['succes' => true]);
        exit();
    }

    public function logout(): void // Toon login pagina
    {

        // Initialize the session.
        // If you are using session_name("something"), don't forget it now!
        session_start();

        // Unset all of the session variables.
        $_SESSION = array();

        // Finally, destroy the session.
        session_destroy();
        header("Location: login");
        exit();
    }
}
