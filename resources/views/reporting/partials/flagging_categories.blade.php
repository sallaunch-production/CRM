@php
    $flagSuffix = (($levelnya ?? '') === 'operasional') ? '_operasional' : '';
    $flagRoute = function (string $name) use ($flagSuffix) {
        return route($name.$flagSuffix);
    };

    $currentPath = trim(request()->path(), '/');
    $activeAliases = [
        'filtering_transaction' => ['filtering_transaction', 'search_filtering_transaction'],
        'filtering_identitas' => ['filtering_identitas', 'search_filtering_identitas', 'detail_identitas'],
        'filter_telepon' => ['filter_telepon', 'search_filtering_telepon', 'detail_telpon'],
        'filter_negara' => ['filter_negara', 'search_filtering_negara', 'detail_negara'],
        'filter_sender' => ['filter_sender', 'search_filtering_sender', 'detail_sender'],
        'filter_one_to_many' => ['filter_one_to_many', 'search_filtering_one_to_many', 'detail_one_to_many'],
        'filter_many_to_one' => ['filter_many_to_one', 'search_filtering_many_to_one', 'detail_many_to_one'],
    ];
    $isActive = function (string $name) use ($flagSuffix, $currentPath, $activeAliases) {
        foreach (($activeAliases[$name] ?? [$name]) as $alias) {
            if ($currentPath === trim($alias.$flagSuffix, '/')) {
                return true;
            }
        }

        return false;
    };

    $categories = [
        [
            'route' => 'filtering_transaction',
            'label' => 'Semua',
            'hint' => 'Semua flag',
            'img' => 'vendor/adminlte/dist/img/people.jpg',
            'icon' => 'fas fa-layer-group',
        ],
        [
            'route' => 'filtering_identitas',
            'label' => 'Identitas',
            'hint' => 'By nomor identitas',
            'img' => 'vendor/adminlte/dist/img/card.png',
            'icon' => 'fas fa-id-card',
        ],
        [
            'route' => 'filter_telepon',
            'label' => 'Telephone',
            'hint' => 'By telepon',
            'img' => 'vendor/adminlte/dist/img/telepon_penerima.png',
            'icon' => 'fas fa-phone',
        ],
        [
            'route' => 'filter_negara',
            'label' => 'Negara',
            'hint' => 'By negara',
            'img' => 'vendor/adminlte/dist/img/passport.png',
            'icon' => 'fas fa-passport',
        ],
        [
            'route' => 'filter_sender',
            'label' => 'Pengirim',
            'hint' => 'By pengirim',
            'img' => 'vendor/adminlte/dist/img/sender.png',
            'icon' => 'fas fa-user',
        ],
        [
            'route' => 'filter_one_to_many',
            'label' => 'One to Many',
            'hint' => '1 ID pengirim → banyak penerima',
            'img' => 'vendor/adminlte/dist/img/one-to-many.png',
            'icon' => 'fas fa-share-alt',
            'accent' => 'otm',
        ],
        [
            'route' => 'filter_many_to_one',
            'label' => 'Many to One',
            'hint' => 'Banyak pengirim → 1 penerima',
            'img' => 'vendor/adminlte/dist/img/many-to-one.png',
            'icon' => 'fas fa-compress-arrows-alt',
            'accent' => 'mto',
        ],
    ];
@endphp

<style>
    :root {
        --arsema-red: #e31c23;
        --arsema-red-hover: #c4151c;
        --arsema-red-soft: rgba(227, 28, 35, 0.12);
    }
    .flag-cat-wrap { margin-bottom: 1.25rem; }
    .flag-cat-title {
        font-size: 0.95rem;
        font-weight: 600;
        color: #334155;
        margin-bottom: 0.75rem;
        letter-spacing: 0.01em;
    }
    .flag-cat-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(118px, 1fr));
        gap: 0.75rem;
        max-width: 980px;
    }
    .flag-cat-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-start;
        text-align: center;
        text-decoration: none !important;
        color: #1e293b !important;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 0.85rem 0.55rem 0.7rem;
        min-height: 128px;
        transition: border-color .15s ease, box-shadow .15s ease, transform .15s ease, background .15s ease;
    }
    .flag-cat-card:hover {
        background: #fff;
        border-color: #f1a3a6;
        box-shadow: 0 6px 16px rgba(227, 28, 35, 0.08);
        transform: translateY(-1px);
    }
    .flag-cat-card.is-active {
        background: #fff;
        border-color: var(--arsema-red);
        box-shadow: 0 0 0 3px var(--arsema-red-soft);
    }
    .flag-cat-card.is-active.accent-otm,
    .flag-cat-card.is-active.accent-mto {
        border-color: var(--arsema-red);
        box-shadow: 0 0 0 3px var(--arsema-red-soft);
    }
    .flag-cat-icon {
        width: 52px;
        height: 52px;
        object-fit: contain;
        margin-bottom: 0.45rem;
        border-radius: 8px;
        background: #fff;
        padding: 4px;
    }
    .flag-cat-label {
        font-size: 0.82rem;
        font-weight: 700;
        line-height: 1.2;
        margin-bottom: 0.2rem;
    }
    .flag-cat-card.is-active .flag-cat-label {
        color: var(--arsema-red);
    }
    .flag-cat-hint {
        font-size: 0.68rem;
        color: #64748b;
        line-height: 1.25;
        margin: 0;
        max-width: 100%;
    }
    .flag-filter-bar {
        display: flex;
        flex-wrap: wrap;
        align-items: flex-end;
        gap: 0.75rem 1rem;
        background: linear-gradient(180deg, #fff 0%, #fff7f7 100%);
        border: 1px solid #f0d0d2;
        border-radius: 12px;
        padding: 1rem 1.1rem;
        max-width: 780px;
    }
    .flag-filter-bar .flag-filter-field {
        flex: 1 1 200px;
        margin: 0;
        min-width: 180px;
    }
    .flag-filter-bar label {
        display: block;
        font-size: 0.8rem;
        font-weight: 600;
        color: #475569;
        margin-bottom: 0.35rem;
    }
    .flag-filter-bar .form-control {
        border-radius: 8px;
        border-color: #cbd5e1;
        height: 40px;
    }
    .flag-filter-bar .form-control:focus {
        border-color: var(--arsema-red);
        box-shadow: 0 0 0 0.2rem var(--arsema-red-soft);
    }
    .flag-filter-bar .btn,
    .btn-arsema {
        height: 40px;
        border-radius: 8px;
        padding-left: 1.15rem;
        padding-right: 1.15rem;
        font-weight: 600;
        background: var(--arsema-red);
        border-color: var(--arsema-red);
        color: #fff;
    }
    .flag-filter-bar .btn:hover,
    .btn-arsema:hover {
        background: var(--arsema-red-hover);
        border-color: var(--arsema-red-hover);
        color: #fff;
    }
    .flag-section-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0 0 0.25rem;
    }
    .flag-section-sub {
        color: #64748b;
        font-size: 0.9rem;
        margin-bottom: 0.9rem;
    }
    .flag-count-badge {
        display: inline-block;
        background: var(--arsema-red);
        color: #fff;
        font-size: 0.8rem;
        font-weight: 600;
        border-radius: 999px;
        padding: 0.4rem 0.85rem;
    }
    .flag-id-link {
        display: inline;
        padding: 0;
        margin: 0;
        border: 0;
        background: none;
        color: #1e293b;
        font-weight: 600;
        font-size: inherit;
        line-height: inherit;
        text-decoration: underline;
        text-underline-offset: 2px;
        text-decoration-color: #94a3b8;
        cursor: pointer;
    }
    .flag-id-link:hover,
    .flag-id-link:focus {
        color: #0f172a;
        text-decoration-color: #475569;
        outline: none;
    }
    .flag-id-text {
        color: #1e293b;
        font-weight: 700;
    }
    .flag-count-pill {
        display: inline-block;
        min-width: 1.75rem;
        text-align: center;
        background: transparent;
        color: #1e293b;
        border: 0;
        border-radius: 0;
        font-weight: 600;
        font-size: inherit;
        padding: 0;
    }
    .btn-arsema-outline {
        color: var(--arsema-red);
        border: 1px solid var(--arsema-red);
        background: #fff;
        border-radius: 8px;
        font-weight: 600;
    }
    .btn-arsema-outline:hover {
        background: var(--arsema-red);
        color: #fff;
        border-color: var(--arsema-red);
    }
    @media (max-width: 576px) {
        .flag-cat-grid { grid-template-columns: repeat(2, 1fr); }
    }
</style>

<div class="flag-cat-wrap">
    <div class="flag-cat-title">Kategori</div>
    <div class="flag-cat-grid" role="navigation" aria-label="Kategori flagging">
        @foreach($categories as $cat)
            @php
                $active = $isActive($cat['route']);
                $accent = $cat['accent'] ?? '';
            @endphp
            <a
                href="{{ $flagRoute($cat['route']) }}"
                class="flag-cat-card {{ $active ? 'is-active' : '' }} {{ $accent ? 'accent-'.$accent : '' }}"
                title="{{ $cat['hint'] }}"
            >
                <img src="{{ asset($cat['img']) }}" alt="{{ $cat['label'] }}" class="flag-cat-icon" loading="lazy">
                <div class="flag-cat-label">{{ $cat['label'] }}</div>
                <p class="flag-cat-hint">{{ $cat['hint'] }}</p>
            </a>
        @endforeach
    </div>
</div>
