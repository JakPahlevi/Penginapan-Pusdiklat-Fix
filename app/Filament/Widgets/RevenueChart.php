<?php

namespace App\Filament\Widgets;

use App\Models\Payment;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class RevenueChart extends ChartWidget
{
    protected static ?string $heading = 'Revenue (Paid)';
    protected static ?string $pollingInterval = '10s';

    // Tambahkan property untuk menyimpan interval waktu
    public ?string $filter = 'monthly'; // Default filter adalah bulanan

    // Metode untuk menentukan opsi filter
    protected function getFilters(): ?array
    {
        return [
            'daily' => 'Harian',
            'weekly' => 'Mingguan',
            'monthly' => 'Bulanan',
        ];
    }

    protected function getData(): array
    {
        // Tentukan interval waktu berdasarkan filter yang dipilih
        $interval = match ($this->filter) {
            'daily' => 'perDay',
            'weekly' => 'perWeek',
            'monthly' => 'perMonth',
            default => 'perMonth',
        };

        // Ambil data berdasarkan interval yang dipilih
        $data = Trend::query(
            Payment::where('payment_status', 'paid') // Filter berdasarkan status 'paid'
        )
            ->between(
                start: now()->startOfYear(), // Mulai dari awal tahun
                end: now()->endOfYear(), // Sampai akhir tahun
            )
            ->dateColumn('payment_date')
            ->$interval() // Gunakan interval yang dipilih (harian/mingguan/bulanan)
            ->sum('amount'); // Jumlahkan berdasarkan kolom 'amount'

        return [
            'datasets' => [
                [
                    'label' => 'Revenue (Paid)',
                    'data' => $data->map(fn(TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn(TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
