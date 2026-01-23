<?php

namespace Database\Seeders;

use App\Enums\AttributeType;
use App\Models\Attribute;
use App\Models\Customer;
use Illuminate\Database\Seeder;

class AttributeCustomerSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::all();

        $attributeByCategory = Attribute::with('category')->get()->groupBy(
            fn ($attribute) => $attribute->category->name
        );

        foreach ($customers as $customer){
            if(isset($attributeByCategory['Health'])){
              $healthAttribute = $attributeByCategory['Health']->random(5);

              foreach($healthAttribute as $attribute){
                  $customer->attributes()->syncWithoutDetaching([
                      $attribute->id => [
                          'value' => $this->seedValue($attribute),
                      ],
                  ]);
              }
            }
        }
    }

    private function seedValue(Attribute $attribute): mixed
    {
        return match ($attribute->type) {
            AttributeType::STRING => 'value-' . rand(1, 999),
            AttributeType::NUMBER => rand(1, 100),
            AttributeType::BOOLEAN, AttributeType::SELECT => $this->randomOptionKey($attribute),
            AttributeType::DATE => now()->subDays(rand(0, 3650))->toDateString(),
        };
    }

    private function randomOptionKey(Attribute $attribute): mixed
    {
        $options = $attribute->options ?? [];

        if (empty($options)) {
            return null;
        }

        $keys = array_keys($options);

        return $keys[array_rand($keys)];
    }
}
