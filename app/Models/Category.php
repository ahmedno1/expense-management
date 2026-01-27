<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function budgets()
    {
        return $this->hasMany(CategoryBudget::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}
