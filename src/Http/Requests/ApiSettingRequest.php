<?php

namespace GuestCMS\Api\Http\Requests;

use GuestCMS\Base\Rules\OnOffRule;
use GuestCMS\Support\Http\Requests\Request;

class ApiSettingRequest extends Request
{
    public function rules(): array
    {
        return [
            'api_enabled' => [new OnOffRule()],
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'api_enabled' => [
                'description' => 'Enable or disable the API',
                'example' => 'on',
            ],
        ];
    }
}
