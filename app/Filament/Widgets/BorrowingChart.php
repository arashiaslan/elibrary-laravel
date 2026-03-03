<?php

namespace App\Filament\Widgets;

use App\Models\Borrowing;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class BorrowingChart extends ChartWidget
{
    protected ?string $heading = 'Peminjaman Bulanan';

    protected function getData(): array
    {
        $data = Borrowing::select(
                DB::raw('MONTH(borrow_date) as month'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Total Peminjaman',
                    'data' => array_values($data),
                ],
            ],
            'labels' => array_map(
                fn ($month) => date('F', mktime(0, 0, 0, $month, 10)),
                array_keys($data)
            ),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}