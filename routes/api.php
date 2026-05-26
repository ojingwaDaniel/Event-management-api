<?php

use App\Http\Controllers\Api\AttendeeController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EventController;
use Illuminate\Support\Facades\Route;

Route::apiResource("events",EventController::class);
Route::get("/users/{user}/events",[EventController::class,"userEvents"]);
Route::apiResource("events.attendees",
AttendeeController::class)->scoped()->except("update");  
Route::post("/login",[AuthController::class,"login"]);
Route::post("/logout",[AuthController::class,"logout"])->middleware("auth:sanctum");