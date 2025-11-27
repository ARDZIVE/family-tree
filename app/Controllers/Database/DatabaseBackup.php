<?php

namespace App\Controllers\Database;

use CodeIgniter\Controller;

class DatabaseBackup extends Controller
{
    public function index()
    {
        $backupPath = WRITEPATH . 'backups/';
        $files = [];

        if (is_dir($backupPath)) {
            $files = array_filter(scandir($backupPath), function($item) {
                return $item !== '.' && $item !== '..' && pathinfo($item, PATHINFO_EXTENSION) === 'sql';
            });

            // Sort files by modification time, newest first
            usort($files, function($a, $b) use ($backupPath) {
                return filemtime($backupPath . $b) - filemtime($backupPath . $a);
            });
        }

        return view('backup/backup_view', ['files' => $files]);
    }

    public function delete($filename)
    {
        $backupPath = WRITEPATH . 'backups/';
        $file = $backupPath . $filename;

        if (file_exists($file) && pathinfo($file, PATHINFO_EXTENSION) === 'sql') {
            if (unlink($file)) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Backup file deleted successfully'
                ]);
            }
        }

        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Unable to delete backup file'
        ]);
    }

    public function backup()
    {
        try {
            // Get database configuration
            $db = \Config\Database::connect();

            // Set backup folder path
            $backupPath = WRITEPATH . 'backups/';

            // Create backup directory if it doesn't exist
            if (!is_dir($backupPath)) {
                mkdir($backupPath, 0777, true);
            }

            // Generate filename with current date and time
            $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
            $filePath = $backupPath . $filename;

            // Open file for writing
            $handle = fopen($filePath, 'w+');
            if ($handle === false) {
                throw new \Exception("Unable to create backup file");
            }

            // Add SQL header
            fwrite($handle, "-- Database Backup for " . $db->database . "\n");
            fwrite($handle, "-- Generated on " . date('Y-m-d H:i:s') . "\n\n");
            fwrite($handle, "SET FOREIGN_KEY_CHECKS=0;\n\n");

            // Get all tables
            $tables = $db->listTables();

            foreach ($tables as $table) {
                // Get create table syntax
                $query = $db->query("SHOW CREATE TABLE `$table`");
                $row = $query->getRow();
                $createTableSql = isset($row->{'Create Table'}) ? $row->{'Create Table'} : '';

                // Write table structure
                fwrite($handle, "\n\n-- Table structure for table `$table`\n\n");
                fwrite($handle, "DROP TABLE IF EXISTS `$table`;\n");
                fwrite($handle, $createTableSql . ";\n\n");

                // Get table data
                $query = $db->query("SELECT * FROM `$table`");
                $rows = $query->getResultArray();

                if (!empty($rows)) {
                    fwrite($handle, "-- Dumping data for table `$table`\n");

                    // Get column names
                    $columns = array_keys($rows[0]);

                    foreach ($rows as $row) {
                        $values = array_map(function($value) use ($db) {
                            if (is_null($value)) {
                                return 'NULL';
                            }
                            return "'" . $db->escape(strval($value)) . "'";
                        }, $row);

                        $sql = "INSERT INTO `$table` (`" . implode('`, `', $columns) . "`) VALUES (" . implode(', ', $values) . ");\n";
                        fwrite($handle, $sql);
                    }
                }
            }

            // Add SQL footer
            fwrite($handle, "\n\nSET FOREIGN_KEY_CHECKS=1;\n");

            // Close file
            fclose($handle);

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Database backup created successfully',
                'filename' => $filename
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Unable to create backup: ' . $e->getMessage()
            ]);
        }
    }

    public function downloadBackup($filename)
    {
        $backupPath = WRITEPATH . 'backups/';
        $file = $backupPath . $filename;

        if (file_exists($file)) {
            return $this->response->download($file, null)->setFileName($filename);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Backup file not found'
            ]);
        }
    }
}