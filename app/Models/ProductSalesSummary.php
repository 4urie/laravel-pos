<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSalesSummary extends Model
{
    // This model maps to the database view 'product_sales_summary'
    protected $table = 'product_sales_summary';

    // Since this is a view, not a table, it won't have created_at or updated_at columns
    public $timestamps = false;

    // Optionally define fillable columns if you plan to mass assign (not required for views)
    protected $fillable = [
        'product_id',
        'product_name',
        'total_quantity_sold',
        'total_revenue',
    ];
}
