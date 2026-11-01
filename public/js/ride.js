// CommunityRide - small, dependency-free UI helpers
document.addEventListener('DOMContentLoaded', () => {

    // Vehicle filter chips
    const chips = document.querySelectorAll('#chips .chip');
    chips.forEach(chip => chip.addEventListener('click', () => {
        chips.forEach(c => c.classList.remove('active'));
        chip.classList.add('active');
        const type = chip.dataset.type;
        document.querySelectorAll('.vcard').forEach(card => {
            card.classList.toggle('hide', !(type === 'all' || card.dataset.type === type));
        });
    }));

    // Show / hide password
    document.querySelectorAll('[data-toggle-password]').forEach(btn => {
        btn.addEventListener('click', () => {
            const input = document.getElementById(btn.dataset.togglePassword);
            const show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            btn.textContent = show ? 'Hide' : 'Show';
        });
    });

    // Password strength meter (register page)
    const pw = document.getElementById('password');
    const meter = document.querySelector('.meter span');
    const label = document.querySelector('.meter-label');
    if (pw && meter && document.body.classList.contains('page-register')) {
        pw.addEventListener('input', () => {
            const v = pw.value;
            let score = 0;
            if (v.length >= 8) score++;
            if (/[a-z]/.test(v) && /[A-Z]/.test(v)) score++;
            if (/\d/.test(v)) score++;
            if (/[^A-Za-z0-9]/.test(v) || v.length >= 14) score++;
            const levels = [
                ['0%', '#ff7b7b', ''],
                ['25%', '#ff7b7b', 'Weak'],
                ['50%', '#ffb547', 'Okay'],
                ['75%', '#c6e86b', 'Good'],
                ['100%', '#4ade80', 'Strong'],
            ];
            const [w, c, t] = levels[v ? Math.max(score, 1) : 0];
            meter.style.width = w; meter.style.background = c; label.textContent = t;
        });
    }

    // Loading state on form submit (prevents double submits)
    document.querySelectorAll('form[data-loading]').forEach(form => {
        form.addEventListener('submit', () => {
            const btn = form.querySelector('button[type=submit]');
            if (btn) { btn.classList.add('loading'); btn.firstChild.textContent = 'Please wait'; }
        });
    });

    // Reveal on scroll
    const items = document.querySelectorAll('.reveal');
    if ('IntersectionObserver' in window) {
        const io = new IntersectionObserver(entries => entries.forEach(e => {
            if (e.isIntersecting) { e.target.classList.add('in'); io.unobserve(e.target); }
        }), { threshold: .12 });
        items.forEach(el => io.observe(el));
    } else {
        items.forEach(el => el.classList.add('in'));
    }
});