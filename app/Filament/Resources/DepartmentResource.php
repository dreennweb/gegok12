<?php

namespace App\Filament\Resources;

use App\Models\Department;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\TernaryFilter;

class DepartmentResource extends Resource
{
    protected static ?string $model = Department::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationGroup = 'Administration';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Department Name')
                                    ->required()
                                    ->unique(ignoreRecord: true),
                                TextInput::make('slug')
                                    ->label('Slug')
                                    ->required()
                                    ->unique(ignoreRecord: true),
                                TextInput::make('code')
                                    ->label('Department Code')
                                    ->required()
                                    ->unique(ignoreRecord: true),
                                TextInput::make('contact_email')
                                    ->label('Contact Email')
                                    ->email()
                                    ->nullable(),
                                TextInput::make('contact_phone')
                                    ->label('Contact Phone')
                                    ->tel()
                                    ->nullable(),
                                Toggle::make('is_active')
                                    ->label('Active')
                                    ->default(true),
                            ]),
                        Textarea::make('description')
                            ->label('Description')
                            ->nullable()
                            ->rows(3),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('code')
                    ->searchable(),
                TextColumn::make('contact_email')
                    ->searchable(),
                TextColumn::make('is_active')
                    ->label('Status')
                    ->badge()
                    ->color(fn(bool $state): string => $state ? 'success' : 'danger'),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Active'),
            ]);
    }
}
