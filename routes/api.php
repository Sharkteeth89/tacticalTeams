<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SoldierController;
use App\Http\Controllers\MissionController;
use App\Http\Controllers\TeamController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('soldiers')->group(function (){

    Route::post('/create',[SoldierController::class, 'createSoldier']);
    Route::post('/update/{id}',[SoldierController::class, 'updateSoldier']);
    Route::get('/soldier/list',[SoldierController::class, 'soldiersList']);
    Route::post('/soldier/state/{id}',[SoldierController::class, 'stateSoldier']);
    Route::get('/soldier/info/{id}',[SoldierController::class, 'infoSoldier']);
    Route::get('/soldier/historial/{id}',[SoldierController::class, 'soldierHistorial']);
    
});

Route::prefix('missions')->group(function (){

    Route::post('/create',[MissionController::class, 'createMission']);
    Route::post('/update/{id}',[MissionController::class, 'updateMission']);
    Route::get('/mission/list',[MissionController::class, 'missionList']);
    Route::get('/mission/info/{id}',[MissionController::class, 'missionInfo']);

});

Route::prefix('teams')->group(function (){

    Route::post('/create',[TeamController::class, 'createTeam']);
    Route::post('/update/{id}',[TeamController::class, 'updateTeam']);
    Route::post('/delete/{id}',[TeamController::class, 'deleteTeam']);
    Route::post('/add/soldier',[TeamController::class, 'addSoldier']);
    Route::post('/add/leader',[TeamController::class, 'addLeader']);
    Route::post('/mission/assing',[TeamController::class, 'assingMission']);
    Route::get('/team/info/{id}',[TeamController::class, 'teamInfo']);
    Route::post('/leader/update',[TeamController::class, 'updateLeader']);
    Route::post('/remove/team/member/{id}',[TeamController::class, 'removeTeamMember']);

});
