@php
    $level = $levelnya ?? '';
    $activeTab = $activeTab ?? 'receive'; // receive | send | all

    if ($level === 'operasional') {
        $tabs = [
            'receive' => ['label' => 'Receive', 'route' => 'newcustomer_operasional'],
            'send' => ['label' => 'Send', 'route' => 'newcustomer_send_operasional'],
        ];
    } else {
        $tabs = [
            'receive' => ['label' => 'Receive', 'route' => 'newcustomer'],
            'send' => ['label' => 'Send', 'route' => 'newcustomer_send'],
            'all' => ['label' => 'All', 'route' => 'newcustomer_all'],
        ];
    }
@endphp

<nav class="crm-tabs" aria-label="New Customer tabs">
    @foreach($tabs as $key => $tab)
        <a
            href="{{ route($tab['route']) }}"
            class="crm-tab {{ $activeTab === $key ? 'is-active' : '' }}"
        >
            {{ $tab['label'] }}
        </a>
    @endforeach
</nav>
