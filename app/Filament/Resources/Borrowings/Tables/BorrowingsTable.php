<?php

namespace App\Filament\Resources\Borrowings\Tables;

use App\Models\Borrowing;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class BorrowingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // 1. Ubah user_id jadi relasi ke nama user
                TextColumn::make('user.name')
                    ->label('Nama Peminjam')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                // 2. Ubah book_id jadi relasi ke judul buku
                TextColumn::make('book.title')
                    ->label('Judul Buku')
                    ->searchable()
                    ->sortable()
                    ->wrap(), // Teks akan turun ke bawah jika judulnya panjang

                // 3. Rapikan format tanggal
                TextColumn::make('borrow_date')
                    ->label('Tgl Pinjam')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('due_date')
                    ->label('Batas Kembali')
                    ->date('d M Y')
                    ->sortable()
                    ->color('danger'), // Kasih warna merah biar admin notice batas waktunya

                TextColumn::make('return_date')
                    ->label('Tgl Dikembalikan')
                    ->date('d M Y')
                    ->sortable()
                    ->placeholder('-'), // Kalau belum dikembalikan (null), tampilkan tanda strip (-)

                // 4. Ubah status jadi Badge berwarna
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'borrowed' => 'primary', 
                        'return_pending' => 'warning', // Menambah warna peringatan (kuning/orange)
                        'returned' => 'success', 
                        'late' => 'danger',      
                        default => 'secondary',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'borrowed' => 'Sedang Dipinjam',
                        'return_pending' => 'Menunggu Admin', // Teks khusus untuk admin
                        'returned' => 'Dikembalikan',
                        'late' => 'Terlambat',
                        default => $state,
                    }),

                TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Diubah Pada')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Filter Status')
                    ->options([
                        'borrowed' => 'Sedang Dipinjam',
                        'return_pending' => 'Menunggu Konfirmasi Admin', // Opsi filter tambahan
                        'returned' => 'Sudah Dikembalikan',
                        'late' => 'Terlambat',
                    ]),
            ])
            ->recordActions([
                // ACTION BARU: Tombol untuk konfirmasi pengembalian
                Action::make('confirm_return')
                    ->label('Terima Buku')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Konfirmasi Pengembalian Buku')
                    ->modalDescription('Apakah Anda yakin sudah menerima buku fisik ini dari siswa? Status akan diubah menjadi Dikembalikan dan stok buku akan bertambah.')
                    ->modalSubmitActionLabel('Ya, Saya Terima')
                    // Logic ini memastikan tombol HANYA MUNCUL jika statusnya return_pending
                    ->visible(fn (Borrowing $record): bool => $record->status === 'return_pending')
                    ->action(function (Borrowing $record) {
                        $record->update([
                            'status' => 'returned',
                            'return_date' => Carbon::now(), // Catat tanggal & jam saat ini
                        ]);

                        $record->book->increment('stock'); // Tambah kembali stok buku
                    }),

                // Tombol Edit Bawaan
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc'); // Urutkan dari transaksi paling baru
    }
}