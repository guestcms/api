<?php

namespace GuestCMS\Api\Models;

use GuestCMS\Base\Contracts\BaseModel;
use GuestCMS\Base\Models\Concerns\HasBaseEloquentBuilder;
use GuestCMS\Base\Models\Concerns\HasMetadata;
use GuestCMS\Base\Models\Concerns\HasUuidsOrIntegerIds;
use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;

class PersonalAccessToken extends SanctumPersonalAccessToken implements BaseModel
{
    use HasMetadata;
    use HasUuidsOrIntegerIds;
    use HasBaseEloquentBuilder;
}
