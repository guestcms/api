<?php

namespace GuestCMS\Api\Forms;

use GuestCMS\Api\Http\Requests\StoreSanctumTokenRequest;
use GuestCMS\Api\Models\PersonalAccessToken;
use GuestCMS\Base\Forms\FieldOptions\NameFieldOption;
use GuestCMS\Base\Forms\Fields\TextField;
use GuestCMS\Base\Forms\FormAbstract;

class SanctumTokenForm extends FormAbstract
{
    public function buildForm(): void
    {
        $this
            ->setupModel(new PersonalAccessToken())
            ->setValidatorClass(StoreSanctumTokenRequest::class)
            ->add('name', TextField::class, NameFieldOption::make()->toArray());
    }
}
