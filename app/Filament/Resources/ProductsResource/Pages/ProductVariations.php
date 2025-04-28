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
                ->label('Attribute Type');
            $fields[] = TextInput::make('variation_type_' . $type->id . '.name')
                ->label('Attribute Value'); 
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
                        FileUpload::make('sku_image_dir')
                            ->label('Variant Image')
                            ->columnSpanFull()
                            ->image()  // This will show the image preview if one exists
                            ->imagePreviewHeight('200px'),  // Optional: set a fixed height for image preview
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
        
        $existingSkus = $record->sku->keyBy(fn($sku) => json_encode($sku->attributes));
        $updatedSkus = [];
        
        foreach ($variations as $variation) {
            $attributesArray = collect($variation)
                ->filter(fn($value, $key) => str_starts_with($key, 'variation_type_'))
                ->map(fn($option) => [(int) $option['id'], (int) $option['value_id']])
                ->values()
                ->toArray();
            
            $skuData = [
                'stock' => $variation['stock'],
                'price' => $variation['price'],
                'sku_image_dir' => $variation['sku_image_dir'] ?? null,
            ];
            
            if (isset($existingSkus[json_encode($attributesArray)])) {
                $existingSku = $existingSkus[json_encode($attributesArray)];
                $existingSku->update($skuData);
                $updatedSkus[] = $existingSku->id;
            } else {
                $newSku = ProductsSKU::create(array_merge($skuData, [
                    'sku' => $this->generateSKU($attributesArray),
                    'products_id' => $record->id,
                    'attributes' => $attributesArray,
                ]));
                $updatedSkus[] = $newSku->id;
            }
        }
    
        $record->sku()->whereNotIn('id', $updatedSkus)->delete();
    
        return $record;
    }
    
    
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $variations = $this->record->sku->toArray();
        $data['variations'] = $this->generateVariationCombination($this->record->attributes, $variations);
        return $data;
    }

    private function generateVariationCombination($attributes, array $variations): array
    {
        $defaultStock = $this->record->stock ?? 0;
        $defaultPrice = $this->record->price ?? 0;
    
        $productVariantCollection = $this->productVariantCollection($attributes);
    
        $mergeResult = [];
    
        foreach ($productVariantCollection as $product) {
            $optionIds = collect($product)
                ->filter(fn($value, $key) => str_starts_with($key, 'variation_type_'))
                ->flatMap(fn($option) => [(int) $option['id'], (int) $option['value_id']])
                ->toArray();
    
            $exist = array_filter($variations, fn($existingOption) => $existingOption['attributes'] === $optionIds);
    
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

            $mergeResult[] = $product;

        }
        return $mergeResult;
    }
    
    private function productVariantCollection($attributes)
    {
        $result = [[]];
    
        foreach ($attributes as $attribute) {
            $data = [];
            foreach ($attribute->product_attribute_values as $option) {
                foreach ($result as $combination) {
                    $data[] = $combination + [
                        'variation_type_' . $attribute->id => [
                            'id' => $attribute->id,
                            'value_id' => $option->id,
                            'name' => $option->value,
                            'label' => $attribute->type
                        ]
                    ];
                }
            }
            $result = $data;
        }
    
        return $result;
    }
    
    private function generateSKU($combination)
    {
        return 'SKU-' . $this->record->name. '-' .substr(md5(json_encode($combination)), 0, 8);
    }

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return 'Generate Variations';
    }
    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-plus-circle';  // Using a Heroicons icon name.
    }
}
