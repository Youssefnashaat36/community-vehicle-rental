const Tag = ({ className, title, sub, children }) => (
  <div className={`absolute z-10 flex items-center gap-3 rounded-2xl border border-white/10 bg-[#121850]/90 py-3 pl-3 pr-5 backdrop-blur ${className}`}>
    <span className="grid h-10 w-10 place-items-center rounded-xl bg-[#ffa62b]/15 text-[#ffa62b]">
      <svg viewBox="0 0 24 24" className="h-5 w-5" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">{children}</svg>
    </span>
    <div>
      <div className="font-bold text-white">{title}</div>
      <div className="text-sm text-[#8e96e6]">{sub}</div>
    </div>
  </div>
)

function HeroArt() {
  const stars = [[60,60],[250,40],[420,95],[600,70],[130,150],[520,180],[330,120],[580,250],[40,230]]
  const buildings = [[20,300,40,55],[70,265,45,90],[130,320,55,35],[180,285,40,70],[228,300,50,55],[290,330,40,25],[360,320,40,35],[400,290,45,65],[450,262,38,93],[500,310,55,45],[560,280,45,75],[605,300,50,55]]
  const windows = [[30,312],[80,280],[92,300],[190,300],[240,315],[410,305],[462,280],[475,300],[570,296],[615,316],[512,325]]

  return (
    <div className="relative h-[560px] overflow-hidden rounded-[2.5rem] border border-white/10 bg-[#0a0e30] lg:h-[710px]">
      <svg className="absolute inset-0 h-full w-full" viewBox="0 0 665 710" preserveAspectRatio="xMidYMid slice">
        <defs>
          <linearGradient id="sky" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0" stopColor="#0a0e30" /><stop offset="1" stopColor="#1c2266" />
          </linearGradient>
          <radialGradient id="glow">
            <stop offset="0" stopColor="#ffa62b" stopOpacity=".5" /><stop offset="1" stopColor="#ffa62b" stopOpacity="0" />
          </radialGradient>
          <linearGradient id="road" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0" stopColor="#1b2168" /><stop offset="1" stopColor="#10154a" />
          </linearGradient>
        </defs>

        <rect width="665" height="355" fill="url(#sky)" />
        <rect y="355" width="665" height="355" fill="#0f1448" />
        <ellipse cx="332" cy="355" rx="260" ry="90" fill="url(#glow)" />

        {stars.map(([x, y], i) => <circle key={i} cx={x} cy={y} r="1.6" fill="#fff" opacity=".8" />)}
        {buildings.map(([x, y, w, h], i) => <rect key={i} x={x} y={y} width={w} height={h} fill="#141a5a" />)}
        {windows.map(([x, y], i) => <rect key={i} x={x} y={y} width="5" height="7" fill="#ffa62b" />)}

        <polygon points="332,355 -67,710 732,710" fill="url(#road)" />
        <line x1="332" y1="355" x2="-67" y2="710" stroke="#c98a2b" strokeWidth="2" />
        <line x1="332" y1="355" x2="732" y2="710" stroke="#c98a2b" strokeWidth="2" />

        {[[385,14,3],[415,22,4],[455,30,5],[600,45,6],[665,55,7]].map(([y, h, w], i) => (
          <rect key={i} x={332 - w / 2} y={y} width={w} height={h} rx="1.5" fill="#e9ecff" opacity=".85" />
        ))}

        {[[190,430],[250,385],[476,430],[415,385]].map(([x, y], i) => (
          <g key={i}>
            <line x1={x} y1={y} x2={x} y2={y + 85} stroke="#2a3190" strokeWidth="2" />
            <circle cx={x} cy={y} r="5" fill="#ffa62b" />
            <circle cx={x} cy={y} r="14" fill="#ffa62b" opacity=".15" />
          </g>
        ))}

        <ellipse cx="332" cy="578" rx="95" ry="14" fill="#ffa62b" opacity=".35" />
        <path d="M283 490 L303 447 H361 L381 490 Z" fill="#262d86" />
        <rect x="247" y="488" width="170" height="60" rx="16" fill="#2f379c" />
        <rect x="262" y="508" width="40" height="11" rx="5.5" fill="#ffa62b" />
        <rect x="362" y="508" width="40" height="11" rx="5.5" fill="#ffa62b" />
        <rect x="308" y="536" width="48" height="14" rx="3" fill="#e9ecff" />
        <rect x="268" y="558" width="28" height="14" rx="6" fill="#0a0e30" />
        <rect x="368" y="558" width="28" height="14" rx="6" fill="#0a0e30" />
      </svg>

      <Tag className="left-8 top-12" title="0.4 km away" sub="Ready to pick up">
        <path d="M12 21s-6-5.3-6-10a6 6 0 1112 0c0 4.7-6 10-6 10z" /><circle cx="12" cy="11" r="2" />
      </Tag>
      <Tag className="right-8 top-[310px]" title="Verified host" sub="ID checked">
        <path d="M12 3l7 3v5c0 5-3.5 8-7 10-3.5-2-7-5-7-10V6l7-3z" /><path d="M9 12l2 2 4-4" />
      </Tag>
      <Tag className="bottom-12 left-12" title="Book in 60 seconds" sub="From $25 / day">
        <circle cx="12" cy="12" r="9" /><path d="M12 7v5l3 2" />
      </Tag>
    </div>
  )
}

export default HeroArt