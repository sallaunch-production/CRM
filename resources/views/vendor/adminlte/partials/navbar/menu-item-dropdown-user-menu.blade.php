@php( $logout_url = View::getSection('logout_url') ?? config('adminlte.logout_url', 'logout') )
@php( $profile_url = View::getSection('profile_url') ?? config('adminlte.profile_url', 'logout') )

@if (config('adminlte.usermenu_profile_url', false))
    @php( $profile_url = Auth::user()->adminlte_profile_url() )
@endif

@if (config('adminlte.use_route_url', false))
    @php( $profile_url = $profile_url ? route($profile_url) : '' )
    @php( $logout_url = $logout_url ? route($logout_url) : '' )
@else
    @php( $profile_url = $profile_url ? url($profile_url) : '' )
    @php( $logout_url = $logout_url ? url($logout_url) : '' )
@endif

<li class="nav-item dropdown user-menu">

    {{-- User menu toggler --}}
    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
        @if(config('adminlte.usermenu_image'))
            <img src="{{ Auth::user()->adminlte_image() }}"
                 class="user-image img-circle elevation-2"
                 alt="{{ Auth::user()->name }}">
        @endif
        <span @if(config('adminlte.usermenu_image')) class="d-none d-md-inline" @endif>
            {{ Auth::user()->name }}
        </span>
    </a>

    {{-- User menu dropdown --}}
    <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">

        {{-- User menu header --}}
        @if(!View::hasSection('usermenu_header') && config('adminlte.usermenu_header'))
            <li class="user-header {{ config('adminlte.usermenu_header_class', 'bg-primary') }}
                @if(!config('adminlte.usermenu_image')) h-auto @endif">
                @if(config('adminlte.usermenu_image'))
                    <img src="{{ Auth::user()->adminlte_image() }}"
                         class="img-circle elevation-2"
                         alt="{{ Auth::user()->name }}">
                @endif
                <p class="@if(!config('adminlte.usermenu_image')) mt-0 @endif">
                    {{ Auth::user()->name }}
                    @if(config('adminlte.usermenu_desc'))
                        <small>{{ Auth::user()->adminlte_desc() }}</small>
                    @endif
                </p>
            </li>
        @else
            @yield('usermenu_header')
        @endif

        {{-- Configured user menu links --}}
        @each('adminlte::partials.navbar.dropdown-item', $adminlte->menu("navbar-user"), 'item')

        {{-- User menu body --}}
        @hasSection('usermenu_body')
            <li class="user-body">
                @yield('usermenu_body')
            </li>
        @endif

        {{-- User menu footer --}}
        <li class="user-footer">
            @if($profile_url)
                <a href="{{ $profile_url }}" class="btn btn-default btn-flat">
                    <i class="fa fa-fw fa-user text-lightblue"></i>
                    {{ __('adminlte::menu.profile') }}
                </a>
            @endif
            <a class="btn btn-default btn-flat float-right @if(!$profile_url) btn-block @endif"
               href="#"
               id="btnLogoutTrigger"
               onclick="event.preventDefault(); window.showLogoutConfirmModal();">
                <i class="fa fa-fw fa-power-off text-red"></i>
                {{ __('adminlte::adminlte.log_out') }}
            </a>
            <form id="logout-form" action="{{ $logout_url }}" method="POST" style="display: none;">
                @if(config('adminlte.logout_method'))
                    {{ method_field(config('adminlte.logout_method')) }}
                @endif
                {{ csrf_field() }}
            </form>
        </li>

    </ul>

</li>

{{-- Logout confirmation modal (dipindah ke body via JS) --}}
<style>
    #logoutConfirmModal .modal-dialog {
        max-width: 400px;
    }
    #logoutConfirmModal .modal-content {
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.12);
        overflow: hidden;
    }
    #logoutConfirmModal .logout-modal-body {
        padding: 1.75rem 1.5rem 1.5rem;
        text-align: center;
    }
    #logoutConfirmModal .logout-modal-icon {
        width: 56px;
        height: 56px;
        margin: 0 auto 1rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fde8e9;
        color: #e31c23;
        font-size: 1.35rem;
    }
    #logoutConfirmModal .logout-modal-title {
        margin: 0 0 0.4rem;
        font-size: 1.15rem;
        font-weight: 700;
        color: #0f172a;
    }
    #logoutConfirmModal .logout-modal-subtitle {
        margin: 0 0 1.4rem;
        font-size: 0.92rem;
        color: #64748b;
        line-height: 1.45;
    }
    #logoutConfirmModal .logout-modal-actions {
        display: flex;
        gap: 0.65rem;
        justify-content: center;
    }
    #logoutConfirmModal .logout-modal-actions .btn {
        min-width: 110px;
        border-radius: 10px;
        font-weight: 600;
        padding: 0.55rem 1rem;
    }
    #logoutConfirmModal .btn-logout-cancel {
        background: #e2e8f0;
        border-color: #e2e8f0;
        color: #334155;
    }
    #logoutConfirmModal .btn-logout-cancel:hover {
        background: #cbd5e1;
        border-color: #cbd5e1;
        color: #0f172a;
    }
    #logoutConfirmModal .btn-logout-confirm {
        background: #e31c23;
        border-color: #e31c23;
        color: #fff;
    }
    #logoutConfirmModal .btn-logout-confirm:hover {
        background: #c4151c;
        border-color: #c4151c;
        color: #fff;
    }
    #logoutConfirmModal .btn-logout-close {
        position: absolute;
        top: 0.75rem;
        right: 0.85rem;
        border: 0;
        background: transparent;
        color: #94a3b8;
        font-size: 1.25rem;
        line-height: 1;
        padding: 0.15rem;
        cursor: pointer;
    }
    #logoutConfirmModal .btn-logout-close:hover {
        color: #475569;
    }
    @media (max-width: 420px) {
        #logoutConfirmModal .logout-modal-actions {
            flex-direction: column;
        }
        #logoutConfirmModal .logout-modal-actions .btn {
            width: 100%;
        }
    }
</style>

<div class="modal fade" id="logoutConfirmModal" tabindex="-1" role="dialog" aria-labelledby="logoutConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content position-relative">
            <button type="button" class="btn-logout-close" data-dismiss="modal" aria-label="Tutup">&times;</button>
            <div class="logout-modal-body">
                <div class="logout-modal-icon" aria-hidden="true">
                    <i class="fas fa-sign-out-alt"></i>
                </div>
                <h5 class="logout-modal-title" id="logoutConfirmModalLabel">Keluar dari CRM?</h5>
                <p class="logout-modal-subtitle">
                    Apakah anda yakin untuk keluar dari sesi saat ini ?
                </p>
                <div class="logout-modal-actions">
                    <button type="button" class="btn btn-logout-cancel" data-dismiss="modal">Batal</button>
                    <button type="button"
                            class="btn btn-logout-confirm"
                            onclick="document.getElementById('logout-form').submit();">
                        Logout
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    window.showLogoutConfirmModal = function () {
        var modal = document.getElementById('logoutConfirmModal');
        if (modal && modal.parentElement !== document.body) {
            document.body.appendChild(modal);
        }
        if (window.jQuery) {
            jQuery('.dropdown-menu').removeClass('show');
            jQuery('#logoutConfirmModal').modal('show');
        }
    };
</script>
