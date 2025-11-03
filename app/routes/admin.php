<?php

// Rotas para gerenciamento de veículos
$router->addRoute('GET', '/manager/vehicles', 'VehicleController@index');
$router->addRoute('GET', '/manager/vehicles/create', 'VehicleController@create');
$router->addRoute('POST', '/manager/vehicles', 'VehicleController@store');
$router->addRoute('GET', '/manager/vehicles/edit/{id}', 'VehicleController@edit');
$router->addRoute('POST', '/manager/vehicles/update/{id}', 'VehicleController@update');
$router->addRoute('GET', '/manager/vehicles/delete/{id}', 'VehicleController@delete');

