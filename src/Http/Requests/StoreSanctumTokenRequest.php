<?php

namespace Datlechin\SanctumToken\Http\Requests;

use Botble\Support\Http\Requests\Request;

class StoreSanctumTokenRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => 'required',
            'abilities' => 'nullable|array',
        ];
    }
}
