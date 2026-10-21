<?php

namespace FriendsOfBotble\SanctumToken\Tables;

use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\DateTimeColumn;
use Botble\Table\Columns\FormattedColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\Columns\NameColumn;
use Botble\Table\HeaderActions\CreateHeaderAction;
use FriendsOfBotble\SanctumToken\Models\PersonalAccessToken;
use FriendsOfBotble\SanctumToken\Tables\BulkActions\DeleteTokensBulkAction;
use Illuminate\Database\Eloquent\Builder;

class SanctumTokenTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->setView('plugins/sanctum-token::table')
            ->model(PersonalAccessToken::class)
            ->addHeaderAction(CreateHeaderAction::make()->route('sanctum-token.create'))
            ->addAction(DeleteAction::make()->route('sanctum-token.destroy'))
            ->addColumns([
                IdColumn::make(),
                NameColumn::make(),
                FormattedColumn::make('tokenable_type')
                    ->label(trans('plugins/sanctum-token::sanctum-token.owner'))
                    ->getValueUsing(
                        fn (FormattedColumn $column) => sprintf(
                            '%s #%s',
                            class_basename($column->getItem()->tokenable_type),
                            $column->getItem()->tokenable_id
                        )
                    ),
                FormattedColumn::make('abilities')
                    ->label(trans('plugins/sanctum-token::sanctum-token.abilities'))
                    ->getValueUsing(fn (FormattedColumn $column, $value) => implode(', ', (array) $value)),
                DateTimeColumn::make('last_used_at')
                    ->label(trans('plugins/sanctum-token::sanctum-token.last_used_at'))
                    ->withEmptyState(),
                DateTimeColumn::make('expires_at')
                    ->label(trans('plugins/sanctum-token::sanctum-token.expires_at'))
                    ->withEmptyState(trans('plugins/sanctum-token::sanctum-token.never')),
                CreatedAtColumn::make(),
            ])
            ->addBulkAction(DeleteTokensBulkAction::make()->permission('sanctum-token.destroy'))
            ->queryUsing(fn (Builder $query) => $query
                ->manageableBy(auth()->user())
                ->select([
                    'id',
                    'name',
                    'tokenable_type',
                    'tokenable_id',
                    'abilities',
                    'last_used_at',
                    'expires_at',
                    'created_at',
                ]));
    }
}
