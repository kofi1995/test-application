<?php


namespace App\Service;


use App\Exception\CSVDBCreateUnknownColumnsException;
use App\Exception\CSVDBDuplicateRowException;
use App\Exception\CSVDBInitializedFailException;
use App\Exception\CSVDBNotFoundException;
use App\Exception\CSVDBWhereNotSpecified;
use App\Exception\FileNotFoundException;

class CSVDatabase
{
    private Helper $helper;
    private string $csvPath;
    private ?array $csv;
    private ?array $where = null;

    public function __construct(Helper $helper)
    {
        $this->helper = $helper;
    }

    public function setCSVPath(string $csvPath) {
        $this->csvPath = $csvPath;
    }

    public function initialize() :void {
        try {
            $this->csv = $this->helper->parseCSVFile($this->helper->getStoragePath($this->csvPath, true));
        } catch(FileNotFoundException $e) {
            throw new CSVDBInitializedFailException($e->getMessage());
        }

    }
    public function where(array $where): CSVDatabase {
        $this->where = $where;
        return $this;
    }

    public function first(): array {
        return $this->csv[$this->search()];
    }

    public function update(array $update) {
        $rowKey = $this->search(true);
        $rowToUpdate = $this->csv[$rowKey];
        foreach($update as $key => $val) {
            if(!isset($rowToUpdate[$key]) ||$key === "id" ) { //prevent ID from being overwritten for consistency
                throw new CSVDBNotFoundException("WRITE: Cannot find '${key}' in CSV column");
            }
            $rowToUpdate[$key] = $val;
        }
        $this->csv[$rowKey] = $rowToUpdate;
        $this->helper->storeCSVFile($this->csv, $this->helper->getStoragePath($this->csvPath));
    }

    public function delete() {
        $rowKey = $this->search(true);
        unset($this->csv[$rowKey]);
        $this->helper->storeCSVFile($this->csv, $this->helper->getStoragePath($this->csvPath));
    }

    public function create(array $create, bool $duplicate = false) {
        $lastElement = end($this->csv);
        $lastId = $lastElement['id'];
        unset($lastElement['id']);
        if(!$this->keysAreEqual($lastElement, $create)) {
            throw new CSVDBCreateUnknownColumnsException("WRITE: Create method expects the exact columns found in CSV file");
        }
        if(!$duplicate) {
            $csv = $this->csv;
            foreach($csv as $row) {
                unset($row['id']);
                if($row == $create) {
                    throw new CSVDBDuplicateRowException("WRITE: The data you are trying to write already exists");
                }
            }
        }

        $create['id'] = $lastId + 1;
        $create = array_merge(array_flip(['id', 'name', 'state', 'zip', 'amount', 'qty', 'item']), $create);
        $this->csv[] = $create;
        $this->helper->storeCSVFile($this->csv, $this->helper->getStoragePath($this->csvPath));
    }

    private function search(bool $update = false): int {
        if(!$this->where && !$update) {
            return 0; //first_key
        }
        elseif(!$this->where && $update) {
            throw new CSVDBWhereNotSpecified("Update field needs to be specified.");
        }
        $where = $this->where;
        $this->where = null;
        $key = array_search($where[key($where)], array_column($this->csv, key($where)));
        if($key === false) {
            throw new CSVDBNotFoundException("READ: Cannot find '${where[key($where)]}' in " . key($where) . "' column");
        }
        return $key;
    }

    public function getAll() :array {
            return $this->csv;
    }

    private function keysAreEqual($array1, $array2) {
        return !array_diff_key($array1, $array2) && !array_diff_key($array2, $array1);
    }

}