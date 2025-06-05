<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TestSubmissionResource\Pages;
use App\Filament\Resources\TestSubmissionResource\RelationManagers;
use App\Models\TestSubmission;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TestSubmissionResource extends Resource {
    protected static ?string $model = TestSubmission::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function canCreate(): bool {
        return false;
    }

    protected static ?int $navigationSort = 3;


    public static function form(Form $form): Form {
        return $form
            ->schema([
                Forms\Components\Select::make('test_id')
                    ->relationship('test', 'title')
                    ->required(),
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\TextInput::make('correct_ans_count')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('wrong_ans_count')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('result')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('test.title')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('correct_ans_count')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('wrong_ans_count')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('result'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array {
        return [
            //
        ];
    }
    public static function getNavigationBadge(): ?string {
        return static::getModel()::count();
    }

    public static function getPages(): array {
        return [
            'index' => Pages\ListTestSubmissions::route('/'),
            'edit' => Pages\EditTestSubmission::route('/{record}/edit'),
        ];
    }
}
