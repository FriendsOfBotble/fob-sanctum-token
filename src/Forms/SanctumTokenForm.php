<?php

namespace FriendsOfBotble\SanctumToken\Forms;

use Botble\Base\Forms\FieldOptions\DatePickerFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\DatePickerField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use FriendsOfBotble\SanctumToken\Http\Requests\StoreSanctumTokenRequest;
use FriendsOfBotble\SanctumToken\Models\PersonalAccessToken;

class SanctumTokenForm extends FormAbstract
{
    public function buildForm(): void
    {
        $this
            ->setupModel(new PersonalAccessToken())
            ->setValidatorClass(StoreSanctumTokenRequest::class)
            ->add('name', TextField::class, TextFieldOption::make()->label(__('core/base::tables.name'))->required())
            ->add(
                'expires_at',
                DatePickerField::class,
                DatePickerFieldOption::make()
                    ->label(trans('plugins/sanctum-token::sanctum-token.expires_at'))
                    ->helperText(trans('plugins/sanctum-token::sanctum-token.expires_at_helper'))
                    ->defaultValue(null)
            );
    }
}
