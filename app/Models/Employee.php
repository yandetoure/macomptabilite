<?php declare(strict_types=1); 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    protected $fillable = [
        'employee_number',
        'first_name',
        'last_name',
        'email',
        'phone',
        'hire_date',
        'position',
        'department',
        'base_salary',
        'address',
        'social_security_number',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'hire_date' => 'date',
        'base_salary' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Relations
    public function payrolls(): HasMany
    {
        return $this->hasMany(Payroll::class);
    }

    // Accesseur pour le nom complet
    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
