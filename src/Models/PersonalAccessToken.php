<?php

namespace Datlechin\SanctumToken\Models;

use Botble\Base\Contracts\BaseModel;
use Botble\Base\Models\Concerns\HasBaseEloquentBuilder;
use Botble\Base\Models\Concerns\HasMetadata;
use Botble\Base\Models\Concerns\HasUuidsOrIntegerIds;
use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;

class PersonalAccessToken extends SanctumPersonalAccessToken implements BaseModel
{
    use HasMetadata;
    use HasUuidsOrIntegerIds;
    use HasBaseEloquentBuilder;
}
