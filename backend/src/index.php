<?php

require __DIR__ . "/vendor/autoload.php";

use App\Service\Route;
use App\Controller\WelcomeController;

Route::add('/api/orders', [WelcomeController::class, 'index'], 'GET', 'json');

Route::add('/api/orders/edit/([0-9]*)', [WelcomeController::class, 'update'], 'POST', 'json');

Route::add('/api/orders/delete/([0-9]*)', [WelcomeController::class, 'delete'], 'POST', 'json');

Route::add('/api/orders/store', [WelcomeController::class, 'store'], 'POST', 'json');




Route::run('/');