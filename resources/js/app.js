const $ = (s, r = document) => r.querySelector(s);

const CFG = window.NexusDig || {};
const I18N = CFG.i18n || {};
const LOCALE = CFG.locale || 'en';
const CURRENCY = CFG.currency || 'USD';
const RATES = CFG.rates || { USD: 1, BDT: 120 };
const META = CFG.currencies || {
    BDT: { symbol: '৳', decimals: 0 },
    USD: { symbol: '$', decimals: 2 },
};
const AUTH = CFG.auth || {};
const AUTH_URL = AUTH.staff ? AUTH.admin : AUTH.dashboard;
const CHECKOUT = CFG.checkout || {};
const checkoutUrl = (type, slug) => CHECKOUT[type] && slug ? `${CHECKOUT[type]}/${encodeURIComponent(slug)}` : null;

const t = (key, replacements = {}) => {
    let value = I18N[key] ?? key;
    Object.entries(replacements).forEach(([k, v]) => {
        value = value.replaceAll(`:${k}`, String(v));
    });
    return value;
};

const num = (n) => Number(n).toLocaleString(LOCALE === 'bn' ? 'bn-BD' : 'en-US');

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

const budgetAmount = (usd) => {
    const meta = META[CURRENCY] || {};
    const locale = LOCALE === 'bn' ? 'bn-BD' : 'en-US';
    const formatted = convert(usd, 'USD', CURRENCY).toLocaleString(locale, { maximumFractionDigits: 0 });

    return `${meta.symbol || ''}${formatted}`;
};

const NAV = [
    ['home', t('nav_home')],
    ['courses', t('nav_courses')],
    ['services', t('nav_services')],
    ['team', t('nav_team')],
    ['analyzer', t('nav_analyzer')],
    ['contact', t('nav_contact')],
];

const tech = ['Laravel', 'Tailwind CSS', 'MySQL', 'AI & RAG', 'Telegram Bots', 'Automation', 'SEO', 'Livewire', 'Flutter', 'APIs'];

const courses = [
    {
        id: 1,
        slug: 'laravel-for-production-teams',
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
        slug: 'ai-engineering-with-rag',
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
        slug: 'telegram-bot-automation',
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
    {
        i: '🌐',
        slug: 'web-development',
        title: t('service_web'),
        desc: t('service_web_desc'),
        long: t('service_web_long'),
        f: [t('service_web_f1'), t('service_web_f2'), t('service_web_f3')],
    },
    {
        i: '📱',
        slug: 'mobile-app-development',
        title: t('service_mobile'),
        desc: t('service_mobile_desc'),
        long: t('service_mobile_long'),
        f: [t('service_mobile_f1'), t('service_mobile_f2'), t('service_mobile_f3')],
    },
    {
        i: '⚙️',
        slug: 'custom-software',
        title: t('service_software'),
        desc: t('service_software_desc'),
        long: t('service_software_long'),
        f: [t('service_software_f1'), t('service_software_f2'), t('service_software_f3')],
    },
    {
        i: '📈',
        slug: 'seo-content',
        title: t('service_seo'),
        desc: t('service_seo_desc'),
        long: t('service_seo_long'),
        f: [t('service_seo_f1'), t('service_seo_f2'), t('service_seo_f3')],
    },
    {
        i: '🤖',
        slug: 'ai-automation',
        title: t('service_ai'),
        desc: t('service_ai_desc'),
        long: t('service_ai_long'),
        f: [t('service_ai_f1'), t('service_ai_f2'), t('service_ai_f3')],
    },
    {
        i: '✈️',
        slug: 'telegram-bots',
        title: t('service_bots'),
        desc: t('service_bots_desc'),
        long: t('service_bots_long'),
        f: [t('service_bots_f1'), t('service_bots_f2'), t('service_bots_f3')],
    },
];

const partners = [
    { name: 'Startup Dhaka', mark: 'SD', hue: 6 },
    { name: 'TechHive', mark: 'TH', hue: 20 },
    { name: 'BuildLab', mark: 'BL', hue: 352 },
    { name: 'CloudWorks', mark: 'CW', hue: 12 },
    { name: 'GreenMart', mark: 'GM', hue: 28 },
    { name: 'EdTech BD', mark: 'EB', hue: 345 },
];

const team = [
    { id: 1, name: 'Rakib Hasan', dept: 'leadership', initials: 'RH', hue: 6, role: t('team_m1_role'), bio: t('team_m1_bio'), exp: t('team_m1_exp').split('|') },
    { id: 2, name: 'Nusrat Jahan', dept: 'leadership', initials: 'NJ', hue: 20, role: t('team_m2_role'), bio: t('team_m2_bio'), exp: t('team_m2_exp').split('|') },
    { id: 3, name: 'Tanvir Ahmed', dept: 'engineering', initials: 'TA', hue: 352, role: t('team_m3_role'), bio: t('team_m3_bio'), exp: t('team_m3_exp').split('|') },
    { id: 4, name: 'Sadia Islam', dept: 'engineering', initials: 'SI', hue: 12, role: t('team_m4_role'), bio: t('team_m4_bio'), exp: t('team_m4_exp').split('|') },
    { id: 5, name: 'Mehedi Hasan', dept: 'engineering', initials: 'MH', hue: 28, role: t('team_m5_role'), bio: t('team_m5_bio'), exp: t('team_m5_exp').split('|') },
    { id: 6, name: 'Farhana Akter', dept: 'growth', initials: 'FA', hue: 345, role: t('team_m6_role'), bio: t('team_m6_bio'), exp: t('team_m6_exp').split('|') },
    { id: 7, name: 'Imran Chowdhury', dept: 'instructors', initials: 'IC', hue: 15, role: t('team_m7_role'), bio: t('team_m7_bio'), exp: t('team_m7_exp').split('|') },
    { id: 8, name: 'Ayesha Rahman', dept: 'support', initials: 'AR', hue: 0, role: t('team_m8_role'), bio: t('team_m8_bio'), exp: t('team_m8_exp').split('|') },
];

const DEPTS = [
    ['all', t('team_all')],
    ['leadership', t('team_leadership')],
    ['instructors', t('team_instructors')],
    ['engineering', t('team_engineering')],
    ['growth', t('team_growth')],
    ['support', t('team_support')],
];

const deptLabel = (id) => (DEPTS.find(([key]) => key === id) || DEPTS[0])[1];

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

const techGroup = (dup = false) => `<div class="flex gap-2 pr-2"${dup ? ' aria-hidden="true"' : ''}>${tech.map((x) => `<span class="chip whitespace-nowrap">${x}</span>`).join('')}</div>`;

const serviceCard = (s, i) => `<button type="button" class="card lift p-5 text-left w-full cursor-pointer" onclick="window.NexusDigApp.openService(${i})">
    <div class="ico">${s.i}</div><h3 class="font-semibold mt-4">${s.title}</h3><p class="mut text-sm mt-1">${s.desc}</p>
    <span class="mt-3 inline-flex items-center gap-1 text-sm font-semibold" style="color:var(--br)">${t('learn_more')} <span aria-hidden="true">→</span></span>
</button>`;

const teamPhoto = (m, large = false) => `<div class="team-photo${large ? ' team-photo-lg' : ''}" style="--h:${m.hue}" aria-hidden="true">${m.initials}</div>`;

const teamCard = (m) => `<a href="#member/${m.id}" class="card lift p-4 flex flex-col gap-3">
    ${teamPhoto(m)}
    <div><h3 class="font-semibold leading-snug">${m.name}</h3><p class="mut text-sm">${m.role}</p></div>
    <span class="mt-auto text-sm font-semibold inline-flex items-center gap-1" style="color:var(--br)">${t('view_profile')} <span aria-hidden="true">→</span></span>
</a>`;

const clientsSection = () => `<section class="max-w-6xl mx-auto px-4 mt-14"><h2 class="text-2xl font-bold">${t('who_we_work_with')}</h2><p class="mut mt-2 text-sm max-w-2xl">${t('who_we_work_with_desc')}</p>
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 mt-6">${partners.map((p) => `<a href="#" data-partner-link class="card lift p-4 flex flex-col items-center justify-center gap-2 min-h-[96px]" aria-label="${p.name}">
    <span class="w-9 h-9 rounded-full grid place-items-center text-xs font-bold text-white" style="background:linear-gradient(135deg,hsl(${p.hue} 82% 64%),hsl(${p.hue + 18} 72% 46%))">${p.mark}</span>
    <span class="text-xs font-semibold text-center">${p.name}</span></a>`).join('')}</div>
    <div class="grid grid-cols-3 gap-3 mt-4">${[[8, t('years_experience')], [120, t('happy_clients')], [12, t('countries_served')]].map(([n, label]) => `<div class="card p-4 text-center"><p class="text-2xl font-extrabold" style="color:var(--br)">${num(n)}+</p><p class="mut text-xs mt-1">${label}</p></div>`).join('')}</div></section>`;

const V = {
    home: () => `<div class="hero"><canvas id="hero-net" class="hero-net" aria-hidden="true"></canvas><section class="max-w-6xl mx-auto px-4 pt-16 pb-20 grid md:grid-cols-2 gap-12 items-center relative">
    <div><div class="badge-ring"><p class="chip inline-flex items-center gap-2 font-semibold"><i class="w-2 h-2 rounded-full" style="background:var(--br)"></i>${t('hero_badge')}</p></div>
    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight mt-5 leading-[1.08]">${t('hero_title_1')} <span class="gt">${t('hero_title_2')}</span></h1>
    <p class="mut mt-4 text-lg">${t('hero_subtitle')}</p>
    <div class="flex flex-wrap gap-3 mt-6"><a class="btn" href="#courses">${t('browse_courses')}</a><a class="btn btn-o" href="#contact">${t('request_quote')}</a></div></div>
    <div class="grid grid-cols-2 gap-4"><div class="col-span-2 card overflow-hidden shadow-xl"><div class="flex items-center gap-1.5 px-4 py-2.5 border-b bd"><i class="w-2.5 h-2.5 rounded-full bg-red-400"></i><i class="w-2.5 h-2.5 rounded-full bg-amber-400"></i><i class="w-2.5 h-2.5 rounded-full" style="background:var(--br)"></i><span class="mut text-xs ml-2">routes/web.php</span></div><pre class="code p-4 overflow-x-auto"><span class="mut">// Learn it. Ship it.</span>
Route::get('/courses', CourseIndex::class);
Route::post('/inquiry', InquiryController::class);
<span style="color:var(--br)">$bot</span>->onCommand('start', $welcome);</pre></div>
    <a href="#courses" class="card p-6"><p class="text-3xl">🎓</p><h2 class="font-semibold mt-3">${t('online_learning')}</h2><p class="mut text-sm mt-1">${t('online_learning_desc')}</p></a>
    <a href="#services" class="card p-6"><p class="text-3xl">🛠️</p><h2 class="font-semibold mt-3">${t('software_ai')}</h2><p class="mut text-sm mt-1">${t('software_ai_desc')}</p></a>
    </div></section>
    </div><section class="max-w-6xl mx-auto px-4 mt-8"><p class="mut text-sm mb-3">${t('tech_marquee_label')}</p><div class="marquee" role="region" aria-label="${t('tech_marquee_label')}"><div class="marquee-track">${techGroup()}${techGroup(true)}</div></div></section>
    <section class="max-w-6xl mx-auto px-4 mt-14"><div class="flex justify-between items-end mb-6"><h2 class="text-3xl font-bold tracking-tight">${t('featured_courses')}</h2><a href="#courses" class="text-sm" style="color:var(--br)">${t('view_all')}</a></div>
    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">${courses.map(ccard).join('')}</div></section>
    <section class="max-w-6xl mx-auto px-4 mt-14"><h2 class="text-2xl font-bold mb-5">${t('our_services')}</h2>
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">${services.map((s, i) => serviceCard(s, i)).join('')}</div></section>
    ${clientsSection()}
    <section class="max-w-6xl mx-auto px-4 mt-14"><div class="flex justify-between items-end mb-6"><h2 class="text-2xl font-bold">${t('our_team')}</h2><a href="#team" class="text-sm" style="color:var(--br)">${t('view_all')}</a></div>
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">${team.slice(0, 4).map(teamCard).join('')}</div></section>
    <section class="max-w-6xl mx-auto px-4 mt-14"><div class="cta p-10 text-center"><h2 class="text-3xl font-bold">${t('have_project')}</h2><p class="mt-2 opacity-90">${t('have_project_desc')}</p><a href="#contact" class="btn mt-6" style="background:#fff;color:#b83b36">${t('start_inquiry')}</a></div></section>`,

    courses: () => `<section class="max-w-6xl mx-auto px-4 pt-10"><h1 class="text-3xl font-bold">${t('courses_title')}</h1>
    <div class="flex flex-wrap gap-3 mt-5"><input id="q" class="!w-full sm:!w-72" placeholder="${t('search_courses')}" aria-label="${t('search_courses')}">
    <select id="cat" class="!w-full sm:!w-56" aria-label="${t('all_categories')}"><option value="">${t('all_categories')}</option>${[...new Set(courses.map((c) => c.cat))].map((c) => `<option>${c}</option>`).join('')}</select></div>
    <div id="list" class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3 mt-6"></div></section>`,

    course: (id) => {
        const c = courses.find((x) => x.id == id) || courses[0];
        const n = c.s.flat().length;
        const checkout = checkoutUrl('course', c.slug);
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
    ${checkout ? `<a href="${checkout}" class="btn w-full mt-4">${t('enroll_now')}</a>` : `<button class="btn w-full mt-4" onclick="window.NexusDigApp.toast('${t('checkout_not_connected')}')">${t('enroll_now')}</button>`}
    <ul class="mut text-sm mt-4 space-y-1"><li>${n} ${t('lessons')}</li><li>${t('live_classes_recordings')}</li><li>${t('access_duration')}</li></ul></aside>
    </div></section>`;
    },

    services: () => `<section class="max-w-6xl mx-auto px-4 pt-10"><h1 class="text-3xl font-bold">${t('services_title')}</h1><p class="mut mt-2">${t('services_subtitle')}</p>
    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3 mt-6">${services.map((s, i) => serviceCard(s, i)).join('')}</div></section>`,

    team: () => `<section class="max-w-6xl mx-auto px-4 pt-10"><h1 class="text-3xl font-bold">${t('our_team')}</h1><p class="mut mt-2">${t('our_team_desc')}</p>
    <div id="team-filter" class="flex flex-wrap gap-2 mt-6">${DEPTS.map(([id, label]) => `<button type="button" class="chip cursor-pointer${id === 'all' ? ' active' : ''}" data-dept="${id}">${label} <span class="mut">${id === 'all' ? team.length : team.filter((m) => m.dept === id).length}</span></button>`).join('')}</div>
    <div id="team-grid" class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4 mt-6"></div></section>`,

    member: (id) => {
        const m = team.find((x) => x.id == id) || team[0];
        return `<section class="max-w-4xl mx-auto px-4 pt-8"><a href="#team" class="text-sm mut">${t('back_to_team')}</a>
    <div class="card p-6 mt-4 grid sm:grid-cols-[200px_1fr] gap-6 items-start">
    ${teamPhoto(m, true)}
    <div><span class="soft inline-block text-xs px-2 py-0.5 rounded-full">${deptLabel(m.dept)}</span>
    <h1 class="text-2xl font-bold mt-2">${m.name}</h1><p class="mut">${m.role}</p>
    <h2 class="font-semibold mt-5">${t('member_about')}</h2><p class="mt-1 text-sm leading-relaxed">${m.bio}</p>
    <h2 class="font-semibold mt-5">${t('member_expertise')}</h2><div class="flex flex-wrap gap-2 mt-2">${m.exp.map((x) => `<span class="chip">${x}</span>`).join('')}</div>
    <a href="#contact" class="btn mt-6">${t('member_contact')}</a></div></div></section>`;
    },

    contact: () => `<section class="max-w-2xl mx-auto px-4 pt-10"><h1 class="text-3xl font-bold">${t('service_inquiry')}</h1><p class="mut mt-2">${t('tell_us')}</p>
    <form id="f" class="card p-6 mt-6 grid gap-4" novalidate>
    <label>${t('name')}<input name="name" required autocomplete="name"></label>
    <label>${t('email')}<input name="email" type="email" required autocomplete="email"></label>
    <label>${t('phone_optional')}<input name="phone" type="tel"></label>
    <label>${t('service')}<select name="svc">${services.map((s) => `<option>${s.title}</option>`).join('')}</select></label>
    <div class="grid sm:grid-cols-2 gap-4"><label>${t('budget')}<select><option>${t('budget_under', { amount: budgetAmount(100) })}</option><option>${t('budget_mid', { min: budgetAmount(100), max: budgetAmount(500) })}</option><option>${t('budget_over', { amount: budgetAmount(500) })}</option></select></label>
    <label>${t('timeline')}<select><option>${t('timeline_asap')}</option><option>${t('timeline_1_3')}</option><option>${t('timeline_flexible')}</option></select></label></div>
    <label>${t('project_description')}<textarea name="msg" rows="4" required minlength="20"></textarea></label>
    <p id="err" class="text-sm text-red-500 hidden" role="alert"></p>
    <button class="btn">${t('send_inquiry')}</button></form></section>`,

    analyzer: () => `<section class="max-w-4xl mx-auto px-4 pt-10"><h1 class="text-3xl font-bold">${t('website_analyzer')}</h1><p class="mut mt-2">${t('analyzer_subtitle')}</p>
    <form id="az" class="card p-5 mt-6 flex flex-col sm:flex-row gap-3" novalidate><input name="u" placeholder="example.com" aria-label="${t('website_address')}" required><select name="dev" class="sm:!w-40" aria-label="${t('device')}"><option value="mobile">${t('mobile')}</option><option value="desktop">${t('desktop')}</option></select><button class="btn">${t('analyze')}</button></form>
    <p id="azerr" class="text-sm text-red-500 mt-2 hidden" role="alert"></p><div id="res" class="mt-6"><div class="card p-8 text-center mut">${t('enter_website')}</div></div></section>`,
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
    <div class="cta p-6 mt-4"><p class="font-semibold">${t('want_fixed')}</p><p class="text-sm opacity-90 mt-1">${t('want_fixed_desc')}</p><a href="#contact" class="btn mt-4" style="background:#fff;color:#b83b36">${t('request_seo_audit')}</a></div></div></div>`;
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

function bindTeam() {
    const grid = $('#team-grid');
    if (!grid) return;
    const buttons = document.querySelectorAll('#team-filter [data-dept]');
    const draw = (dept) => {
        const list = dept === 'all' ? team : team.filter((m) => m.dept === dept);
        grid.innerHTML = list.map(teamCard).join('');
        buttons.forEach((b) => b.classList.toggle('active', b.dataset.dept === dept));
    };
    buttons.forEach((b) => {
        b.onclick = () => draw(b.dataset.dept);
    });
    draw('all');
}

function bindPartnerLinks() {
    document.querySelectorAll('[data-partner-link]').forEach((el) => {
        el.onclick = (e) => {
            e.preventDefault();
            toast(t('partner_link_note'));
        };
    });
}

function openService(i) {
    const s = services[i];
    if (!s) return;
    const checkout = checkoutUrl('service', s.slug);
    $('#svc-modal-body').innerHTML = `<div class="flex items-start gap-3 pr-8"><div class="ico shrink-0">${s.i}</div><div><h2 id="svc-modal-title" class="text-xl font-bold leading-snug">${s.title}</h2><p class="mut text-sm mt-1">${s.desc}</p></div></div>
    <p class="mt-4 text-sm leading-relaxed">${s.long}</p>
    <h3 class="font-semibold mt-5">${t('service_modal_features')}</h3>
    <ul class="mt-2 space-y-2 text-sm">${s.f.map((x) => `<li class="flex gap-2"><span style="color:var(--br)" aria-hidden="true">✓</span><span>${x}</span></li>`).join('')}</ul>
    ${checkout ? `<a href="${checkout}" class="btn w-full mt-6">${t('buy_now')}</a>` : ''}
    <a href="#contact" class="btn btn-o w-full mt-3" onclick="window.NexusDigApp.closeService()">${t('service_modal_cta')}</a>`;
    const modal = $('#svc-modal');
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    $('button[data-close-svc]', modal)?.focus();
}

function closeService() {
    const modal = $('#svc-modal');
    if (!modal || modal.classList.contains('hidden')) return;
    modal.classList.add('hidden');
    document.body.style.overflow = '';
}

let netRAF = 0;
let netObs = null;

function stopNet() {
    if (netRAF) cancelAnimationFrame(netRAF);
    netRAF = 0;
    if (netObs) {
        netObs.disconnect();
        netObs = null;
    }
}

function startNet() {
    const canvas = $('#hero-net');
    if (!canvas) return;
    stopNet();
    const ctx = canvas.getContext('2d');
    if (!ctx) return;
    const reduce = matchMedia('(prefers-reduced-motion: reduce)').matches;
    const dpr = Math.min(window.devicePixelRatio || 1, 2);
    const color = getComputedStyle(document.documentElement).getPropertyValue('--br').trim() || '#f0726c';
    let w = 1;
    let h = 1;
    let points = [];

    const resize = () => {
        const rect = canvas.getBoundingClientRect();
        w = Math.max(rect.width, 1);
        h = Math.max(rect.height, 1);
        canvas.width = Math.round(w * dpr);
        canvas.height = Math.round(h * dpr);
        ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
        const count = Math.max(18, Math.min(90, Math.round((w * h) / 14000)));
        points = Array.from({ length: count }, () => ({
            x: Math.random() * w,
            y: Math.random() * h,
            vx: (Math.random() - 0.5) * 0.35,
            vy: (Math.random() - 0.5) * 0.35,
        }));
    };

    resize();
    if (typeof ResizeObserver !== 'undefined') {
        netObs = new ResizeObserver(resize);
        netObs.observe(canvas);
    }

    const frame = () => {
        ctx.clearRect(0, 0, w, h);
        for (const p of points) {
            p.x += p.vx;
            p.y += p.vy;
            if (p.x < 0 || p.x > w) p.vx *= -1;
            if (p.y < 0 || p.y > h) p.vy *= -1;
        }
        ctx.strokeStyle = color;
        ctx.lineWidth = 1;
        for (let i = 0; i < points.length; i += 1) {
            for (let j = i + 1; j < points.length; j += 1) {
                const dx = points[i].x - points[j].x;
                const dy = points[i].y - points[j].y;
                const d = Math.hypot(dx, dy);
                if (d < 130) {
                    ctx.globalAlpha = (1 - d / 130) * 0.32;
                    ctx.beginPath();
                    ctx.moveTo(points[i].x, points[i].y);
                    ctx.lineTo(points[j].x, points[j].y);
                    ctx.stroke();
                }
            }
        }
        ctx.globalAlpha = 0.7;
        ctx.fillStyle = color;
        for (const p of points) {
            ctx.beginPath();
            ctx.arc(p.x, p.y, 1.6, 0, Math.PI * 2);
            ctx.fill();
        }
        ctx.globalAlpha = 1;
        if (!reduce) netRAF = requestAnimationFrame(frame);
    };

    frame();
}

function updateTabs(k) {
    const map = {
        home: '#home',
        courses: '#courses',
        course: '#courses',
        services: '#services',
        team: '#team',
        member: '#team',
        analyzer: '#analyzer',
        contact: '#contact',
    };
    const activeHash = map[k] || '#home';
    document.querySelectorAll('.tab').forEach((tab) => {
        tab.classList.toggle('active', tab.getAttribute('href') === activeHash);
    });
}

function route() {
    closeService();
    stopNet();
    const [v, arg] = (location.hash.slice(1) || 'home').split('/');

    if (v === 'dashboard') {
        location.href = AUTH_URL || '/dashboard';
        return;
    }

    const k = V[v] ? v : 'home';
    const app = $('#app');
    app.innerHTML = `<div class="view">${V[k](arg)}</div>`;

    const titleMap = {
        home: t('nav_home'),
        courses: t('courses_title'),
        course: t('courses_title'),
        services: t('services_title'),
        team: t('our_team'),
        member: t('our_team'),
        analyzer: t('website_analyzer'),
        contact: t('service_inquiry'),
    };
    document.title = 'NexusDig – ' + (titleMap[k] || t('nav_home'));
    window.scrollTo(0, 0);

    const isActive = (h) => h === k || (k === 'course' && h === 'courses') || (k === 'member' && h === 'team');
    const activeAttrs = (h) => (isActive(h) ? ' class="active" aria-current="page"' : ' class=""');
    $('#nav').innerHTML = NAV.map(([h, l]) => `<a href="#${h}"${activeAttrs(h)}>${l}</a>`).join('');

    const accountLink = AUTH.check
        ? `<a href="${AUTH_URL}">${t('my_dashboard')}</a>`
        : `<a href="${AUTH.login}">${t('login')}</a>`;
    const accountExtra = AUTH.check
        ? `<form method="POST" action="${AUTH.logout}"><input type="hidden" name="_token" value="${CFG.csrf}"><button type="submit" class="text-left">${t('logout')}</button></form>`
        : `<a href="${AUTH.register}">${t('register')}</a>`;
    $('#mnav').innerHTML = NAV.map(([h, l]) => `<a href="#${h}"${activeAttrs(h)}>${l}</a>`).join('')
        + accountLink + accountExtra;
    $('#mnav').classList.add('hidden');
    $('#burger').setAttribute('aria-expanded', 'false');

    updateTabs(k);

    if (k === 'home') {
        requestAnimationFrame(startNet);
        bindPartnerLinks();
    }
    if (k === 'team') bindTeam();
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
        if ($('#hero-net')) startNet();
    };
    document.querySelectorAll('[data-close-svc]').forEach((el) => {
        el.onclick = closeService;
    });
    addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeService();
    });

    document.addEventListener('click', (e) => {
        document.querySelectorAll('header details[open]').forEach((details) => {
            if (!details.contains(e.target)) details.removeAttribute('open');
        });
    });

    document.querySelectorAll('[data-toggle-password]').forEach((button) => {
        button.onclick = () => {
            const input = document.getElementById(button.dataset.togglePassword);

            if (!input) return;

            const show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            button.textContent = show ? t('hide_password') : t('show_password');
            button.setAttribute('aria-pressed', String(show));
            input.focus();
        };
    });

    if ($('#app-content')) {
        addEventListener('hashchange', route);
        route();
    }
}

window.NexusDigApp = { init, toast, route, openService, closeService };

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
} else {
    init();
}

export { init, toast, route, openService, closeService };
