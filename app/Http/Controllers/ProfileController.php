<?php

namespace App\Http\Controllers;

use App\Enums\AddressType;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Country;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\User;
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
        /** @var User $user */
        $user = $request->user();
        /** @var Customer $customer */
        $customer = $user->customer;
        $shippingAddress = $customer->shippingAddress ?: new CustomerAddress(['type' => AddressType::Shipping]);
        $billingAddress = $customer->billingAddress ?: new CustomerAddress(['type' => AddressType::Billing]);
        $countries = Country::query()->orderBy('name')->get();
        return view('profile.view', compact('customer', 'user', 'shippingAddress', 'billingAddress', 'countries'));
    }
}
