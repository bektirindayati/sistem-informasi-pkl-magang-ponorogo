<?php

namespace App\Models;

class Admin extends User
{
    protected static function booted(): void
    {
        static::addGlobalScope('adminOnly', function ($query) {
            $query->whereIn('role', ['admin', 'super_admin']);
        });
    }
}