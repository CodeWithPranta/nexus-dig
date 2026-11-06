const $ = (s, r = document) => r.querySelector(s);

const CFG = window.NexusDig || {};
const I18N = CFG.i18n || {};
const LOCALE = CFG.locale || 'bn';
const CURRENCY = CFG.currency || 'BDT';
const RATES = CFG.rates || { USD: 1, BDT: 120 };
const META = CFG.currencies || {
    BDT: { symbol: '৳', decimals: 0 },
    USD: { symbol: '$', decimals: 2 },
};

const t = (key, replacements = {}) => {
    let value = I18N[key] ?? key;
    Object.entries(replacements).forEach(([k, v]) => {
        value = value.replaceAll(`:${k}`, String(v));
    });
    return value;
};

const toUsd = (amount, from) => (from === 'USD' ? amount : amount / (RATES[from] || 1));
const convert = (amount, from, to) => toUsd(amount, from) * (to === 'USD' ? 1 : (RATES[to] || 1));

const money = (amount, from = 'BDT', to = CURRENCY) => {
    const meta = META[to] || {};
    const decimals = meta.decimals ?? 2;
    const value = convert(amount, from, to);
    const locale = LOCALE === 'bn' ? 'bn-BD' : 'en-US';
    const formatted = value.toLocaleString(locale, {
        minimumFractionDigits: decimals,
        maximumFractionDigits: decimals,
    });

    return `${meta.symbol || ''}${formatted}`;
};

const dual = (amount, from = 'BDT') => {
    const other = CURRENCY === 'BDT' ? 'USD' : 'BDT';

    return `${money(amount, from)} <span class="mut text-xs">(${money(amount, from, other)})</span>`;
};

const NAV = [
    ['home', t('nav_home')],
    ['courses', t('nav_courses')],
    ['services', t('nav_services')],
    ['analyzer', t('nav_analyzer')],
    ['contact', t('nav_contact')],
];

const courses = [
    {
        id: 1,
        t: t('course_laravel_title'),
        cat: t('category_web'),
        lv: t('level_beginner'),
        h: '24h',
        p: 5900,
        d: 4700,
        by: t('instructor_name'),
        s: [
            [t('section_getting_started'), [[t('lesson_course_intro'), 1], [t('lesson_toolchain'), 1], [t('lesson_routing'), 0]]],
            [t('section_databases'), [[t('lesson_migrations'), 0], [t('lesson_relationships'), 0], [t('lesson_live_qa'), 0, 'live']]],
        ],
    },
    {
        id: 2,
        t: t('course_ai_title'),
        cat: t('category_ai'),
        lv: t('level_intermediate'),
        h: '18h',
        p: 9500,
        d: null,
        by: t('instructor_name'),
        s: [
            [t('section_foundations'), [[t('lesson_llm'), 1], [t('lesson_prompt'), 0]]],
            [t('section_retrieval'), [[t('lesson_embeddings'), 0], [t('lesson_document'), 0]]],
        ],
    },
    {
        id: 3,
        t: t('course_bot_title'),
        cat: t('category_bot'),
        lv: t('level_beginner'),
        h: '10h',
        p: 3500,
        d: null,
        by: t('instructor_name'),
        s: [
            [t('section_basics'), [[t('lesson_bot_api'), 1], [t('lesson_commands'), 0]]],
            [t('section_workflows'), [[t('lesson_scheduled'), 0], [t('lesson_lead_bot'), 0]]],
        ],
    },
];

const services = [
    ['🌐', t('service_web'), t('service_web_desc')],
    ['📱', t('service_mobile'), t('service_mobile_desc')],
    ['⚙️', t('service_software'), t('service_software_desc')],
    ['📈', t('service_seo'), t('service_seo_desc')],
    ['🤖', t('service_ai'), t('service_ai_desc')],
    ['✈️', t('service_bots'), t('service_bots_desc')],
];

const price = (c) => (c.d
    ? `<s class="mut text-sm">${money(c.p)}</s> <b>${dual(c.d)}</b>`
    : `<b>${dual(c.p)}</b>`);

const toast = (m) => {
    const el = $('#toast');
    el.textContent = m;
    el.classList.remove('hidden');
    clearTimeout(toast.x);
    toast.x = setTimeout(() => el.classList.add('hidden'), 2600);
};

const ccard = (c) => `<a href="#course/${c.id}" class="card p-5 flex flex-col gap-3">
    <div class="thumb t${c.id % 3}" aria-hidden="true">🎓</div>
    <div class="flex gap-2 text-xs"><span class="soft px-2 py-0.5 rounded-full">${c.cat}</span><span class="mut py-0.5">${c.lv} · ${c.h}</span></div>
    <h3 class="font-semibold leading-snug">${c.t}</h3><p class="mut text-sm">${c.by}</p>
    <p class="mt-auto">${price(c)}</p></a>`;

const V = {
    home: () => `<div class="hero"><section class="max-w-6xl mx-auto px-4 pt-16 pb-20 grid md:grid-cols-2 gap-12 items-center relative">
    <div><p class="chip inline-flex items-center gap-2 font-semibold"><i class="w-2 h-2 rounded-full bg-emerald-500"></i>${t('hero_badge')}</p>
    <h1 class="text-4xl sm:text-6xl font-extrabold tracking-tight mt-5 leading-[1.05]">${t('hero_title_1')} <span class="gt">${t('hero_title_2')}</span></h1>
    <p class="mut mt-4 text-lg">${t('hero_subtitle')}</p>
    <div class="flex flex-wrap gap-3 mt-6"><a class="btn" href="#courses">${t('browse_courses')}</a><a class="btn btn-o" href="#contact">${t('request_quote')}</a></div></div>
    <div class="grid grid-cols-2 gap-4"><div class="col-span-2 card overflow-hidden shadow-xl"><div class="flex items-center gap-1.5 px-4 py-2.5 border-b bd"><i class="w-2.5 h-2.5 rounded-full bg-red-400"></i><i class="w-2.5 h-2.5 rounded-full bg-amber-400"></i><i class="w-2.5 h-2.5 rounded-full bg-emerald-400"></i><span class="mut text-xs ml-2">routes/web.php</span></div><pre class="code p-4 overflow-x-auto"><span class="mut">// Learn it. Ship it.</span>
Route::get('/courses', CourseIndex::class);
Route::post('/inquiry', InquiryController::class);
<span style="color:var(--br)">$bot</span>->onCommand('start', $welcome);</pre></div>
    <a href="#courses" class="card p-6"><p class="text-3xl">🎓</p><h2 class="font-semibold mt-3">${t('online_learning')}</h2><p class="mut text-sm mt-1">${t('online_learning_desc')}</p></a>
    <a href="#services" class="card p-6"><p class="text-3xl">🛠️</p><h2 class="font-semibold mt-3">${t('software_ai')}</h2><p class="mut text-sm mt-1">${t('software_ai_desc')}</p></a>
    </div></section>
    </div><section class="max-w-6xl mx-auto px-4 mt-8 flex flex-wrap items-center gap-2"><span class="mut text-sm mr-2">${t('what_we_work_with')}</span>${['Laravel', 'Tailwind CSS', 'MySQL', 'AI &amp; RAG', 'Telegram bots', 'Automation', 'SEO'].map((x) => `<span class="chip">${x}</span>`).join('')}</section>
    <section class="max-w-6xl mx-auto px-4 mt-14"><div class="flex justify-between items-end mb-6"><h2 class="text-3xl font-bold tracking-tight">${t('featured_courses')}</h2><a href="#courses" class="text-sm" style="color:var(--br)">${t('view_all')}</a></div>
    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">${courses.map(ccard).join('')}</div></section>
    <section class="max-w-6xl mx-auto px-4 mt-14"><h2 class="text-2xl font-bold mb-5">${t('what_we_build')}</h2>
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">${services.map((s) => `<div class="card lift p-5"><div class="ico">${s[0]}</div><h3 class="font-semibold mt-4">${s[1]}</h3><p class="mut text-sm mt-1">${s[2]}</p></div>`).join('')}</div></section>
    <section class="max-w-6xl mx-auto px-4 mt-14"><div class="cta p-10 text-center"><h2 class="text-3xl font-bold">${t('have_project')}</h2><p class="mt-2 opacity-90">${t('have_project_desc')}</p><a href="#contact" class="btn mt-6" style="background:#fff;color:#065f46">${t('start_inquiry')}</a></div></section>`,

    courses: () => `<section class="max-w-6xl mx-auto px-4 pt-10"><h1 class="text-3xl font-bold">${t('courses_title')}</h1>
    <div class="flex flex-wrap gap-3 mt-5"><input id="q" class="!w-full sm:!w-72" placeholder="${t('search_courses')}" aria-label="${t('search_courses')}">
    <select id="cat" class="!w-full sm:!w-56" aria-label="${t('all_categories')}"><option value="">${t('all_categories')}</option>${[...new Set(courses.map((c) => c.cat))].map((c) => `<option>${c}</option>`).join('')}</select></div>
    <div id="list" class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3 mt-6"></div></section>`,

    course: (id) => {
        const c = courses.find((x) => x.id == id) || courses[0];
        const n = c.s.flat().length;
        return `<section class="max-w-6xl mx-auto px-4 pt-8"><a href="#courses" class="text-sm mut">${t('all_courses')}</a>
    <div class="grid lg:grid-cols-3 gap-8 mt-4">
    <div class="lg:col-span-2">
    <p class="soft inline-block text-xs px-2 py-0.5 rounded-full">${c.cat}</p>
    <h1 class="text-3xl font-bold mt-2">${c.t}</h1><p class="mut mt-2">${c.lv} · ${c.h} · ${t('taught_by')} ${c.by}</p>
    <div class="card mt-5 aspect-video grid place-items-center"><button class="btn" onclick="window.NexusDigApp.toast('${t('free_intro')}')">${t('watch_free')}</button></div>
    <h2 class="text-xl font-bold mt-8">${t('syllabus')}</h2><p class="mut text-sm">${t('sections_lessons', { sections: c.s.length })}</p>
    <div class="mt-3 space-y-3">${c.s.map((s, i) => `<details class="card" ${i ? '' : 'open'}><summary class="cursor-pointer p-4 font-semibold">${s[0]} <span class="mut text-sm font-normal">· ${s[1].length} ${t('lessons')}</span></summary>
    <ul class="border-t bd">${s[1].map((l) => `<li class="flex justify-between gap-3 px-4 py-3 text-sm border-b bd last:border-0"><span>${l[2] === 'live' ? '📡' : '▶'} ${l[0]}</span>${l[1] ? `<span class="soft px-2 rounded-full text-xs py-0.5">${t('free_preview')}</span>` : `<span class="mut" title="${t('locked')}">🔒</span>`}</li>`).join('')}</ul></details>`).join('')}</div>
    </div>
    <aside class="card p-5 h-fit lg:sticky lg:top-24"><p class="text-2xl">${price(c)}</p>
    <button class="btn w-full mt-4" onclick="window.NexusDigApp.toast('${t('checkout_not_connected')}')">${t('enroll_now')}</button>
    <ul class="mut text-sm mt-4 space-y-1"><li>${n} ${t('lessons')}</li><li>${t('live_classes_recordings')}</li><li>${t('access_duration')}</li></ul></aside>
    </div></section>`;
    },

    services: () => `<section class="max-w-6xl mx-auto px-4 pt-10"><h1 class="text-3xl font-bold">${t('services_title')}</h1><p class="mut mt-2">${t('services_subtitle')}</p>
    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3 mt-6">${services.map((s) => `<div class="card lift p-6 flex flex-col"><div class="ico">${s[0]}</div><h2 class="font-semibold mt-4 text-lg">${s[1]}</h2><p class="mut text-sm mt-1">${s[2]}</p><a class="btn btn-o mt-5" href="#contact" data-svc="${s[1]}">${t('request_quote_btn')}</a></div>`).join('')}</div></section>`,

    contact: () => `<section class="max-w-2xl mx-auto px-4 pt-10"><h1 class="text-3xl font-bold">${t('service_inquiry')}</h1><p class="mut mt-2">${t('tell_us')}</p>
    <form id="f" class="card p-6 mt-6 grid gap-4" novalidate>
    <label>${t('name')}<input name="name" required autocomplete="name"></label>
    <label>${t('email')}<input name="email" type="email" required autocomplete="email"></label>
    <label>${t('phone_optional')}<input name="phone" type="tel"></label>
    <label>${t('service')}<select name="svc">${services.map((s) => `<option>${s[1]}</option>`).join('')}</select></label>
    <div class="grid sm:grid-cols-2 gap-4"><label>${t('budget')}<select><option>${t('budget_under')}</option><option>${t('budget_mid')}</option><option>${t('budget_over')}</option></select></label>
    <label>${t('timeline')}<select><option>${t('timeline_asap')}</option><option>${t('timeline_1_3')}</option><option>${t('timeline_flexible')}</option></select></label></div>
    <label>${t('project_description')}<textarea name="msg" rows="4" required minlength="20"></textarea></label>
    <p id="err" class="text-sm text-red-500 hidden" role="alert"></p>
    <button class="btn">${t('send_inquiry')}</button></form></section>`,

    analyzer: () => `<section class="max-w-4xl mx-auto px-4 pt-10"><h1 class="text-3xl font-bold">${t('website_analyzer')}</h1><p class="mut mt-2">${t('analyzer_subtitle')}</p>
    <form id="az" class="card p-5 mt-6 flex flex-col sm:flex-row gap-3" novalidate><input name="u" placeholder="example.com" aria-label="${t('website_address')}" required><select name="dev" class="sm:!w-40" aria-label="${t('device')}"><option value="mobile">${t('mobile')}</option><option value="desktop">${t('desktop')}</option></select><button class="btn">${t('analyze')}</button></form>
    <p id="azerr" class="text-sm text-red-500 mt-2 hidden" role="alert"></p><div id="res" class="mt-6"><div class="card p-8 text-center mut">${t('enter_website')}</div></div></section>`,

    dashboard: () => `<section class="max-w-6xl mx-auto px-4 pt-10"><div class="flex flex-wrap justify-between gap-3 items-end"><div><h1 class="text-3xl font-bold">${t('my_dashboard')}</h1><p class="mut text-sm">${t('sample_data')}</p></div></div>
    <div class="grid lg:grid-cols-3 gap-6 mt-6">
    <div class="lg:col-span-2 space-y-4"><h2 class="font-semibold">${t('continue_learning')}</h2>
    ${[[courses[0], 62, t('lesson_relationships')], [courses[1], 18, t('lesson_prompt')]].map(([c, p, l]) => `<div class="card p-5"><div class="flex justify-between gap-3"><div><h3 class="font-semibold">${c.t}</h3><p class="mut text-sm">${t('next')} ${l}</p></div><a class="btn !py-1.5 self-start" href="#course/${c.id}">${t('resume')}</a></div>
    <div class="bar mt-4" role="progressbar" aria-valuenow="${p}" aria-valuemin="0" aria-valuemax="100"><i style="width:${p}%"></i></div><p class="mut text-xs mt-1">${t('complete', { percent: p })}</p></div>`).join('')}
    <h2 class="font-semibold pt-2">${t('recent_orders')}</h2>
    <div class="card overflow-x-auto"><table class="w-full text-sm"><thead class="mut text-left"><tr><th class="p-3">${t('order')}</th><th>${t('course')}</th><th>${t('amount')}</th><th>${t('status')}</th></tr></thead><tbody>
    <tr class="border-t bd"><td class="p-3">#ND-0001</td><td>${t('course_laravel_title')}</td><td>${dual(4700)}</td><td><span class="soft px-2 py-0.5 rounded-full text-xs">${t('paid')}</span></td></tr>
    <tr class="border-t bd"><td class="p-3">#ND-0002</td><td>${t('course_ai_title')}</td><td>${dual(9500)}</td><td><span class="soft px-2 py-0.5 rounded-full text-xs">${t('pending')}</span></td></tr></tbody></table></div></div>
    <aside class="space-y-4"><div class="card p-5"><h2 class="font-semibold">${t('upcoming_class')}</h2><p class="mt-2 text-sm">${t('live_qa')}</p><p class="mut text-sm">${t('local_time')}</p>
    <button class="btn w-full mt-4" onclick="window.NexusDigApp.toast('${t('meeting_link_note')}')">${t('join_live')}</button></div>
    <div class="card p-5"><h2 class="font-semibold">${t('recordings')}</h2><ul class="mut text-sm mt-2 space-y-1"><li>▶ ${t('routing_recap')}</li><li>▶ ${t('eloquent_workshop')}</li></ul></div></aside>
    </div></section>`,
};

const esc = (s) => s.replace(/[&<>"']/g, (c) => '&#' + c.charCodeAt(0) + ';');

function report(h, dev) {
    let s = 0;
    for (const c of h) s = (s * 31 + c.charCodeAt(0)) >>> 0;
    const rnd = () => (s = (s * 1664525 + 1013904223) >>> 0) / 4294967296;
    const sc = (a, b) => Math.round(a + rnd() * (b - a));
    const m = dev === 'mobile';
    const S = [
        [t('performance'), sc(m ? 38 : 60, m ? 88 : 98)],
        [t('accessibility'), sc(70, 98)],
        [t('best_practices'), sc(70, 100)],
        [t('seo'), sc(65, 100)],
    ];
    const col = (n) => (n >= 90 ? '#059669' : n >= 50 ? '#d97706' : '#dc2626');
    const g = ([l, n]) => `<div class="card p-4 text-center"><svg viewBox="0 0 36 36" class="w-24 h-24 mx-auto -rotate-90" aria-hidden="true"><circle cx="18" cy="18" r="15.9" fill="none" stroke="var(--bd)" stroke-width="3"/><circle cx="18" cy="18" r="15.9" fill="none" stroke="${col(n)}" stroke-width="3" stroke-linecap="round" stroke-dasharray="${n} 100"/></svg><p class="text-2xl font-bold -mt-16 mb-10 relative" style="color:${col(n)}">${n}</p><p class="text-sm font-medium">${l}</p></div>`;
    const fcp = (m ? 1.2 + rnd() * 2 : 0.6 + rnd() * 1.4).toFixed(1);
    const lcp = (m ? 2 + rnd() * 3 : 1 + rnd() * 2).toFixed(1);
    const tbt = sc(m ? 150 : 30, m ? 600 : 250);
    const cls = (rnd() * 0.3).toFixed(2);
    const VT = [
        ['First Contentful Paint', fcp + ' s', fcp < 1.8],
        ['Largest Contentful Paint', lcp + ' s', lcp < 2.5],
        ['Total Blocking Time', tbt + ' ms', tbt < 200],
        ['Cumulative Layout Shift', cls, cls < 0.1],
    ];
    const C = [
        'Title tag present, 50–60 characters',
        'Meta description present',
        'Single H1 heading',
        'Images have alt text',
        'Canonical URL set',
        'robots.txt reachable',
        'XML sitemap found',
        'Page is mobile friendly',
        'Served over HTTPS',
        'Structured data detected',
    ];
    const ic = { ok: '✅', warn: '⚠️', bad: '❌' };
    const ck = C.map((item) => {
        const r = rnd();
        return [item, r > 0.35 ? 'ok' : r > 0.15 ? 'warn' : 'bad'];
    });
    const O = ['Serve images in next-gen formats', 'Eliminate render-blocking resources', 'Enable text compression', 'Reduce unused JavaScript'].map((item) => [item, sc(100, 1800)]);
    return `<p class="card p-3 text-sm mb-4" role="note"><b>${t('demo_mode')}</b> ${t('demo_mode_desc')}</p>
    <h2 class="text-xl font-bold mb-3">${esc(h)} <span class="soft text-xs px-2 py-0.5 rounded-full align-middle">${m ? t('mobile') : t('desktop')}</span></h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">${S.map(g).join('')}</div>
    <h3 class="font-semibold mt-8 mb-3">${t('core_metrics')}</h3><div class="grid grid-cols-2 md:grid-cols-4 gap-4">${VT.map((v) => `<div class="card p-4"><p class="mut text-xs">${v[0]}</p><p class="text-xl font-bold mt-1" style="color:${v[2] ? '#059669' : '#d97706'}">${v[1]}</p></div>`).join('')}</div>
    <div class="grid md:grid-cols-2 gap-6 mt-8"><div><h3 class="font-semibold mb-3">${t('seo_checklist')}</h3><ul class="card divide-y" style="--tw-divide-color:var(--bd)">${ck.map((c) => `<li class="flex gap-2 p-3 text-sm"><span aria-hidden="true">${ic[c[1]]}</span><span>${c[0]}</span></li>`).join('')}</ul></div>
    <div><h3 class="font-semibold mb-3">${t('opportunities')}</h3><ul class="card">${O.map((o) => `<li class="flex justify-between gap-3 p-3 text-sm border-b bd last:border-0"><span>${o[0]}</span><span class="mut whitespace-nowrap">~${o[1]} ms</span></li>`).join('')}</ul>
    <div class="cta p-6 mt-4"><p class="font-semibold">${t('want_fixed')}</p><p class="text-sm opacity-90 mt-1">${t('want_fixed_desc')}</p><a href="#contact" class="btn mt-4" style="background:#fff;color:#065f46">${t('request_seo_audit')}</a></div></div></div>`;
}

function bindAnalyzer() {
    const f = $('#az');
    f.onsubmit = (e) => {
        e.preventDefault();
        const er = $('#azerr');
        const v = f.u.value.trim();
        let u;
        try {
            u = new URL(/^https?:\/\//i.test(v) ? v : 'https://' + v);
            if (!u.hostname.includes('.')) throw 0;
        } catch {
            er.textContent = t('enter_valid_website');
            er.classList.remove('hidden');
            return;
        }
        er.classList.add('hidden');
        const r = $('#res');
        r.innerHTML = `<div class="card p-8 text-center mut" aria-busy="true">${t('analyzing')} ${esc(u.hostname)}…</div>`;
        setTimeout(() => {
            r.innerHTML = report(u.hostname, f.dev.value);
        }, 900);
    };
}

function updateTabs(k) {
    const map = {
        home: '#home',
        courses: '#courses',
        course: '#courses',
        services: '#services',
        analyzer: '#analyzer',
        contact: '#contact',
        dashboard: '#dashboard',
    };
    const activeHash = map[k] || '#home';
    document.querySelectorAll('.tab').forEach((tab) => {
        tab.classList.toggle('active', tab.getAttribute('href') === activeHash);
    });
}

function route() {
    const [v, arg] = (location.hash.slice(1) || 'home').split('/');
    const k = V[v] ? v : 'home';
    const app = $('#app');
    app.innerHTML = `<div class="view">${V[k](arg)}</div>`;

    const titleMap = {
        home: t('nav_home'),
        courses: t('courses_title'),
        course: t('courses_title'),
        services: t('services_title'),
        analyzer: t('website_analyzer'),
        contact: t('service_inquiry'),
        dashboard: t('my_dashboard'),
    };
    document.title = 'NexusDig – ' + (titleMap[k] || t('nav_home'));
    window.scrollTo(0, 0);

    const links = NAV.map(([h, l]) => `<a href="#${h}" ${k === h || (k === 'course' && h === 'courses') ? 'style="color:var(--br)" aria-current="page"' : ''}>${l}</a>`).join('');
    $('#nav').innerHTML = links;
    $('#mnav').innerHTML = links + `<a href="#dashboard">${t('nav_dashboard')}</a>`;
    $('#mnav').classList.add('hidden');
    $('#burger').setAttribute('aria-expanded', 'false');

    updateTabs(k);

    if (k === 'courses') {
        const draw = () => {
            const q = $('#q').value.toLowerCase();
            const c = $('#cat').value;
            const r = courses.filter((x) => x.t.toLowerCase().includes(q) && (!c || x.cat === c));
            $('#list').innerHTML = r.length ? r.map(ccard).join('') : `<p class="mut col-span-full card p-6 text-center">${t('no_courses')}</p>`;
        };
        $('#q').oninput = $('#cat').onchange = draw;
        draw();
    }
    if (k === 'analyzer') bindAnalyzer();
    if (k === 'contact') {
        $('#f').onsubmit = (e) => {
            e.preventDefault();
            const f = e.target;
            const er = $('#err');
            const bad = !f.name.value.trim()
                ? t('validation_name')
                : !/^\S+@\S+\.\S+$/.test(f.email.value)
                  ? t('validation_email')
                  : f.msg.value.trim().length < 20
                    ? t('validation_description')
                    : '';
            er.textContent = bad;
            er.classList.toggle('hidden', !bad);
            if (!bad) {
                f.reset();
                toast(t('inquiry_validated'));
            }
        };
    }
}

function init() {
    $('#burger').onclick = () => {
        const m = $('#mnav');
        const o = m.classList.toggle('hidden');
        m.classList.toggle('flex', !o);
        $('#burger').setAttribute('aria-expanded', String(!o));
    };
    $('#theme').onclick = () => {
        const r = document.documentElement;
        const d = r.dataset.theme === 'dark' || (!r.dataset.theme && matchMedia('(prefers-color-scheme:dark)').matches);
        r.dataset.theme = d ? 'light' : 'dark';
    };
    addEventListener('hashchange', route);
    route();
}

window.NexusDigApp = { init, toast, route };

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
} else {
    init();
}

export { init, toast, route };
