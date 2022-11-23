@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    @if(session()->has('plainTextToken'))
        <div class="note note-success">
            {{ __('This is your new personal access token, this token only show 1 time, make sure you have copied it.') }}
            <div class="alert alert-warning mt-3">
                <span id="token" class="fw-bold">{{ session('plainTextToken') }}</span>
                <span class="badge badge-success cursor-pointer" onclick="copyToClipboard()">
                    <i class="fas fa-copy"></i>
                </span>
            </div>
        </div>

        <script>
            function copyToClipboard()  {
                let plainTextToken = document.getElementById('token').innerText;
                const input = document.createElement('input');
                input.value = plainTextToken;
                document.body.appendChild(input);
                input.select();
                document.execCommand('copy');
                document.body.removeChild(input);
                toastr.info('{{ __('Copy token to clipboard successfully!') }}')
            }
        </script>
    @endif
    @include('core/table::base-table')
@stop
