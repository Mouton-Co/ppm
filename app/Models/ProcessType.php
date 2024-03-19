<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcessType extends Model
{
    use HasFactory;

    protected $fillable = [
        'process_type',
        'required_files',
    ];

    public function isDisabled($field)
    {
        $disabled = false;

        if (in_array('PDF/DWG', explode(',', $this->required_files))) {
            $disabled = in_array($field, ['pdf', 'dwg', 'pdfOrStep', 'dwgOrStep']);
        } elseif (in_array('PDF/STEP', explode(',', $this->required_files))) {
            $disabled = in_array($field, ['pdf', 'step', 'pdfOrDwg', 'dwgOrStep']);
        } elseif (in_array('DWG/STEP', explode(',', $this->required_files))) {
            $disabled = in_array($field, ['dwg', 'step', 'pdfOrDwg', 'pdfOrStep']);
        }

        return $disabled;
    }
}
