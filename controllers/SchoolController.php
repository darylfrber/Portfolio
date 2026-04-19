<?php

class SchoolController extends BaseController
{
    public function index(): void // Toon index van school
    {
        $this->notLoggedIn();

        $apiToken = $_ENV['BITLAB_API_KEY'];
        $gitlabUrl = 'https://git.nexed.com';
        $userId = 'darylfrber'; // Gebruiker waar je informatie van wilt ophalen

        // Haal de laatste activiteiten op van gebruiker
        $eventsUrl = $gitlabUrl . '/api/v4/users/' . $userId . '/events?private_token=' . $apiToken;
        $response_events = file_get_contents($eventsUrl);
        $events = json_decode($response_events);

        $projectData = [];

        foreach ($events as $event) {
            if (!empty($event->project_id)) {
                // Haal bijbehorende project op van laatste activiteit
                $projectUrl = $gitlabUrl . '/api/v4/projects/' . $event->project_id;
                $response_projects = file_get_contents($projectUrl . '?private_token=' . $apiToken);
                $project = json_decode($response_projects);

                // Sla activiteit en project op om in twig te gebruiken.
                $projectData[] = [
                    'event' => $event,
                    'project' => $project,
                ];

                if (count($projectData) === 5) {
                    break;
                }
            }
        }

        if (empty($projectData)) {
            $projectData = 'Geen activiteit gevonden';
        }

        $data['projectData'] = $projectData;
        $data['page'] = 'school';
        render('/school/index.twig', $data);
        exit();
    }

    public function files(): void
    {
        $folder = $_GET['folder'] ?? ''; // Haal de waarde van de optionele folder parameter op
        $folderPath = realpath(__DIR__ . DIRECTORY_SEPARATOR . '../../..') . DIRECTORY_SEPARATOR . $folder;
        $folders = [];
        $files = [];

        // Controleer of het opgegeven pad een geldige map is
        if (is_dir($folderPath)) {
            // Loop door de inhoud van de map
            $directoryContents = scandir($folderPath);
            foreach ($directoryContents as $item) {
                if ($item !== '.' && $item !== '..' && $item !== '.git' && $item !== '.gitignore' && $item !== '.DS_Store') {
                    $itemPath = $folderPath . DIRECTORY_SEPARATOR . $item;

                    if (is_dir($itemPath)) {
                        $folders[] = $item;
                    } else {
                        $files[] = $item;
                    }
                }
            }
        }

        $response = [
            'folders' => $folders,
            'files' => $files
        ];

        $jsonResponse = json_encode($response);

        if ($jsonResponse === false) {
            // Er is een fout opgetreden bij het coderen naar JSON
            $errorMessage = 'Er is een fout opgetreden bij het genereren van de JSON-respons.';
            // Eventueel extra foutafhandeling...
            die($errorMessage);
        }

        header('Content-Type: application/json');
        echo $jsonResponse;
    }
}
