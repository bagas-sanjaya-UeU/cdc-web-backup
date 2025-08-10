<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'categories';
    protected $fillable = ['name'];

    /**
     * Get the menu items associated with the category.
     */
    public function menuItems()
    {
        return $this->hasMany(MenuItem::class);
    }

    /**
     * Get the category name.
     */
    public function getNameAttribute($value)
    {
        return ucfirst($value);
    }
}
