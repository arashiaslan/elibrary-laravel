<?php

namespace App\Filament\Resources\Borrowings\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class BorrowingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // 1. Ubah input angka jadi dropdown relasi ke tabel users
                Select::make('user_id')
                    ->label('Nama Peminjam')
                    // Opsional: ->relationship('user', 'name', fn ($query) => $query->where('role', 'member')) 
                    // (Pakai query di atas kalau mau memfilter agar admin tidak bisa meminjam buku)
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                // 2. Dropdown relasi ke tabel books
                Select::make('book_id')
                    ->label('Buku yang Dipinjam')
                    ->relationship('book', 'title')
                    ->searchable()
                    ->preload()
                    ->required(),

                // 3. Set default hari ini
                DatePicker::make('borrow_date')
                    ->label('Tanggal Pinjam')
                    ->required()
                    ->default(now()),

                // 4. Set default batas waktu (misal 7 hari dari sekarang)
                DatePicker::make('due_date')
                    ->label('Batas Kembali (Jatuh Tempo)')
                    ->required()
                    ->default(now()->addDays(7)),

                DatePicker::make('return_date')
                    ->label('Tanggal Dikembalikan'),

                // 5. Ubah teks input jadi pilihan status yang fix
                Select::make('status')
                    ->label('Status Peminjaman')
                    ->options([
                        'borrowed' => 'Sedang Dipinjam',
                        'returned' => 'Sudah Dikembalikan',
                        'late' => 'Terlambat',
                    ])
                    ->required()
                    ->default('borrowed'),
            ]);
    }
}
