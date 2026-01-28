@if (session()->has('error'))
    <style>
        .toast-progress {
            height: 3px;
            width: 100%;
            animation: shrink 3s linear forwards;
        }
    </style>
    <div class="toast-container position-fixed top-0 end-0 p-3 pt-8" style="margin-top: 7.5px;">
        <div class="toast fade show mb-5" id="toast-simple" role="alert" aria-live="assertive" aria-atomic="true"
            data-bs-autohide="false">
            <div class="toast-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center text-danger">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round"
                            class="icon icon-tabler icons-tabler-outline icon-tabler-exclamation-circle">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                            <path d="M12 9v4" />
                            <path d="M12 16v.01" />
                        </svg>&ensp;
                        <span class="me-auto">{{ session('error') }}</span>
                    </div>
                    <button type="button" class="ms-2 btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        </div>
    </div>
@endif