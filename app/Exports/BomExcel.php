<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class BomExcel implements ToCollection
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection(Collection $rows)
    {
        dd($rows);
    }
}
