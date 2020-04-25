<?php

namespace App\Controller;

use App\Exception\CSVDBCreateUnknownColumnsException;
use App\Exception\CSVDBDuplicateRowException;
use App\Exception\CSVDBNotFoundException;
use App\Exception\CSVDBWhereNotSpecified;
use App\Exception\FileNotFoundException;
use App\Exception\FileOpenFailedException;
use App\Exception\ValidationException;
use App\Service\Helper;
use App\Service\Validator\IntegerRule;
use App\Service\Validator\NumericRule;
use App\Service\Validator\RequiredRule;
use App\Service\Validator\RequiredWithoutAllRule;
use App\Service\Validator\SizeRule;
use App\Service\Validator\StringRule;

class WelcomeController extends BaseController {

    public function index() {
        return $this->json([
            'data' => $this->db->getAll(),
        ]);
    }

    public function store() {
        $this->request->validate([
            'name' => [new RequiredRule, new StringRule],
            'state' => [new RequiredRule, new StringRule, new SizeRule(2)],
            'zip' => [new RequiredRule, new NumericRule],
            'amount' => [new RequiredRule, new NumericRule],
            'qty' => [new RequiredRule, new IntegerRule()],
            'item' => [new RequiredRule, new StringRule],
        ]);

        $error = null;
        try {
            $request = $this->request->all();
            $request['amount'] = number_format($request['amount'], 2);
            $this->db->create($request);
        } catch(CSVDBCreateUnknownColumnsException $e) {
            $error = $e->getMessage();
        } catch(CSVDBDuplicateRowException $e) {
            $error = $e->getMessage();
        } catch(FileOpenFailedException $e) {
            $error = $e->getMessage();
        }

        if($error) {
            return $this->json([
                'message' => $error,
            ], 400);
        }
        return $this->json([
            'message' => "Row create successful."
        ]);
    }

    public function update(int $id) {

        $errors = [];
        $error = null;
        try {
            $this->request->validate([
                'name' => [new RequiredWithoutAllRule(['state', 'zip', 'amount', 'qty', 'item']), new StringRule],
                'state' => [new RequiredWithoutAllRule(['name', 'zip', 'amount', 'qty', 'item']), new StringRule, new SizeRule(2)],
                'zip' => [new RequiredWithoutAllRule(['state', 'name', 'amount', 'qty', 'item']), new NumericRule],
                'amount' => [new RequiredWithoutAllRule(['state', 'zip', 'name', 'qty', 'item']), new NumericRule],
                'qty' => [new RequiredWithoutAllRule(['state', 'zip', 'amount', 'name', 'item']), new IntegerRule()],
                'item' => [new RequiredWithoutAllRule(['state', 'zip', 'amount', 'qty', 'name']), new StringRule],
            ]);
        } catch(ValidationException $e) {
            $errors = $e->getErrors();
            $error = $e->getMessage();
        }

        if($error) {
            return $this->json([
                'message' => $error,
                'errors' => $errors,
            ], 422);
        }

        try {
            $request = $this->request->all();
            if($this->request->has('amount')) {
                $request['amount'] = number_format($request['amount'], 2);
            }
            $this->db->where(['id' => $id])->update($request);

        } catch(CSVDBWhereNotSpecified $e) {
            $error = $e->getMessage();
        } catch(CSVDBNotFoundException $e) {
            $error = $e->getMessage();
        } catch(FileOpenFailedException $e) {
            $error = $e->getMessage();
        }

        if($error) {
            return $this->json([
                'message' => $error,
            ], 400);
        }
        return $this->json([
            'message' => 'Row update Successful.'
        ]);
    }

    public function delete(int $id) {
        $error = null;
        try {
            $this->db->where(['id' => $id])->delete();

        } catch(CSVDBWhereNotSpecified $e) {
            $error = $e->getMessage();
        } catch(CSVDBNotFoundException $e) {
            $error = $e->getMessage();
        } catch(FileOpenFailedException $e) {
            $error = $e->getMessage();
        }

        if($error) {
            return $this->json([
                'message' => $error,
            ], 400);
        }
        return $this->json([
            'message' => "Row delete successful."
        ]);
    }
}
