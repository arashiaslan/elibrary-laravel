<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Book;
use App\Models\Borrowing;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', User::count())
                ->description('Semua pengguna')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make('Total Buku', Book::count())
                ->description('Buku terdaftar')
                ->descriptionIcon('heroicon-m-book-open')
                ->color('success'),

            Stat::make('Buku Dipinjam', Borrowing::where('status', 'borrowed')->count())
                ->description('Sedang dipinjam')
                ->descriptionIcon('heroicon-m-arrow-path')
                ->color('warning'),

            Stat::make('Terlambat', Borrowing::where('status', 'late')->count())
                ->description('Belum dikembalikan')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger'),
        ];
    }
}