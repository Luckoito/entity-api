<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\EntityController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\InstanceController;


Route::prefix('entity')->group(function () {
    Route::get('/', [EntityController::class, 'index']);
    Route::get('/count', [EntityController::class, 'count']);
    Route::get('/name/{entity_name}', [EntityController::class, 'show']);
    Route::get('/id/{entity_id}', [EntityController::class, 'showById']);

    Route::post('/new', [EntityController::class, 'store']);
    Route::put('/rename', [EntityController::class, 'rename']);
    Route::delete('/{entity_id}', [EntityController::class, 'destroy']);
});

Route::prefix('property')->group(function () {
    Route::get('/', [PropertyController::class, 'index']);
    Route::get('/id/{property_id}', [PropertyController::class, 'show']);
    Route::get('/name/{property_name}', [PropertyController::class, 'showByName']);
    Route::get('/count', [PropertyController::class, 'count']);

    Route::post('/new', [PropertyController::class, 'store']);
    Route::put('/rename', [PropertyController::class, 'rename']);
    Route::delete('/{property_id}', [PropertyController::class, 'destroy']);
});

Route::prefix('instance')->group(function () {
    Route::get('/', [InstanceController::class, 'all']);
    Route::get('/count', [InstanceController::class, 'count']);
    Route::get('/entity/{entity_name}', [InstanceController::class, 'index']);
    Route::get('/id/{instance_id}', [InstanceController::class, 'show']);
    Route::get('/data', [InstanceController::class, 'showByData']);

    Route::post('/new', [InstanceController::class, 'store']);
    Route::put('/update', [InstanceController::class, 'update']);
    Route::delete('/{instance_id}', [InstanceController::class, 'destroy']);
});
