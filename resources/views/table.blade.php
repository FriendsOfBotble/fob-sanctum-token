@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    @if(session()->has('plainTextToken'))
        <x-core::alert
            type="success"
            :title="__('This is your new personal access token, this token only show 1 time, make sure you have copied it.')"
        >
            <div class="d-flex align-items-center gap-1 mt-2">
                <code>{{ session('plainTextToken') }}</code>

                <a
                    href="javascript:void(0);"
                    data-bb-toggle="clipboard"
                    data-clipboard-action="copy"
                    data-clipboard-text="{{ session('plainTextToken') }}"
                    data-clipboard-message="{{ trans('core/table::table.copied') }}"
                    data-bs-toggle="tooltip"
                    title="{{ trans('core/table::table.copy') }}"
                    class="text-muted text-center text-decoration-none"
                >
                    <span class="sr-only">{{ trans('core/table::table.copy') }}</span>
                    <x-core::icon name="ti ti-clipboard" />
                </a>
            </div>
        </x-core::alert>
    @endif

    @include('core/table::base-table')
@stop
