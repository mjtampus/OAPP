<?php

namespace App\Filament\Resources\ProductsResource\Pages;

use Filament\Actions;
use Filament\Forms\Form;
use App\Models\ProductsSKU;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\EditRecord;
use Filament\Forms\Components\FileUpload;
use App\Filament\Resources\ProductsResource;

class ProductVariations extends EditRecord
{
    protected static string $resource = ProductsResource::class;

    public function form(Form $form): Form
    {
        $types = $this->record->attributes;
        $fields = [];
    
        foreach ($types as $type) {
            $fields[] = TextInput::make('variation_type_' . $type->id . '.id')
                ->hidden();
            $fields[] = TextInput::make('variation_type_' . $type->id . '.label')
                ->label('Attribute Type')
                ->disabled();
            $fields[] = TextInput::make('variation_type_' . $type->id . '.name')
                ->label('Attribute Value'); // Display "Red", "Large", etc.
        }

        return $form->schema([
            Repeater::make('variations')
                ->collapsible()
                ->defaultItems(1)
                ->columnspan(2)
                ->addable(false)
                ->schema([
                    Section::make()
                        ->columns(2)
                        ->schema($fields),
                    Group::make([
                        FileUpload::make('sku_image_dir')->label('Variant Image')->columnSpanFull(),
                        TextInput::make('sku')->label('SKU')->disabled(),
                        TextInput::make('stock')->label('Stock')->numeric()->required(),
                        TextInput::make('price')->label('Price')->numeric()->required(),
                    ])->columns(3)
                ])
        ]);
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $variations = $data['variations'];
        unset($data['variations']);

        
        $record->update($data);
        $record->sku()->delete(); // Remove old SKUs to prevent duplicates

        foreach ($variations as $variation) {
            ProductsSKU::create([
                'sku' => $this->generateSKU($variation),
                'products_id' => $record->id,
                'attributes' => json_encode(
                                collect($variation)
                                    ->filter(fn($value, $key) => str_starts_with($key, 'variation_type_')) // Keep only variation attributes
                                    ->mapWithKeys(fn($option) => [$option['id'] => $option['name']]) // Store as {id: value}
                                    ->toArray()
                            ),
                'stock' => $variation['stock'],
                'price' => $variation['price'],
            ]);
        }

        return $record;
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $variations = $this->record->sku->toArray();

        $data['variations'] = $this->generateVariationCombination(
            $this->record->attributes,
            $variations
        );


        return $data;
    }

    private function generateVariationCombination($attributes, array $variations): array
    {
        $defaultStock = $this->record->stock ?? 0;
        $defaultPrice = $this->record->price ?? 0;
    
        $productVariantCollection = $this->productVariantCollection($attributes, $defaultStock, $defaultPrice);

        // dd($productVariantCollection);
        $mergeResult = [];
    
        foreach ($productVariantCollection as $product) {
            $optionIds = collect($product)
                ->filter(fn($value, $key) => str_starts_with($key, 'variation_type_'))
                ->map(fn($option) => $option['id'])
                ->values()
                ->toArray();


            $exist = array_filter($variations, function ($existingOption) use ($optionIds) {

                return json_decode($existingOption['attributes'], true) === $optionIds;

            });
    
            if (!empty($exist)) {
                $existingRecord = reset($exist);
                $product['stock'] = $existingRecord['stock'];
                $product['price'] = $existingRecord['price'];
                $product['sku'] = $existingRecord['sku'];
            } else {
                $product['stock'] = $defaultStock;
                $product['price'] = $defaultPrice;
                $product['sku'] = $this->generateSKU($optionIds);
            }
    
            // Ensure attribute type (e.g., "Color") is displayed
            // foreach ($attributes as $attribute) {
            //     $product['variation_type_' . $attribute->id]['label'] = $attribute->type;
            // }
    
            $mergeResult[] = $product;
        }
        // dd($mergeResult);
        return $mergeResult;
    }
    

    private function productVariantCollection($attributes, int|string $defaultStock, int|string $defaultPrice)
    {
        $result = [[]];
    
        foreach ($attributes as $attribute) {
            $data = [];
            foreach ($attribute->product_attribute_values as $option) {
                foreach ($result as $combination) {
                    $newItem = $combination + [
                        'variation_type_' . $attribute->id => [
                            'id' => $option->id,
                            'name' => $option->value,
                            'label' => $attribute->type, // Ensure attribute type is stored
                        ]
                    ];
                    $data[] = $newItem;
                }
            }
            $result = $data;
        }
    
        foreach ($result as &$combination) {
            if (count($combination) === count($attributes)) {
                $combination['stock'] = $defaultStock;
                $combination['price'] = $defaultPrice;
            }
        }
   
        return $result;
    }
    

    private function generateSKU($combination)
    {

        return 'SKU-' . substr(md5(json_encode($combination)), 0, 8);
        
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}