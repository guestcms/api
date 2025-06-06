<?php

namespace GuestCMS\Api\Tables;

use GuestCMS\Api\Models\PersonalAccessToken;
use GuestCMS\Table\Abstracts\TableAbstract;
use GuestCMS\Table\Actions\DeleteAction;
use GuestCMS\Table\BulkActions\DeleteBulkAction;
use GuestCMS\Table\Columns\Column;
use GuestCMS\Table\Columns\CreatedAtColumn;
use GuestCMS\Table\Columns\DateTimeColumn;
use GuestCMS\Table\Columns\IdColumn;
use GuestCMS\Table\Columns\NameColumn;
use GuestCMS\Table\HeaderActions\CreateHeaderAction;
use Illuminate\Database\Eloquent\Builder;

class SanctumTokenTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->setView('packages/api::table')
            ->model(PersonalAccessToken::class)
            ->addHeaderAction(CreateHeaderAction::make()->route('api.sanctum-token.create'))
            ->addAction(DeleteAction::make()->route('api.sanctum-token.destroy'))
            ->addColumns([
                IdColumn::make(),
                NameColumn::make(),
                Column::make('abilities')
                    ->label(trans('packages/api::sanctum-token.abilities')),
                DateTimeColumn::make('last_used_at')
                    ->label(trans('packages/api::sanctum-token.last_used_at')),
                CreatedAtColumn::make(),
            ])
            ->addBulkAction(DeleteBulkAction::make())
            ->queryUsing(fn (Builder $query) => $query->select([
                'id',
                'name',
                'abilities',
                'last_used_at',
                'created_at',
            ]));
    }
}
