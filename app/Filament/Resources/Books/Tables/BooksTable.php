<?php

namespace App\Filament\Resources\Books\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class BooksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // 1. Gambar ditaruh paling depan agar UI lebih manis
                ImageColumn::make('cover_image')
                    ->label('Cover')
                    ->disk('public')
                    ->square(), // Bisa diganti ->circular() kalau mau bulat

                // 2. Judul dipertebal
                TextColumn::make('title')
                    ->label('Judul Buku')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                // 3. Ubah category_id jadi relasi ke nama kategori + jadikan badge
                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->sortable()
                    ->searchable()
                    ->badge(),

                TextColumn::make('author')
                    ->label('Penulis')
                    ->searchable(),

                // Publisher disembunyikan secara default agar tabel tidak terlalu lebar
                TextColumn::make('publisher')
                    ->label('Penerbit')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                // 4. Stok diberi warna otomatis (merah=habis, kuning=mau habis, hijau=aman)
                TextColumn::make('stock')
                    ->label('Stok')
                    ->numeric()
                    ->sortable()
                    ->color(fn (string $state): string => match (true) {
                        $state == 0 => 'danger',
                        $state <= 3 => 'warning',
                        default => 'success',
                    }),

                // Kolom sisanya disembunyikan ke dalam toggle menu
                TextColumn::make('slug')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Diubah Pada')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // 5. Tambahkan filter kategori agar admin gampang mencari buku
                SelectFilter::make('category_id')
                    ->relationship('category', 'name')
                    ->label('Filter Kategori')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}