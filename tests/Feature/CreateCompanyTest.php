<?php

use App\Livewire\CreateCompany;
use App\Models\Field;
use Illuminate\Http\UploadedFile;
use Livewire\Livewire;

it('creates a company with selected field', function () {
    Storage::fake('public');

    $field = Field::factory()->create();
    $file = UploadedFile::fake()->image('logo.jpg');

    Livewire::test(CreateCompany::class)
        ->set('name', 'Test Company')
        ->set('employees', 50)
        ->set('phone', '123-456-7890')
        ->set('business_photo', $file)
        ->set('field', $field->first()->id)
        ->call('submit')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('companies', [
        'name' => 'Test Company',
        'field_id' => $field->id,
    ]);

    Storage::disk('public')->assertExists('business-profile-photos/test_company.jpg');
});
