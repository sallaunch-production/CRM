{{--
  Reusable CRM start/end date filter.
  Props (optional):
    $startDate, $endDate     — Y-m-d values
    $startName, $endName     — input names (default start_date / end_date)
    $startId, $endId         — element ids
    $startLabel, $endLabel   — field labels
    $groupLabel              — optional section title above the pair
--}}
@php
    $startName = $startName ?? 'start_date';
    $endName = $endName ?? 'end_date';
    $startId = $startId ?? $startName;
    $endId = $endId ?? $endName;
    $startLabel = $startLabel ?? 'Start Date';
    $endLabel = $endLabel ?? 'End Date';
    $crmStartDate = old($startName, $startDate ?? '');
    $crmEndDate = old($endName, $endDate ?? '');
@endphp

@if(!empty($groupLabel))
    <div class="crm-search-group">
        <div class="crm-search-group-label">{{ $groupLabel }}</div>
@endif

<div class="crm-search-field">
    <label for="{{ $startId }}">{{ $startLabel }}</label>
    <input
        type="date"
        name="{{ $startName }}"
        id="{{ $startId }}"
        class="form-control"
        value="{{ $crmStartDate }}"
        @if($crmEndDate) max="{{ $crmEndDate }}" @endif
        required
    >
</div>

<div class="crm-search-field">
    <label for="{{ $endId }}">{{ $endLabel }}</label>
    <input
        type="date"
        name="{{ $endName }}"
        id="{{ $endId }}"
        class="form-control"
        value="{{ $crmEndDate }}"
        @if($crmStartDate) min="{{ $crmStartDate }}" @endif
        required
    >
</div>

@if(!empty($groupLabel))
    </div>
@endif

@once
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('form').forEach(function (form) {
            form.querySelectorAll('input[type="date"][name$="start_date"]').forEach(function (startDate) {
                if (startDate.dataset.crmDateBound) {
                    return;
                }

                var endName = startDate.name.replace(/start_date$/, 'end_date');
                var endDate = form.querySelector('input[type="date"][name="' + endName + '"]');
                if (!endDate) {
                    return;
                }

                startDate.dataset.crmDateBound = '1';
                endDate.dataset.crmDateBound = '1';

                startDate.addEventListener('change', function () {
                    endDate.min = startDate.value || '';
                    if (endDate.value && startDate.value && endDate.value < startDate.value) {
                        endDate.value = '';
                    }
                });

                endDate.addEventListener('change', function () {
                    startDate.max = endDate.value || '';
                    if (startDate.value && endDate.value && endDate.value < startDate.value) {
                        endDate.value = startDate.value;
                    }
                });
            });
        });
    });
</script>
@endonce
