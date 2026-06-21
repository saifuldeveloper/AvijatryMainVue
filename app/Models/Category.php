<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'parent_id', 'deleted_by'];
    protected $with = ['parent'];
    protected $appends = ['full_name'];

    public static function parentCategoriesQuery()
    {
        return self::where('parent_id', 0)->orderBy('id', 'asc');
    }

    // Relationships
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    // Accessor for Full Name
    public function getFullNameAttribute()
    {
        if ($this->parent_id === 0) {
            return $this->attributes['name'] ?? '';
        }
        $parentCategory = Category::find($this->parent_id);
        if ($parentCategory) {
            return $parentCategory->name . '-' . ($this->attributes['name'] ?? '');
        } else {
            return $this->attributes['name'] ?? '';
        }
    }
}
