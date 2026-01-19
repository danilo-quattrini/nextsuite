<?php
namespace App\Http\Responses;

use Illuminate\Http\RedirectResponse;
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;

/**
 * Redirect newly registered to the choice of create or no a new company.
 */
class RegisterResponse implements RegisterResponseContract
{
    /**
     * Build the response after registration.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toResponse($request): RedirectResponse
    {
        return redirect()->route('company.choice');
    }
}

