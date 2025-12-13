<?php

use App\Livewire\CreateCompany;
use App\Models\Company;
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

test('user can create multiple company', function(){
    $user = User::factory()->create();

    Storage::fake('public');

    $field = Field::factory()->create();
    $file = UploadedFile::fake()->image('logo.jpg');

    Livewire::test(CreateCompany::class)
        ->set('name', 'Company 1 S.R.L')
        ->set('employees', 50)
        ->set('phone', '123-456-7890')
        ->set('business_photo', $file)
        ->set('field', $field->id)
        ->set('owner_id', $user->id)
        ->call('submit')
        ->assertHasNoErrors();

    Livewire::test(CreateCompany::class)
        ->set('name', 'Company 2 S.R.L')
        ->set('employees', 50)
        ->set('phone', '123-456-7890')
        ->set('business_photo', $file)
        ->set('field', $field->id)
        ->set('owner_id', $user->id)
        ->call('submit')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('companies', ['name' => 'Company 1 S.R.L']);
    $this->assertDatabaseHas('companies', ['name' => 'Company 2 S.R.L']);
    $this->assertDatabaseCount('companies', 2);

});
it('allows creating multiple companies with the same name', function () {
    $user = User::factory()->create();

    Storage::fake('public');

    $field = Field::factory()->create();

    // create first company with the name
    $fileA = UploadedFile::fake()->image('logoA.jpg');
    Livewire::test(CreateCompany::class)
        ->set('name', 'Duplicate Co')
        ->set('employees', 10)
        ->set('phone', '000-000-0000')
        ->set('business_photo', $fileA)
        ->set('field', $field->id)
        ->set('owner_id', $user->id)
        ->call('submit');

    // create second company with the same name
    $fileB = UploadedFile::fake()->image('logoB.jpg');
    Livewire::test(CreateCompany::class)
        ->set('name', 'Duplicate Co')
        ->set('employees', 20)
        ->set('phone', '111-111-1111')
        ->set('business_photo', $fileB)
        ->set('field', $field->id)
        ->set('owner_id', $user->id)
        ->call('submit');

   expect(Company::where('name', 'Duplicate Co')->count())->toBe(1);
});