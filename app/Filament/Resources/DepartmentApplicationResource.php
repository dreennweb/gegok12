<?php

namespace App\Filament\Resources;

use App\Models\DepartmentApplication;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;

class DepartmentApplicationResource extends Resource
{
    protected static ?string $model = DepartmentApplication::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-list';
    protected static ?string $navigationGroup = 'Applications';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('status')
                                    ->label('Status')
                                    ->options([
                                        'pending' => 'Pending',
                                        'in_review' => 'In Review',
                                        'queried' => 'Queried',
                                        'approved' => 'Approved',
                                        'rejected' => 'Rejected',
                                    ])
                                    ->required(),
                                TextInput::make('certificate_number')
                                    ->label('Certificate Number')
                                    ->nullable(),
                                Textarea::make('query_notes')
                                    ->label('Query Notes')
                                    ->nullable()
                                    ->rows(3),
                                Textarea::make('rejection_reason')
                                    ->label('Rejection Reason')
                                    ->nullable()
                                    ->rows(3),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('application.reference_number')
                    ->label('Application')
                    ->searchable(),
                TextColumn::make('department.name')
                    ->label('Department')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match($state) {
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'queried' => 'warning',
                        'in_review' => 'info',
                        default => 'gray',
                    }),
                TextColumn::make('progress')
                    ->label('Progress')
                    ->sortable(),
                TextColumn::make('assignee.name')
                    ->label('Assigned To')
                    ->nullable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'in_review' => 'In Review',
                        'queried' => 'Queried',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),
                SelectFilter::make('department_id')
                    ->relationship('department', 'name'),
            ]);
    }
}
