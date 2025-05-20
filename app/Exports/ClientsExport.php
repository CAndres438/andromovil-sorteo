<?php

namespace App\Exports;

use App\Models\Client;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;

class ClientsExport implements FromCollection, WithHeadings, WithTitle, WithMapping
{
    public function collection()
    {
        return Client::all();
    }

    public function headings(): array
    {
        return [
            'Nombre',
            'Apellido',
            'Cédula',
            'Correo',
            'Teléfono',
            'Departamento',
            'Ciudad',
            'Ganador',
            'Registrado en',
        ];
    }

    public function title(): string
    {
        return 'Participantes Andromóvil';
    }

    public function map($client): array
    {
        return [
            $client->name,
            $client->lastname,
            $client->id_number,
            $client->email,
            $client->phone,
            $client->department,
            $client->city,
            $client->is_winner ? 'VERDADERO' : 'FALSO',
            $client->created_at->format('d-m-Y H:i'),
        ];
    }
}


