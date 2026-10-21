<?php

namespace FriendsOfBotble\SanctumToken\Models;

use Botble\Base\Contracts\BaseModel;
use Botble\Base\Models\Concerns\HasBaseEloquentBuilder;
use Botble\Base\Models\Concerns\HasMetadata;
use Botble\Base\Models\Concerns\HasUuidsOrIntegerIds;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;

class PersonalAccessToken extends SanctumPersonalAccessToken implements BaseModel
{
    use HasMetadata;
    use HasUuidsOrIntegerIds;
    use HasBaseEloquentBuilder;

    /**
     * Super admins manage every token (including the ones issued to customers through the API),
     * other admins only manage the tokens they created for themselves.
     */
    public function scopeManageableBy(Builder $query, ?Authenticatable $user): Builder
    {
        if (! $user) {
            return $query->whereRaw('1 = 0');
        }

        if (method_exists($user, 'isSuperUser') && $user->isSuperUser()) {
            return $query;
        }

        return $query
            ->where('tokenable_type', $user instanceof Model ? $user->getMorphClass() : $user::class)
            ->where('tokenable_id', $user->getAuthIdentifier());
    }
}
