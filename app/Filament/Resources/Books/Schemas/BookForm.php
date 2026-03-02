<?php

namespace App\Filament\Resources\Books\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class BookForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([ // Gunakan components()
                Select::make('category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                TextInput::make('title')
                    ->label('Judul Buku')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),

                TextInput::make('slug')
                    ->required()
                    ->readOnly(),

                TextInput::make('author')
                    ->label('Penulis')
                    ->required(),

                TextInput::make('publisher')
                    ->label('Penerbit'),

                TextInput::make('stock')
                    ->label('Stok Buku')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->minValue(0),

                FileUpload::make('cover_image')
                    ->label('Cover Buku')
                    ->image()
                    ->disk('public') 
                    ->directory('book-covers'),

                Textarea::make('synopsis')
                    ->label('Sinopsis')
                    ->rows(5)
                    ->columnSpanFull(),
            ]);
    }
}