<?php
namespace App\Http\Responses;

use Illuminate\Http\RedirectResponse;
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;

/**
 * Redirect newly registered users to the company creation page.
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
        return redirect()->route('company.create');
    }
}

