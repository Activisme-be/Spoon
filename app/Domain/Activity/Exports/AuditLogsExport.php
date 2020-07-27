<?php

namespace App\Domain\Activity\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use Spatie\Activitylog\Models\Activity;

/**
 * Class AuditLogsExport
 *
 * Method for exporting the internal application logs to an excel file (.xls).
 *
 * @package App\Exports
 */
class AuditLogsExport implements WithHeadings, ShouldAutoSize, WithEvents, WithMapping, FromCollection
{
    use Exportable;

    public function __construct(?string $criteria)
    {
        $this->criteria = $criteria;
    }

    /**
     * Map the attributes for the .xls file.
     *
     * @param  mixed $log The entity from the collection.
     * @return array
     */
    public function map($log): array
    {
        return [$log->causer->name, $log->log_name, $log->description, $log->created_at->format('d-m-Y H:i:s')];
    }

    public function headings(): array
    {
        return ['Persoon:', 'Categorie:', 'Handeling:', 'Datum:'];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => static function (AfterSheet $event): void {
                $event->sheet->getDelegate()->getStyle('A1:E1')->getFont()->setBold(true);
            },
        ];
    }

    public function collection(): Collection
    {
        return $this->getResultsByCriteria();
    }

    protected function getResultsByCriteria(): Collection
    {
        $query = Activity::query();

        switch ($this->criteria) {
            case '3-months':    return $query->whereBetween('created_at', [now()->subMonths(3), now()])->get();
            case '6-months':    return $query->whereBetween('created_at', [now()->subMonths(6), now()])->get();
            case 'recent-year': return $query->whereBetween('created_at', [now()->subYear(), now()])->get();
            default:            return $query->get();
        }
    }
}
