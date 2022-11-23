<?php

namespace Datlechin\SanctumToken\Http\Controllers;

use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Base\Traits\HasDeleteManyItemsTrait;
use Datlechin\SanctumToken\Forms\SanctumTokenForm;
use Datlechin\SanctumToken\Http\Requests\StoreSanctumTokenRequest;
use Datlechin\SanctumToken\Repositories\Interfaces\SanctumTokenInterface;
use Datlechin\SanctumToken\Tables\SanctumTokenTable;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SanctumTokenController extends BaseController
{
    use HasDeleteManyItemsTrait;

    public function __construct(protected SanctumTokenInterface $sanctumTokenRepository)
    {
    }

    public function index(SanctumTokenTable $dataTable): JsonResponse|View|string
    {
        page_title()->setTitle(__('plugins/sanctum-token::sanctum-token.name'));

        return $dataTable->renderTable();
    }

    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/sanctum-token::sanctum-token.create'));

        return $formBuilder
            ->create(SanctumTokenForm::class)
            ->renderForm();
    }

    public function store(StoreSanctumTokenRequest $request, BaseHttpResponse $response)
    {
        $accessToken = $request->user()->createToken($request->input('name'));

        event(new CreatedContentEvent(SANCTUM_TOKEN_MODULE_SCREEN_NAME, $request, $accessToken));

        session()->flash('plainTextToken', $accessToken->plainTextToken);

        return $response
            ->setPreviousUrl(route('sanctum-token.index'))
            ->setNextUrl(route('sanctum-token.index'))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    public function destroy(Request $request, $id, BaseHttpResponse $response)
    {
        try {
            $personalAccessToken = $this->sanctumTokenRepository->findOrFail($id);

            $this->sanctumTokenRepository->delete($personalAccessToken);

            event(new DeletedContentEvent(SANCTUM_TOKEN_MODULE_SCREEN_NAME, $request, $personalAccessToken));

            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }

    public function deletes(Request $request, BaseHttpResponse $response)
    {

        return $this->executeDeleteItems(
            $request,
            $response,
            $this->sanctumTokenRepository,
            SANCTUM_TOKEN_MODULE_SCREEN_NAME
        );
    }
}
