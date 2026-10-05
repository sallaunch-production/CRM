{{--
  Reusable empty state for period-based CRM searches.
  Props (optional): $title, $body (HTML allowed via {!! !!} if $bodyHtml=true)
--}}
@php
    $title = $title ?? __('crm.empty_start_period_title');
    $body = $body ?? __('crm.empty_start_period_body');
    $icon = $icon ?? 'fas fa-calendar-alt';
@endphp
<div class="crm-empty">
    <div class="crm-empty-icon" aria-hidden="true">
        <i class="{{ $icon }}"></i>
    </div>
    <h2 class="crm-empty-title">{{ $title }}</h2>
    <p class="crm-empty-text">{!! $body !!}</p>
</div>
