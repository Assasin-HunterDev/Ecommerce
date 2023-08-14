<?php

namespace App\Http\Controllers;

use App\Enums\AddressType;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Country;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Class ProfileController manages user profile related actions.
 *
 * @package App\Http\Controllers
 */
class ProfileController extends Controller
{
    public function view(Request $request): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
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

    public function store(ProfileRequest $request): RedirectResponse
    {
        $customerData = $request->validated();
        $shippingData = $customerData['shipping'];
        $billingData = $customerData['billing'];

        /** @var User $user */
        $user = $request->user();
        /** @var Customer $customer */
        $customer = $user->customer;

        $customer->update($customerData);

        if ($customer->shippingAddress) {
            $customer->shippingAddress->update($shippingData);
        } else {
            $shippingData['customer_id'] = $customer->user_id;
            $shippingData['type'] = AddressType::Shipping->value;
            CustomerAddress::create($shippingData);
        }
        if ($customer->billingAddress) {
            $customer->billingAddress->update($billingData);
        } else {
            $billingData['customer_id'] = $customer->user_id;
            $billingData['type'] = AddressType::Billing->value;
            CustomerAddress::create($billingData);
        }

        $request->session()->flash('flash_message', 'Profile was successfully updated.');

        return redirect()->route('profile');
    }
}
