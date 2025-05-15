<?php

namespace App\Exports;

use App\Models\Guest;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ArrangedByTablesExport implements FromCollection, WithHeadings
{
    protected $invitationId;

    public function __construct($invitationId)
{
    $this->invitationId = $invitationId;
}

    public function collection(): Collection
    {
    $loggedUserId = Auth::user()?->id;

    $guests = Guest::query()
        ->select('guests.name', 'guests.partner_name', 'guests.nr_of_kids', 'tables.table_number')
        ->leftJoin('guest_table', 'guests.id', '=', 'guest_table.guest_id')
        ->leftJoin('tables', 'guest_table.table_id', '=', 'tables.id')
        ->where('guests.user_id', $loggedUserId)
        ->where('guests.invitation_id', $this->invitationId)
        ->orderBy('tables.table_number')
        ->orderBy('guests.name')
        ->get();

    $grouped = $guests->groupBy('table_number');

    $data = [];

    foreach ($grouped as $tableNumber => $guestsAtTable) {
        $data[] = [
            'Table' => __('translations.Table') . ' ' . ($tableNumber ?? 'No Table'),
            'Name' => '',
            'Partner Name' => '',
            'Number of Kids' => '',
        ];

        foreach ($guestsAtTable as $guest) {
            $data[] = [
                'Table' => '',
                'Name' => $guest->name ?? '-',
                'Partner Name' => $guest->partner_name ?: '-',
                'Number of Kids' => $guest->nr_of_kids > 0 ? $guest->nr_of_kids : '-',
            ];
        }
    }

    return new Collection($data);
}

    public function headings(): array
{
    return [
        __('translations.Table'),
        __('translations.Name'),
        __('translations.Partner name'),
        __('translations.Number of kids'),
    ];
}
}
