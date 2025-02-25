<?php 

namespace App\Models;

use App\Models\ProductsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductsAttributesValues extends Model
{
    use HasFactory;

    protected $fillable = [
        'products_attributes_id',
        'value',
        'code',
    ];

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(ProductsAttributes::class);
    }

}