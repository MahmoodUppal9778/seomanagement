<?php
namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SitesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $sites;
    protected $columns;

    public function __construct($sites, $columns)
    {
        $this->sites = $sites;   // Accept filtered data
        $this->columns = $columns;  // Accept selected columns
    }

    /**
     * Return the collection of data to export.
     */
    public function collection()
    {
        return $this->sites;  // Use the passed-in filtered data
    }

    /**
     * Map each row of the site data based on the selected columns.
     */
    public function map($site): array
    {
        $row = [];
        foreach ($this->columns as $column) {
            $row[] = $site->$column;
        }
        return $row;
    }

    /**
     * Define the column headings.
     */
    public function headings(): array
    {
        return $this->columns;
    }
}
