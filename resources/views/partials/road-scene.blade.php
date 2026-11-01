<svg viewBox="0 0 600 640" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="A car driving toward a lit city at night">
    <defs>
        <linearGradient id="sky" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0" stop-color="#060919"/>
            <stop offset=".6" stop-color="#141c4a"/>
            <stop offset="1" stop-color="#3a2a5a"/>
        </linearGradient>
        <linearGradient id="roadg" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0" stop-color="#1a2352"/>
            <stop offset="1" stop-color="#0a0f24"/>
        </linearGradient>
        <radialGradient id="horizon" cx=".5" cy=".5" r=".5">
            <stop offset="0" stop-color="#ffb547" stop-opacity=".55"/>
            <stop offset="1" stop-color="#ffb547" stop-opacity="0"/>
        </radialGradient>
        <filter id="soft"><feGaussianBlur stdDeviation="9"/></filter>
    </defs>

    <rect width="600" height="640" fill="url(#sky)"/>

    <g fill="#eef0fb">
        <circle class="twinkle" cx="60" cy="50" r="1.6"/><circle class="twinkle" cx="140" cy="110" r="1.2" style="animation-delay:-1s"/>
        <circle class="twinkle" cx="230" cy="40" r="1.5" style="animation-delay:-2s"/><circle class="twinkle" cx="330" cy="95" r="1.1"/>
        <circle class="twinkle" cx="420" cy="55" r="1.7" style="animation-delay:-1.5s"/><circle class="twinkle" cx="520" cy="120" r="1.3" style="animation-delay:-.5s"/>
        <circle class="twinkle" cx="560" cy="45" r="1.1" style="animation-delay:-2.4s"/><circle class="twinkle" cx="90" cy="170" r="1.1" style="animation-delay:-.8s"/>
        <circle class="twinkle" cx="480" cy="190" r="1.4"/><circle class="twinkle" cx="190" cy="200" r="1.2" style="animation-delay:-1.8s"/>
    </g>

    <ellipse cx="300" cy="320" rx="300" ry="110" fill="url(#horizon)"/>

    <g fill="#0c1233">
        <rect x="20" y="255" width="44" height="65"/><rect x="68" y="225" width="36" height="95"/>
        <rect x="108" y="268" width="50" height="52"/><rect x="162" y="240" width="38" height="80"/>
        <rect x="204" y="272" width="44" height="48"/><rect x="252" y="285" width="34" height="35"/>
        <rect x="314" y="282" width="34" height="38"/><rect x="352" y="250" width="40" height="70"/>
        <rect x="396" y="212" width="36" height="108"/><rect x="436" y="256" width="50" height="64"/>
        <rect x="490" y="236" width="38" height="84"/><rect x="532" y="266" width="52" height="54"/>
    </g>
    <g fill="#ffb547" opacity=".85">
        <rect x="76" y="240" width="5" height="6"/><rect x="88" y="258" width="5" height="6"/><rect x="76" y="276" width="5" height="6"/>
        <rect x="170" y="256" width="5" height="6"/><rect x="182" y="274" width="5" height="6"/><rect x="170" y="292" width="5" height="6"/>
        <rect x="404" y="228" width="5" height="6"/><rect x="418" y="246" width="5" height="6"/><rect x="404" y="266" width="5" height="6"/><rect x="418" y="286" width="5" height="6"/>
        <rect x="498" y="252" width="5" height="6"/><rect x="510" y="272" width="5" height="6"/>
        <rect x="360" y="266" width="5" height="6"/><rect x="374" y="284" width="5" height="6"/>
        <rect x="32" y="272" width="5" height="6"/><rect x="46" y="292" width="5" height="6"/>
        <rect x="446" y="274" width="5" height="6"/><rect x="544" y="284" width="5" height="6"/>
    </g>

    <rect y="320" width="600" height="320" fill="#080c20"/>

    <polygon points="300,320 660,640 -60,640" fill="url(#roadg)"/>
    <line x1="300" y1="320" x2="-60" y2="640" stroke="#ffb547" stroke-opacity=".55" stroke-width="2"/>
    <line x1="300" y1="320" x2="660" y2="640" stroke="#ffb547" stroke-opacity=".55" stroke-width="2"/>
    <line class="road-dash" x1="300" y1="320" x2="300" y2="640" stroke="#eef0fb" stroke-opacity=".75" stroke-width="4"/>
    <line class="road-dash" x1="300" y1="320" x2="120" y2="640" stroke="#eef0fb" stroke-opacity=".18" stroke-width="3"/>
    <line class="road-dash" x1="300" y1="320" x2="480" y2="640" stroke="#eef0fb" stroke-opacity=".18" stroke-width="3"/>

    <g stroke="#27337a" stroke-width="3">
        <line x1="170" y1="470" x2="170" y2="390"/><line x1="430" y1="470" x2="430" y2="390"/>
        <line x1="225" y1="400" x2="225" y2="350" stroke-width="2"/><line x1="375" y1="400" x2="375" y2="350" stroke-width="2"/>
    </g>
    <g fill="#ffb547">
        <circle cx="170" cy="388" r="5"/><circle cx="430" cy="388" r="5"/><circle cx="225" cy="348" r="3.5"/><circle cx="375" cy="348" r="3.5"/>
    </g>
    <g fill="#ffb547" opacity=".35" filter="url(#soft)">
        <circle cx="170" cy="388" r="18"/><circle cx="430" cy="388" r="18"/>
    </g>

    <g class="car-bob">
        <ellipse cx="300" cy="528" rx="104" ry="14" fill="#000" opacity=".5"/>
        <ellipse cx="300" cy="520" rx="96" ry="22" fill="#ff8a1f" opacity=".35" filter="url(#soft)"/>
        <path d="M232 446 L252 402 Q256 396 264 396 H336 Q344 396 348 402 L368 446 Z" fill="#1a2352"/>
        <path d="M246 442 L261 408 H339 L354 442 Z" fill="#0a0f24"/>
        <rect x="222" y="440" width="156" height="72" rx="18" fill="#27337a"/>
        <rect x="222" y="440" width="156" height="14" rx="7" fill="#33419a" opacity=".6"/>
        <rect x="230" y="462" width="40" height="11" rx="5.5" fill="#ffb547"/>
        <rect x="330" y="462" width="40" height="11" rx="5.5" fill="#ffb547"/>
        <rect x="230" y="462" width="40" height="11" rx="5.5" fill="#ff8a1f" opacity=".7" filter="url(#soft)"/>
        <rect x="330" y="462" width="40" height="11" rx="5.5" fill="#ff8a1f" opacity=".7" filter="url(#soft)"/>
        <rect x="278" y="482" width="44" height="16" rx="3" fill="#eef0fb"/>
        <rect x="282" y="486" width="36" height="8" rx="1.5" fill="#c9cdea"/>
        <rect x="232" y="504" width="26" height="14" rx="4" fill="#060919"/>
        <rect x="342" y="504" width="26" height="14" rx="4" fill="#060919"/>
    </g>
</svg>