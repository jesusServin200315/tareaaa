<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\DownloadResponse;

class ExpoController extends Controller
{
    public function exportar()
    {
        $backup = $this->backupDatabase();

        return $this->downloadBackup($backup);
    }

    protected function backupDatabase()
    {
        $backupPath = WRITEPATH . 'blog02.sql';

        $db = db_connect();

        $username = escapeshellarg($db->username);
        $password = escapeshellarg($db->password);
        $database = escapeshellarg($db->database);

        $command = "mysqldump -u {$username} -p{$password} {$database} > " . escapeshellarg($backupPath);

        shell_exec($command);

        return $backupPath;
    }

    protected function downloadBackup($backupPath)
    {
        $backupFilename = basename($backupPath);

        $response = new DownloadResponse();
        $response->setFilePath($backupPath);
        $response->setFileName($backupFilename);

        return $response;
    }
}
