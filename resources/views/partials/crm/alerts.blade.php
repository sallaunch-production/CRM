@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" id="success-alert" role="alert">
        <strong>{{ session('success') }}</strong>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong>{{ session('error') }}</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
