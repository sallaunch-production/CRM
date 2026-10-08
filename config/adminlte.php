<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    |
    | Here you can change the default title of your admin panel.
    |
    | For detailed instructions you can look the title section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'title' => 'CRM Arsema',
    'title_prefix' => '',
    'title_postfix' => '',

    /*
    |--------------------------------------------------------------------------
    | Favicon
    |--------------------------------------------------------------------------
    |
    | Here you can activate the favicon.
    |
    | For detailed instructions you can look the favicon section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'use_ico_only' => true,
    'use_full_favicon' => false,

    /*
    |--------------------------------------------------------------------------
    | Google Fonts
    |--------------------------------------------------------------------------
    |
    | Here you can allow or not the use of external google fonts. Disabling the
    | google fonts may be useful if your admin panel internet access is
    | restricted somehow.
    |
    | For detailed instructions you can look the google fonts section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'google_fonts' => [
        'allowed' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Logo
    |--------------------------------------------------------------------------
    |
    | Here you can change the logo of your admin panel.
    |
    | For detailed instructions you can look the logo section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'logo' => '<b>CRM Arsema</b>',
    'logo_img' => 'vendor/adminlte/dist/img/logo arsema.png',
    'logo_img_class' => 'brand-image img-circle elevation-3',
    'logo_img_xl' => null,
    'logo_img_xl_class' => 'brand-image-xs',
    'logo_img_alt' => 'Admin Logo',

    /*
    |--------------------------------------------------------------------------
    | Authentication Logo
    |--------------------------------------------------------------------------
    |
    | Here you can setup an alternative logo to use on your login and register
    | screens. When disabled, the admin panel logo will be used instead.
    |
    | For detailed instructions you can look the auth logo section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'auth_logo' => [
        'enabled' => false,
        'img' => [
            'path' => 'vendor/adminlte/dist/img/logo arsema.png',
            'alt' => 'Auth Logo',
            'class' => '',
            'width' => 50,
            'height' => 50,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Preloader Animation
    |--------------------------------------------------------------------------
    |
    | Here you can change the preloader animation configuration.
    |
    | For detailed instructions you can look the preloader section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'preloader' => [
        'enabled' => true,
        'img' => [
            'path' => 'vendor/adminlte/dist/img/logo arsema.png',
            'alt' => 'AdminLTE Preloader Image',
            'effect' => 'animation__shake',
            'width' => 60,
            'height' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Menu
    |--------------------------------------------------------------------------
    |
    | Here you can activate and change the user menu.
    |
    | For detailed instructions you can look the user menu section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'usermenu_enabled' => true,
    'usermenu_header' => false,
    'usermenu_header_class' => 'bg-primary',
    'usermenu_image' => false,
    'usermenu_desc' => false,
    'usermenu_profile_url' => false,

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | Here we change the layout of your admin panel.
    |
    | For detailed instructions you can look the layout section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'layout_topnav' => null,
    'layout_boxed' => null,
    'layout_fixed_sidebar' => null,
    'layout_fixed_navbar' => null,
    'layout_fixed_footer' => null,
    'layout_dark_mode' => null,

    /*
    |--------------------------------------------------------------------------
    | Authentication Views Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the authentication views.
    |
    | For detailed instructions you can look the auth classes section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'classes_auth_card' => 'card-outline card-primary',
    'classes_auth_header' => '',
    'classes_auth_body' => '',
    'classes_auth_footer' => '',
    'classes_auth_icon' => '',
    'classes_auth_btn' => 'btn-flat btn-primary',

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the admin panel.
    |
    | For detailed instructions you can look the admin panel classes here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'classes_body' => '',
    'classes_brand' => '',
    'classes_brand_text' => '',
    'classes_content_wrapper' => '',
    'classes_content_header' => '',
    'classes_content' => '',
    'classes_sidebar' => 'sidebar-dark-primary elevation-4',
    'classes_sidebar_nav' => '',
    'classes_topnav' => 'navbar-white navbar-light',
    'classes_topnav_nav' => 'navbar-expand',
    'classes_topnav_container' => 'container',

    /*
    |--------------------------------------------------------------------------
    | Sidebar
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar of the admin panel.
    |
    | For detailed instructions you can look the sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'sidebar_mini' => 'lg',
    'sidebar_collapse' => false,
    'sidebar_collapse_auto_size' => false,
    'sidebar_collapse_remember' => false,
    'sidebar_collapse_remember_no_transition' => true,
    'sidebar_scrollbar_theme' => 'os-theme-light',
    'sidebar_scrollbar_auto_hide' => 'l',
    'sidebar_nav_accordion' => true,
    'sidebar_nav_animation_speed' => 300,

    /*
    |--------------------------------------------------------------------------
    | Control Sidebar (Right Sidebar)
    |--------------------------------------------------------------------------
    |
    | Here we can modify the right sidebar aka control sidebar of the admin panel.
    |
    | For detailed instructions you can look the right sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'right_sidebar' => false,
    'right_sidebar_icon' => 'fas fa-cogs',
    'right_sidebar_theme' => 'dark',
    'right_sidebar_slide' => true,
    'right_sidebar_push' => true,
    'right_sidebar_scrollbar_theme' => 'os-theme-light',
    'right_sidebar_scrollbar_auto_hide' => 'l',

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    |
    | Here we can modify the url settings of the admin panel.
    |
    | For detailed instructions you can look the urls section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'use_route_url' => false,
    'dashboard_url' => '#',
    'logout_url' => 'logout',
    'login_url' => 'login',
    'register_url' => 'register',
    'password_reset_url' => 'password/reset',
    'password_email_url' => 'password/email',
    'profile_url' => false,

    /*
    |--------------------------------------------------------------------------
    | Laravel Mix
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Laravel Mix option for the admin panel.
    |
    | For detailed instructions you can look the laravel mix section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    |
    */

    'enabled_laravel_mix' => false,
    'laravel_mix_css_path' => 'css/app.css',
    'laravel_mix_js_path' => 'js/app.js',

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar/top navigation of the admin panel.
    |
    | For detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
    */
    
    'menu' => [
        [
            'type'         => 'fullscreen-widget',
            'topnav_right' => true,
        ],

        // —— Main ——
        ['header' => 'MAIN'],
        [
            'text' => 'Home',
            'url'  => 'dashboard',
            'icon' => 'fas fa-fw fa-home',
            'can'  => ['compliance-only'],
        ],
        [
            'text' => 'Home',
            'url'  => 'dashboard_operasional',
            'icon' => 'fas fa-fw fa-home',
            'can'  => ['operasional-only'],
        ],

        // —— Transactions ——
        ['header' => 'TRANSACTIONS'],
        [
            'text' => 'POS Transactions',
            'icon' => 'fas fa-fw fa-cash-register',
            'submenu' => [
                [
                    'text'   => 'Receive',
                    'url'    => 'transaksi_receive_pos',
                    'shift'  => 'ml-3',
                    'can'    => ['compliance-only'],
                    'icon'   => 'fas fa-fw fa-circle',
                    'active' => ['transaksi_receive_pos', 'transaksi_receive_pos/*'],
                ],
                [
                    'text'   => 'Receive',
                    'url'    => 'transaksi_receive_pos_operasional',
                    'shift'  => 'ml-3',
                    'can'    => ['operasional-only'],
                    'icon'   => 'fas fa-fw fa-circle',
                    'active' => ['transaksi_receive_pos_operasional', 'transaksi_receive_pos_operasional/*'],
                ],
                [
                    'text'   => 'Send',
                    'url'    => 'transaksi_send_pos',
                    'shift'  => 'ml-3',
                    'can'    => ['compliance-only'],
                    'icon'   => 'fas fa-fw fa-circle',
                    'active' => ['transaksi_send_pos', 'transaksi_send_pos/*'],
                ],
                [
                    'text'   => 'Send',
                    'url'    => 'transaksi_send_pos_operasional',
                    'shift'  => 'ml-3',
                    'can'    => ['operasional-only'],
                    'icon'   => 'fas fa-fw fa-circle',
                    'active' => ['transaksi_send_pos_operasional', 'transaksi_send_pos_operasional/*'],
                ],
            ],
        ],
        [
            'text' => 'Branch Transactions',
            'icon' => 'fas fa-fw fa-code-branch',
            'submenu' => [
                [
                    'text'  => 'Receive',
                    'url'   => 'transaction_receive_adisena_compliance',
                    'shift' => 'ml-3',
                    'can'   => ['compliance-only'],
                    'icon'  => 'fas fa-fw fa-circle',
                    'active' => ['transaction_receive_adisena_compliance', 'transaction_receive_adisena_compliance/*'],
                ],
                [
                    'text'  => 'Receive',
                    'url'   => 'transaction_receive_adisena_operasional',
                    'shift' => 'ml-3',
                    'can'   => ['operasional-only'],
                    'icon'  => 'fas fa-fw fa-circle',
                    'active' => ['transaction_receive_adisena_operasional', 'transaction_receive_adisena_operasional/*'],
                ],
                [
                    'text'  => 'Send',
                    'url'   => 'transaction_send_adisena_compliance',
                    'shift' => 'ml-3',
                    'can'   => ['compliance-only'],
                    'icon'  => 'fas fa-fw fa-circle',
                    'active' => ['transaction_send_adisena_compliance', 'transaction_send_adisena_compliance/*'],
                ],
                [
                    'text'  => 'Send',
                    'url'   => 'transaction_send_adisena_operasional',
                    'shift' => 'ml-3',
                    'can'   => ['operasional-only'],
                    'icon'  => 'fas fa-fw fa-circle',
                    'active' => ['transaction_send_adisena_operasional', 'transaction_send_adisena_operasional/*'],
                ],
            ],
        ],
        [
            'text' => 'Direct Arsema Transactions',
            'icon' => 'fas fa-fw fa-exchange-alt',
            'submenu' => [
                [
                    'text'  => 'Send',
                    'url'   => 'transaction_send_direct_compliance',
                    'shift' => 'ml-3',
                    'can'   => ['compliance-only'],
                    'icon'  => 'fas fa-fw fa-circle',
                    'active' => ['transaction_send_direct_compliance', 'transaction_send_direct_compliance/*'],
                ],
                [
                    'text'  => 'Send',
                    'url'   => 'transaction_send_direct_operasional',
                    'shift' => 'ml-3',
                    'can'   => ['operasional-only'],
                    'icon'  => 'fas fa-fw fa-circle',
                    'active' => ['transaction_send_direct_operasional', 'transaction_send_direct_operasional/*'],
                ],
                [
                    'text'  => 'Receive',
                    'url'   => 'transaction_receive_direct_compliance',
                    'shift' => 'ml-3',
                    'can'   => ['compliance-only'],
                    'icon'  => 'fas fa-fw fa-circle',
                    'active' => ['transaction_receive_direct_compliance', 'transaction_receive_direct_compliance/*'],
                ],
                [
                    'text'  => 'Receive',
                    'url'   => 'transaction_receive_direct_operasional',
                    'shift' => 'ml-3',
                    'can'   => ['operasional-only'],
                    'icon'  => 'fas fa-fw fa-circle',
                    'active' => ['transaction_receive_direct_operasional', 'transaction_receive_direct_operasional/*'],
                ],
            ],
        ],

        // —— Customer ——
        ['header' => 'CUSTOMER'],
        [
            'text' => 'Customer Profile',
            'icon' => 'fas fa-fw fa-users',
            'can'  => ['compliance-only'],
            'submenu' => [
                [
                    'text'   => 'Customer',
                    'url'    => 'nama',
                    'shift'  => 'ml-3',
                    'icon'   => 'fas fa-fw fa-user',
                    'active' => ['nama', 'cari_nama'],
                ],
                [
                    'text'   => 'Profile',
                    'url'    => 'profil',
                    'shift'  => 'ml-3',
                    'icon'   => 'fas fa-fw fa-id-card',
                    'active' => ['profil', 'search_profil', 'detail_transaksi'],
                ],
                [
                    'text'   => 'Sales',
                    'url'    => 'sales',
                    'shift'  => 'ml-3',
                    'icon'   => 'fas fa-fw fa-handshake',
                    'active' => ['sales', 'search_sales'],
                ],
                [
                    'text'   => 'New Customer',
                    'url'    => 'newcustomer',
                    'shift'  => 'ml-3',
                    'icon'   => 'fas fa-fw fa-user-plus',
                    'active' => [
                        'newcustomer',
                        'newcustomer-send',
                        'newcustomer_all',
                        'search_newcustomer',
                        'search_newcustomer_send',
                        'search_newcustomer_all',
                    ],
                ],
            ],
        ],
        [
            'text' => 'Customer Analytics',
            'can'  => ['operasional-only'],
            'icon' => 'fas fa-fw fa-chart-bar',
            'submenu' => [
                [
                    'text'   => 'New Customer',
                    'url'    => 'newcustomer_operasional',
                    'shift'  => 'ml-3',
                    'icon'   => 'fas fa-fw fa-user-plus',
                    'active' => [
                        'newcustomer_operasional',
                        'newcustomer-send-operasional',
                        'search_newcustomer_operasional',
                        'search_newcustomer_send_operasional',
                    ],
                ],
                [
                    'text'   => 'New Customer Receive',
                    'url'    => 'customer-analytics/new-customer-receive',
                    'shift'  => 'ml-3',
                    'icon'   => 'fas fa-fw fa-inbox',
                    'active' => [
                        'customer-analytics/new-customer-receive',
                        'customer-analytics/new-customer-receive*',
                    ],
                ],
                [
                    'text'   => 'New Customer Send',
                    'url'    => 'newcustomer_daily',
                    'shift'  => 'ml-3',
                    'icon'   => 'fas fa-fw fa-paper-plane',
                    'active' => ['newcustomer_daily', 'newcustomer_daily*'],
                ],
                [
                    'text'   => 'Customer Transactions',
                    'url'    => 'counting_customer_send',
                    'shift'  => 'ml-3',
                    'icon'   => 'fas fa-fw fa-calculator',
                    'active' => [
                        'counting_customer_send',
                        'counting_customer_send/detail',
                        'counting_customer_send*',
                    ],
                ],
            ],
        ],

        // —— Compliance ——
        ['header' => 'COMPLIANCE'],
        [
            'text' => 'Sanction List',
            'icon' => 'fas fa-fw fa-shield-alt',
            'can'  => ['operasional-only'],
            'submenu' => [
                [
                    'text'   => 'Sanction',
                    'url'    => 'sanction_operasional',
                    'shift'  => 'ml-3',
                    'icon'   => 'fas fa-fw fa-ban',
                    'active' => ['sanction_operasional'],
                ],
                [
                    'text'   => 'Screening',
                    'url'    => 'screening_operasional',
                    'shift'  => 'ml-3',
                    'icon'   => 'fas fa-fw fa-user-check',
                    'active' => ['screening_operasional'],
                ],
            ],
        ],
        [
            'text' => 'Sanction List',
            'icon' => 'fas fa-fw fa-shield-alt',
            'can'  => ['compliance-only'],
            'submenu' => [
                [
                    'text'   => 'Sanction',
                    'url'    => 'sanction',
                    'shift'  => 'ml-3',
                    'icon'   => 'fas fa-fw fa-ban',
                    'active' => ['sanction', 'tambah_sanction', 'act_tambah_sanction'],
                ],
                [
                    'text'   => 'Screening',
                    'url'    => 'screening',
                    'shift'  => 'ml-3',
                    'icon'   => 'fas fa-fw fa-user-check',
                    'active' => ['screening', 'cari_screening'],
                ],
            ],
        ],
        [
            'text' => 'Filtering Transaction',
            'icon' => 'fas fa-fw fa-filter',
            'submenu' => [
                [
                    'text'   => 'High Risk Country Transaction',
                    'url'    => 'filtering',
                    'shift'  => 'ml-3',
                    'can'    => ['compliance-only'],
                    'icon'   => 'fas fa-fw fa-exclamation-triangle',
                    'active' => ['filtering', 'cari_filtering'],
                ],
                [
                    'text'   => 'High Risk Country Transaction',
                    'url'    => 'filtering-operasional',
                    'shift'  => 'ml-3',
                    'can'    => ['operasional-only'],
                    'icon'   => 'fas fa-fw fa-exclamation-triangle',
                    'active' => ['filtering-operasional', 'cari_filtering_operasional'],
                ],
                [
                    'text'   => 'Filtering Transaction',
                    'url'    => 'filtering_transaction',
                    'shift'  => 'ml-3',
                    'can'    => ['compliance-only'],
                    'icon'   => 'fas fa-fw fa-search-dollar',
                    'active' => [
                        'filtering_transaction',
                        'filtering_identitas',
                        'search_filtering_identitas',
                        'search_filtering_transaction',
                        'filter_telepon',
                        'filter_negara',
                        'filter_sender',
                        'filter_one_to_many',
                        'filter_many_to_one',
                        'filter_nik_amount',
                        'search_filtering_telepon',
                        'search_filtering_negara',
                        'search_filtering_sender',
                        'search_filtering_one_to_many',
                        'search_filtering_many_to_one',
                        'search_filtering_nik_amount',
                        'detail_identitas',
                        'detail_telpon',
                        'detail_sender',
                        'detail_one_to_many',
                        'detail_many_to_one',
                        'detail_nik_amount',
                    ],
                ],
                [
                    'text'   => 'Filtering Transaction',
                    'url'    => 'filtering_transaction_operasional',
                    'shift'  => 'ml-3',
                    'can'    => ['operasional-only'],
                    'icon'   => 'fas fa-fw fa-search-dollar',
                    'active' => [
                        'filtering_transaction_operasional',
                        'filtering_identitas_operasional',
                        'search_filtering_identitas_operasional',
                        'search_filtering_transaction_operasional',
                        'filter_telepon_operasional',
                        'filter_negara_operasional',
                        'filter_sender_operasional',
                        'filter_one_to_many_operasional',
                        'filter_many_to_one_operasional',
                        'filter_nik_amount_operasional',
                        'search_filtering_telepon_operasional',
                        'search_filtering_negara_operasional',
                        'search_filtering_sender_operasional',
                        'search_filtering_one_to_many_operasional',
                        'search_filtering_many_to_one_operasional',
                        'search_filtering_nik_amount_operasional',
                        'detail_identitas_operasional',
                        'detail_one_to_many_operasional',
                        'detail_many_to_one_operasional',
                        'detail_nik_amount_operasional',
                    ],
                ],
                [
                    'text'   => 'City',
                    'url'    => 'kota',
                    'can'    => ['compliance-only'],
                    'shift'  => 'ml-3',
                    'icon'   => 'fas fa-fw fa-city',
                    'active' => ['kota', 'detail_kota', 'tanggal_valid'],
                ],
                [
                    'text'   => 'City',
                    'url'    => 'kota-operasional',
                    'can'    => ['operasional-only'],
                    'shift'  => 'ml-3',
                    'icon'   => 'fas fa-fw fa-city',
                    'active' => ['kota-operasional', 'detail_kota_operasional'],
                ],
                [
                    'text'   => 'Country',
                    'url'    => 'negara',
                    'can'    => ['compliance-only'],
                    'shift'  => 'ml-3',
                    'icon'   => 'fas fa-fw fa-globe-asia',
                    'active' => [
                        'negara',
                        'negara_send',
                        'negara_receive',
                        'detail_negara',
                        'proses_detail_negara',
                        'proses_detail_negara_send',
                        'pencarian_detail_negara',
                        'pencarian_detail_negara_send',
                    ],
                ],
                [
                    'text'   => 'Country',
                    'url'    => 'negara-operasional',
                    'can'    => ['operasional-only'],
                    'shift'  => 'ml-3',
                    'icon'   => 'fas fa-fw fa-globe-asia',
                    'active' => [
                        'negara-operasional',
                        'negara_send_operasional',
                        'negara_receive_operasional',
                        'detail_negara_operasional',
                        'proses_detail_negara_operasional',
                        'proses_detail_negara_operasional_send',
                        'pencarian_detail_negara_operasional',
                        'pencarian_detail_negara_send_operasional',
                    ],
                ],
            ],
        ],
        [
            'text' => 'Screening Transaction',
            'url'  => 'screening_transaksi_operasional',
            'can'  => ['operasional-only'],
            'icon' => 'fas fa-fw fa-search',
            'active' => [
                'screening_transaksi_operasional',
                'cari_screening_transaksi_oprasional',
            ],
        ],
        [
            'text' => 'Screening Transaction',
            'url'  => 'screening_transaksi',
            'can'  => ['compliance-only'],
            'icon' => 'fas fa-fw fa-search',
            'active' => [
                'screening_transaksi',
                'cari_screening_transaksi',
            ],
        ],

        // —— Data & Laporan ——
        ['header' => 'DATA & REPORTS'],
        [
            'text' => 'Nopend / Id Pos',
            'url'  => 'nopend',
            'can'  => ['compliance-only'],
            'icon' => 'fas fa-fw fa-store',
        ],
        [
            'text' => 'Nopend / Id Pos',
            'url'  => 'nopend_operasional',
            'can'  => ['operasional-only'],
            'icon' => 'fas fa-fw fa-store',
        ],
        [
            'text' => 'Agent Id',
            'url'  => 'agent_sort',
            'can'  => ['compliance-only'],
            'icon' => 'fas fa-fw fa-id-badge',
        ],
        [
            'text' => 'Agent Id',
            'url'  => 'agent_sort_operasional',
            'can'  => ['operasional-only'],
            'icon' => 'fas fa-fw fa-id-badge',
        ],
        [
            'text' => 'Reports',
            'url'  => 'laporan',
            'can'  => ['compliance-only'],
            'icon' => 'fas fa-fw fa-chart-line',
        ],
        [
            'text' => 'Reports',
            'url'  => 'laporan_operasional',
            'can'  => ['operasional-only'],
            'icon' => 'fas fa-fw fa-chart-line',
        ],

        // —— Documents & History ——
        ['header' => 'DOCUMENTS & HISTORY'],
        [
            'text' => 'Upload Proof Of Payment',
            'url'  => 'popimage',
            'can'  => ['compliance-only'],
            'icon' => 'fas fa-fw fa-file-upload',
        ],
        [
            'text' => 'Upload Proof Of Payment',
            'url'  => 'popimage-operasional',
            'can'  => ['operasional-only'],
            'icon' => 'fas fa-fw fa-file-upload',
        ],
        [
            'text' => 'History Refund Arsema',
            'url'  => 'history_refund',
            'can'  => ['compliance-only'],
            'icon' => 'fas fa-fw fa-undo',
        ],
        [
            'text' => 'History Refund Arsema',
            'url'  => 'history_refund_operasional',
            'can'  => ['operasional-only'],
            'icon' => 'fas fa-fw fa-undo',
        ],
        [
            'text' => 'History Refund PT POS',
            'url'  => 'history_refund_pos',
            'can'  => ['compliance-only'],
            'icon' => 'fas fa-fw fa-history',
        ],
        [
            'text' => 'History Refund PT POS',
            'url'  => 'history_refund_pos_operasional',
            'can'  => ['operasional-only'],
            'icon' => 'fas fa-fw fa-history',
        ],
        [
            'text' => 'History Amend PT POS',
            'url'  => 'history_amend_pos',
            'can'  => ['compliance-only'],
            'icon' => 'fas fa-fw fa-edit',
        ],
        [
            'text' => 'History Amend PT POS',
            'url'  => 'history_amend_pos_operasional',
            'can'  => ['operasional-only'],
            'icon' => 'fas fa-fw fa-edit',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    |
    | Here we can modify the menu filters of the admin panel.
    |
    | For detailed instructions you can look the menu filters section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
    */

    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SearchFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\LangFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\DataFilter::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugins Initialization
    |--------------------------------------------------------------------------
    |
    | Here we can modify the plugins used inside the admin panel.
    |
    | For detailed instructions you can look the plugins section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Plugins-Configuration
    |
    */

    'plugins' => [
        'ArsemaTheme' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'css/arsema-theme.css',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'css/arsema-table.css',
                ],
            ],
        ],
        'Datatables' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => '//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css',
                ],
            ],
        ],
        'Select2' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.css',
                ],
            ],
        ],
        'Chartjs' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.bundle.min.js',
                ],
            ],
        ],
        'Sweetalert2' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.jsdelivr.net/npm/sweetalert2@8',
                ],
            ],
        ],
        'Pace' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/themes/blue/pace-theme-center-radar.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js',
                ],
            ],
        ],
        'ConnectionStatus' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'js/connection-status.js',
                ],
            ],
        ],
        'DateRangePicker' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/moment/moment.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/daterangepicker/daterangepicker.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'vendor/daterangepicker/daterangepicker.css',
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | IFrame
    |--------------------------------------------------------------------------
    |
    | Here we change the IFrame mode configuration. Note these changes will
    | only apply to the view that extends and enable the IFrame mode.
    |
    | For detailed instructions you can look the iframe mode section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/IFrame-Mode-Configuration
    |
    */

    'iframe' => [
        'default_tab' => [
            'url' => null,
            'title' => null,
        ],
        'buttons' => [
            'close' => true,
            'close_all' => true,
            'close_all_other' => true,
            'scroll_left' => true,
            'scroll_right' => true,
            'fullscreen' => true,
        ],
        'options' => [
            'loading_screen' => 1000,
            'auto_show_new_tab' => true,
            'use_navbar_items' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Livewire support.
    |
    | For detailed instructions you can look the livewire here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    |
    */

    'livewire' => false,
];
