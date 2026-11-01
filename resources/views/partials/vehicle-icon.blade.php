<svg class="vicon" viewBox="0 0 160 80" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    @switch($type ?? 'Car')
        @case('SUV')
            <path d="M10 58V36c0-3 2-5 5-5h20l9-15h64l13 15h19c3 0 5 2 5 5v22z"/>
            <path d="M52 31V18M88 31V18"/>
            <circle cx="42" cy="58" r="10" fill="#1a2352"/><circle cx="118" cy="58" r="10" fill="#1a2352"/>
            @break
        @case('Van')
            <path d="M8 58V16h98v16h30l16 18v8z"/>
            <path d="M106 32v26M120 32h14"/>
            <circle cx="36" cy="58" r="10" fill="#1a2352"/><circle cx="122" cy="58" r="10" fill="#1a2352"/>
            @break
        @case('Motorbike')
            <circle cx="36" cy="56" r="15" fill="#1a2352"/><circle cx="126" cy="56" r="15" fill="#1a2352"/>
            <path d="M36 56l24-26h30l36 26"/>
            <path d="M60 30l-6-11H40M92 30l9-14h14"/>
            <path d="M70 30l8 14h26"/>
            @break
        @case('Truck')
            <path d="M6 58V24h66v34"/>
            <path d="M72 58V28h30l22 18h24v12z"/>
            <path d="M104 28v18"/>
            <circle cx="30" cy="58" r="10" fill="#1a2352"/><circle cx="104" cy="58" r="10" fill="#1a2352"/><circle cx="136" cy="58" r="10" fill="#1a2352"/>
            @break
        @default
            <path d="M10 58V47c0-4 3-7 7-8l18-5 17-18h46l19 18 18 5c4 1 7 4 7 8v11z"/>
            <path d="M52 34l10-12h30l12 12z"/>
            <circle cx="42" cy="58" r="10" fill="#1a2352"/><circle cx="118" cy="58" r="10" fill="#1a2352"/>
    @endswitch
</svg>