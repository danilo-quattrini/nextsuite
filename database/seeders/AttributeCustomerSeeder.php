<?php

namespace Database\Seeders;

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
              $healthAttribute = $attributeByCategory['Health']->random(3);

              foreach($healthAttribute as $attribute){
                  $customer->attributes()->attach($attribute->id,[
                      'value' => json_encode(rand(50, 99))
                  ]);
              }
            }
        }
    }
}
