<?php

namespace FriendsOfBotble\SanctumToken\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Exception;
use FriendsOfBotble\SanctumToken\Forms\SanctumTokenForm;
use FriendsOfBotble\SanctumToken\Http\Requests\StoreSanctumTokenRequest;
use FriendsOfBotble\SanctumToken\Models\PersonalAccessToken;
use FriendsOfBotble\SanctumToken\Tables\SanctumTokenTable;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

class SanctumTokenController extends BaseController
{
    public function index(SanctumTokenTable $sanctumTokenTable): JsonResponse|View
    {
        $this->pageTitle(__('plugins/sanctum-token::sanctum-token.name'));

        return $sanctumTokenTable->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('plugins/sanctum-token::sanctum-token.create'));

        return SanctumTokenForm::create()->renderForm();
    }

    public function store(StoreSanctumTokenRequest $request): BaseHttpResponse
    {
        $accessToken = $request->user()->createToken($request->input('name'));

        session()->flash('plainTextToken', $accessToken->plainTextToken);

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('sanctum-token.index'))
            ->setNextUrl(route('sanctum-token.index'))
            ->withCreatedSuccessMessage();
    }

    public function destroy(string $id): BaseHttpResponse
    {
        try {
            PersonalAccessToken::findOrFail($id)->delete();

            return $this
                ->httpResponse()
                ->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }
}
