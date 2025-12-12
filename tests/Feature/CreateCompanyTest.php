<?php

use App\Livewire\CreateCompany;
use App\Models\Field;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Livewire\Livewire;

it('creates a company with selected field', function () {
    Storage::fake('public');

    $user = User::factory()->create();

    $field = Field::factory()->create();
    $file = UploadedFile::fake()->image('logo.jpg');

    Livewire::test(CreateCompany::class)
        ->set('name', 'Test Company')
        ->set('employees', 50)
        ->set('phone', '123-456-7890')
        ->set('business_photo', $file)
        ->set('field', $field->first()->id)
        ->set('owner_id', $user->id)
        ->call('submit')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('companies', [
        'name' => 'Test Company',
        'field_id' => $field->id,
    ]);

    Storage::disk('public')->assertExists('business-profile-photos/test_company.jpg');
});

it('create a company for the user', function(){

    $user = User::factory()->create();

    Storage::fake('public');

    $field = Field::factory()->create();
    $file = UploadedFile::fake()->image('logo.jpg');

    Livewire::test(CreateCompany::class)
        ->set('name', 'Revelop S.R.L')
        ->set('employees', 50)
        ->set('phone', '123-456-7890')
        ->set('business_photo', $file)
        ->set('field', $field->first()->id)
        ->set('owner_id', $user->id)
        ->call('submit')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('companies', [
        'name' => 'Revelop S.R.L',
        'owner_id' => $user->id
    ]);

});