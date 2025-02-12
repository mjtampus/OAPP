<?php 

namespace App\Models;

use App\Models\ProductsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductsAttributesValues extends Model
{

    protected $fillable = [
        'products_attributes_id',
        'value',
    ];

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(ProductsAttributes::class);
    }

}