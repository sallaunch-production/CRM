@php
    $flagStartDate = old('start_date', $tanggal_dari ?? ($dari ?? ''));
    $flagEndDate = old('end_date', $tanggal_sampai ?? ($sampai ?? ''));
@endphp

<div class="flag-filter-field">
    <label for="start_date">Start Date</label>
    <input
        type="date"
        name="start_date"
        id="start_date"
        class="form-control"
        value="{{ $flagStartDate }}"
        @if($flagEndDate) max="{{ $flagEndDate }}" @endif
        required
    >
</div>

<div class="flag-filter-field">
    <label for="end_date">End Date</label>
    <input
        type="date"
        name="end_date"
        id="end_date"
        class="form-control"
        value="{{ $flagEndDate }}"
        @if($flagStartDate) min="{{ $flagStartDate }}" @endif
        required
    >
</div>

<button type="submit" class="btn btn-arsema">
    <i class="fas fa-search mr-1"></i> Cari
</button>

@once
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var startDate = document.getElementById('start_date');
        var endDate = document.getElementById('end_date');
        if (!startDate || !endDate) {
            return;
        }

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
</script>
@endonce
