<?php

use App\Livewire\Customer\CreateCustomer;
use App\Models\Company;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\UploadedFile;

test('authenticated user can create customers', function (){
    $user = User::factory()->create();
    $company = Company::factory()->create([
        'owner_id' => $user->id,
    ]);

    Livewire::actingAs($user)
        ->test(CreateCustomer::class)
        ->set('customer_photo', UploadedFile::fake()->image('photo.jpg'))
        ->set('form.full_name', 'John Doe')
        ->set('form.email', 'john@example.com')
        ->set('form.phone', '+123456789')
        ->set('form.dob', '1990-01-01')
        ->set('form.gender', 'man')
        ->set('form.nationality', 'IT')
        ->call('submit')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('customers', [
        'full_name' => 'John Doe',
        'email'     => 'john@example.com',
        'user_id'   => $user->id,
        'company_id'=> $company->id,
    ]);
});

test('customer belong to a user', function (){
    $user = User::factory()->create();
    Company::factory()->create([
        'owner_id' => $user->id,
    ]);

    Livewire::actingAs($user)
        ->test(CreateCustomer::class)
        ->set('form.full_name', 'Jane Doe')
        ->set('form.email', 'jane@example.com')
        ->set('form.phone', '+987654321')
        ->set('form.dob', '1992-05-10')
        ->set('form.gender', 'woman')
        ->set('form.nationality', 'FR')
        ->set('customer_photo', UploadedFile::fake()->image('photo.jpg'))
        ->call('submit');

    $customer = Customer::first();

    $this->assertNotNull($customer);
    $this->assertEquals($user->id, $customer->user_id);
});