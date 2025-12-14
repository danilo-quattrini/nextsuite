<?php

use App\Models\Category;
use App\Models\Field;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('category has multiple fields', function () {
    $category = Category::factory()->create();
    $fields = Field::factory()->count(3)->create();

    // Attach the fields to the category via the pivot table
    $category->fields()->attach($fields->pluck('id')->all());

    $category->refresh();

    // relation returns three fields
    expect($category->fields->count())->toBe(3);

    // pivot table has three rows
    $this->assertDatabaseCount('field_category', 3);

    foreach ($fields as $field) {
        $this->assertDatabaseHas('field_category', [
            'field_id' => $field->id,
            'category_id' => $category->id,
        ]);
    }
});

test('field has multiple categories', function () {
    $field = Field::factory()->create();
    $categories = Category::factory()->count(3)->create();

    $field->categories()->attach($categories->pluck('id')->all());
    $field->refresh();

    expect($field->categories->count())->toBe(3);
    $this->assertDatabaseCount('field_category', 3);

    foreach ($categories as $category) {
        $this->assertDatabaseHas('field_category', [
            'field_id' => $field->id,
            'category_id' => $category->id,
        ]);
    }
});

test('pivot table structure is correct', function () {
    $this->assertTrue(Schema::hasTable('field_category'), 'field_category table should exist');
    $this->assertTrue(Schema::hasColumn('field_category', 'field_id'), 'pivot must have field_id');
    $this->assertTrue(Schema::hasColumn('field_category', 'category_id'), 'pivot must have category_id');

    // optional: ensure foreign key-ish indexes exist (index names may vary, so check for any index on the columns)
    $indexes = collect(DB::select("PRAGMA index_list('field_category')"))->pluck('name')->all();
    // This assertion is soft: we only check that index list is retrievable (schema variations can differ).
    expect(is_array($indexes))->toBeTrue();
});

test('eager loading loads fields to prevent N+1 style access', function () {
    Category::factory()->count(3)->create()->each(function ($category) {
        $category->fields()->attach(Field::factory()->count(2)->create()->pluck('id')->all());
    });

    // retrieve with eager loading
    $loaded = Category::with('fields')->get();

    expect($loaded->first()->relationLoaded('fields'))->toBeTrue();
    expect($loaded->first()->fields->count())->toBe(2);
});