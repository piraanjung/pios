<?php

use App\Features\IdentityAndOnboarding\OnboardCitizen\OnboardCitizenController;
use Illuminate\Support\Facades\Route;

Route::post('/features/onboard-citizen', OnboardCitizenController::class)
    ->name('store');
