<?php

namespace GuestCMS\Api\Forms\Settings;

use GuestCMS\Api\Facades\ApiHelper;
use GuestCMS\Api\Http\Requests\ApiSettingRequest;
use GuestCMS\Base\Forms\FieldOptions\OnOffFieldOption;
use GuestCMS\Base\Forms\Fields\OnOffCheckboxField;
use GuestCMS\Setting\Forms\SettingForm;

class ApiSettingForm extends SettingForm
{
    public function setup(): void
    {
        parent::setup();

        $this
            ->setValidatorClass(ApiSettingRequest::class)
            ->setSectionTitle(trans('packages/api::api.setting_title'))
            ->setSectionDescription(trans('packages/api::api.setting_description'))
            ->contentOnly()
            ->add(
                'api_enabled',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('packages/api::api.api_enabled'))
                    ->value(ApiHelper::enabled())
                    ->toArray()
            );
    }
}
