<?php

namespace Datlechin\SanctumToken\Forms;

use Botble\Base\Forms\FormAbstract;
use Datlechin\SanctumToken\Http\Requests\StoreSanctumTokenRequest;
use Datlechin\SanctumToken\Models\PersonalAccessToken;

class SanctumTokenForm extends FormAbstract
{
    public function buildForm(): FormAbstract|SanctumTokenForm
    {
        return $this
            ->setupModel(new PersonalAccessToken())
            ->setValidatorClass(StoreSanctumTokenRequest::class)
            ->add('name', 'text', [
                'label' => __('core/base::tables.name'),
            ]);
    }
}
