<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectStatus extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    /**
     * Check if the status can be edited
     * @return bool
     */
    public function canEdit(): bool
    {
        switch ($this->name) {
            case 'Prepare':
            case 'Closed':
            case 'Work in Progress':
            case 'Design':
            case 'Waiting for customer':
                return false;
            default:
                return true;
        }
    }
}
