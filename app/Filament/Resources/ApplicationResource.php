<?php

namespace App\Filament\Resources;

use App\Models\Application;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;

class ApplicationResource extends Resource
{
    protected static ?string $model = Application::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Applications';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('reference_number')
                                    ->label('Reference Number')
                                    ->disabled(),
                                Select::make('status')
                                    ->label('Status')
                                    ->options([
                                        'draft' => 'Draft',
                                        'submitted' => 'Submitted',
                                        'processing' => 'Processing',
                                        'completed' => 'Completed',
                                        'rejected' => 'Rejected',
                                    ])
                                    ->required(),
                                TextInput::make('global_progress')
                                    ->label('Overall Progress (%)')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(100),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('reference_number')
                    ->label('Reference')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('business.legal_name')
                    ->label('Business Name')
                    ->searchable(),
                TextColumn::make('business.ntn')
                    ->label('NTN')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match($state) {
                        'completed' => 'success',
                        'rejected' => 'danger',
                        'submitted', 'processing' => 'info',
                        default => 'warning',
                    }),
                TextColumn::make('global_progress')
                    ->label('Progress')
                    ->sortable(),
                TextColumn::make('submitted_at')
                    ->label('Submitted')
                    ->dateTime(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'submitted' => 'Submitted',
                        'processing' => 'Processing',
                        'completed' => 'Completed',
                        'rejected' => 'Rejected',
                    ]),
            ]);
    }
}
