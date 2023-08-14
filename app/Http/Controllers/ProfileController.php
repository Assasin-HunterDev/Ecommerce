<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

/**
 * Class ProfileController manages user profile related actions.
 *
 * @package App\Http\Controllers
 */
class ProfileController extends Controller
{
    public function view(Request $request)
    {
        return view('profile.view');
    }
}
