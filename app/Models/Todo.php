<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Todo extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'content'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeCategorySearch($query, $category_id)
    {
      if (!empty($category_id)) {
        $query->where('category_id', $category_id);
      }
    }

    public function scopeKeywordSearch($query, $keyword)
    {
      if (!empty($keyword)) {
        $query->where('content', 'like', '%' . $keyword . '%');
      }
       return $query;
    }

}
