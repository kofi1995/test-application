<?php


namespace App\Service;


use App\Exception\FileNotFoundException;
use App\Exception\FileOpenFailedException;

class Helper
{
    protected ?string $storagePath = null;

    public function setStoragePath(string $path) {
        $this->storagePath = $path;
    }
    private function getStoragePathString() {
        if(!$this->storagePath) {
            $this->storagePath = $_SERVER["DOCUMENT_ROOT"] . "/storage";
        }
        return $this->storagePath;
    }
    public function getStoragePath(string $file = "", bool $exists = false): string {
        $storagePath = $this->getStoragePathString();
        $file = ltrim($file, '/');
        $fullPath = $file === "" ? $storagePath : $storagePath . "/" . $file;
        if($exists && !file_exists($fullPath)) {
            throw new FileNotFoundException("Requested file/directory not found at: " . $fullPath );
        }
        return $fullPath;
    }

    public function parseCSVFile(string $filePath): array {
        $csv = array_map('str_getcsv', file($filePath));
        array_walk($csv, function(&$a) use ($csv) {
            $a = array_combine($csv[0], $a);
        });
        array_shift($csv);
        return $csv;
    }

    public function storeCSVFile(array $data, string $filePath):void {
        $file_input = fopen($this->getStoragePath('/temp.csv'),"w");
        if ( !$file_input) {
            throw new FileOpenFailedException('File open failed.');
        }
        fputs($file_input, implode(',', array_map([$this, "encodeFunc"], array_keys(reset($data)))) ."\n");
        foreach ($data as $row) {
            fputs($file_input, implode(',', array_map([$this, "encodeFunc"], $row))."\n");
        }
        fclose($file_input);
        unlink($filePath);
        rename($this->getStoragePath('/temp.csv'), $filePath);
    }

    public function jsonResponse(array $message = null, int $code = 200)  : string
    {
        // clear the old headers
        header_remove();
        // set the actual code
        http_response_code($code);
        // set the header to make sure no catching occurs
        header("Expires: on, 01 Jan 1970 00:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        // treat this as json
        header('Content-Type: application/json');
        $this->cors();

        $status = array(
            200 => '200 OK',
            400 => '400 Bad Request',
            404 => '404 Not Found',
            405 => '405 Method Not Allowed',
            422 => 'Unprocessable Entity',
            500 => '500 Internal Server Error'
        );
        // ok, validation error, or failure
        header('Status: '.$status[$code]);
        // return the encoded json
        return json_encode([
            'status' => $code < 300,
            'data' => $message
        ]);
    }

    private function encodeFunc($value) {
        ///remove any ESCAPED double quotes within string.
        $value = str_replace('\\"','"',$value);
        //then force escape these same double quotes And Any UNESCAPED Ones.
        $value = str_replace('"','\"',$value);
        //force wrap value in quotes and return
        return '"'.$value.'"';
    }

    private function cors() {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header("Access-Control-Allow-Headers: X-Requested-With, Content-Type");
    }
}