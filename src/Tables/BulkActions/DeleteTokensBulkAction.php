<?php

namespace FriendsOfBotble\SanctumToken\Tables\BulkActions;

use Botble\Base\Contracts\BaseModel;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Table\BulkActions\DeleteBulkAction;
use FriendsOfBotble\SanctumToken\Models\PersonalAccessToken;
use Illuminate\Database\Eloquent\Model;

class DeleteTokensBulkAction extends DeleteBulkAction
{
    public function dispatch(BaseModel|Model $model, array $ids): BaseHttpResponse
    {
        $ids = PersonalAccessToken::query()
            ->manageableBy(auth()->user())
            ->whereKey($ids)
            ->pluck('id')
            ->all();

        return parent::dispatch($model, $ids);
    }
}
