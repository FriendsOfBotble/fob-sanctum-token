<?php

namespace Datlechin\SanctumToken\Tables;

use BaseHelper;
use Botble\Table\Abstracts\TableAbstract;
use Datlechin\SanctumToken\Repositories\Interfaces\SanctumTokenInterface;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Relations\Relation as EloquentRelation;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class SanctumTokenTable extends TableAbstract
{
    protected $view = 'plugins/sanctum-token::table';

    public function __construct(
        DataTables $table,
        UrlGenerator $urlGenerator,
        SanctumTokenInterface $sanctumTokenRepository,
        protected $hasActions = true,
        protected $hasFilter = true
    ) {
        parent::__construct($table, $urlGenerator);

        $this->repository = $sanctumTokenRepository;

        if (! Auth::user()->hasPermission('sanctum-token.destroy')) {
            $this->hasActions = false;
            $this->hasFilter = false;
        }
    }

    /**
     * @throws \Exception
     */
    public function ajax(): JsonResponse
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('checkbox', fn ($item) => $this->getCheckbox($item->id))
            ->editColumn('name', fn ($item) => $item->name)
            ->editColumn('last_used_at', fn ($item) => $item->last_used_at?->diffForHumans())
            ->editColumn('created_at', fn ($item) => BaseHelper::formatDateTime($item->created_at))
            ->addColumn('operations', fn ($item) => $this->getOperations(null, 'sanctum-token.destroy', $item));

        return $this->toJson($data);
    }

    public function query(): EloquentBuilder|QueryBuilder|EloquentRelation
    {
        $query = $this->repository
            ->getModel()
            ->select([
                'id',
                'name',
                'abilities',
                'last_used_at',
                'created_at'
            ]);

        return $this->applyScopes($query);
    }

    public function columns(): array
    {
        return [
            'id' => [
                'title' => __('core/base::tables.id')
            ],
            'name' => [
                'title' => __('core/base::tables.name')
            ],
            'abilities' => [
                'title' => __('plugins/sanctum-token::sanctum-token.abilities')
            ],
            'last_used_at' => [
                'title' => __('plugins/sanctum-token::sanctum-token.last_used_at')
            ],
            'created_at' => [
                'title' => __('core/base::tables.created_at')
            ],
        ];
    }

    public function buttons(): array
    {
        return $this->addCreateButton(route('sanctum-token.create'));
    }

    public function bulkActions(): array
    {
        return $this->addDeleteAction(
            route('sanctum-token.deletes'),
            'sanctum-token.destroy',
            parent::bulkActions()
        );
    }
}
