@if ($datum->qc_issue)
    {{ 'QC Issue' }}
@else
    {{ $structure[$key]['casts'][$datum?->$key] ?? 'N/A' }}
@endif
