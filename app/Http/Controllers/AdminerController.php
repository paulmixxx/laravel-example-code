<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Miroc\LaravelAdminer\AdminerController as Basic;

class AdminerController extends Basic
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();

        if (Gate::denies('view-panel')) {
            abort(403);
        }

        return parent::index();
    }
}
