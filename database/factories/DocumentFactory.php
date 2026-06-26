<?php

namespace Database\Factories;

use App\Models\CreatedBy;
use App\Models\Document;
use App\Models\DocumentType;
use App\Models\InventoryCount;
use App\Models\LossAndDamage;
use App\Models\Purchase;
use App\Models\SaleOrder;
use App\Models\StockReturn;
use App\Models\Store;

class DocumentFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'store_id' => Store::factory(),
            'document_type_id' => DocumentType::factory(),
            'sale_order_id' => SaleOrder::factory(),
            'purchase_id' => Purchase::factory(),
            'stock_return_id' => StockReturn::factory(),
            'inventory_count_id' => InventoryCount::factory(),
            'loss_and_damage_id' => LossAndDamage::factory(),
            'created_by' => CreatedBy::factory(),
            'number' => $this->faker->word(),
            'external_number' => $this->faker->word(),
            'description' => $this->faker->text(),
            'comments' => $this->faker->word(),
        ];
    }
}
