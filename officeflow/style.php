<?php
// Shared inline stylesheet. Include this inside the <head> of each PHP page.
?>
<style>
:root{--ink:#172033;--muted:#697386;--line:#e5eaf2;--bg:#f5f7fb;--white:#fff;--blue:#315efb;--blue2:#2449d8;--green:#16845b;--amber:#a76505;--red:#bd3434;--shadow:0 14px 40px rgba(21,34,60,.07)}
*{box-sizing:border-box}html{scroll-behavior:smooth}body{margin:0;background:var(--bg);color:var(--ink);font:15px/1.55 Inter,ui-sans-serif,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif}a{color:var(--blue);text-decoration:none}a:hover{color:var(--blue2)}button,input,textarea,select{font:inherit}button,.btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;border:0;border-radius:10px;padding:11px 16px;background:var(--blue);color:#fff;font-weight:650;cursor:pointer;transition:.15s}button:hover,.btn:hover{background:var(--blue2);color:#fff;transform:translateY(-1px)}.btn.secondary,button.secondary{background:#edf1ff;color:#2547c7}.btn.ghost{background:transparent;color:var(--ink);border:1px solid var(--line)}.btn.danger{background:#fff0f0;color:var(--red)}.container{width:min(1160px,calc(100% - 36px));margin:auto}.topbar{background:#fff;border-bottom:1px solid var(--line);height:72px;display:flex;align-items:center}.nav{display:flex;align-items:center;justify-content:space-between;gap:20px}.brand{display:flex;align-items:center;gap:10px;color:var(--ink);font-size:19px;font-weight:800;letter-spacing:-.5px}.brand-mark{height:35px;width:35px;border-radius:11px;background:linear-gradient(145deg,#4776ff,#2449d8);display:grid;place-items:center;color:#fff;font-weight:900}.navlinks{display:flex;align-items:center;gap:22px;color:var(--muted)}.navlinks a{color:var(--muted)}.navlinks a:hover{color:var(--blue)}.hero{padding:82px 0 76px;background:radial-gradient(circle at 80% 10%,#e3eaff 0,transparent 30%),linear-gradient(180deg,#fff,#f7f9ff)}.hero-grid{display:grid;grid-template-columns:1.05fr .95fr;align-items:center;gap:52px}.eyebrow{display:inline-flex;align-items:center;gap:8px;padding:7px 11px;border-radius:999px;background:#edf1ff;color:#3155d4;font-size:12px;font-weight:800;text-transform:uppercase;letter-spacing:.08em}.hero h1{font-size:clamp(38px,5vw,60px);line-height:1.06;letter-spacing:-2.8px;margin:20px 0}.hero p{font-size:18px;line-height:1.7;color:var(--muted);max-width:560px}.actions{display:flex;gap:12px;flex-wrap:wrap;margin-top:28px}.preview{background:#fff;border:1px solid var(--line);border-radius:20px;padding:22px;box-shadow:var(--shadow);transform:rotate(1deg)}.preview-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:18px}.preview-title{font-size:18px;font-weight:800}.mini-stats{display:grid;grid-template-columns:repeat(3,1fr);gap:10px}.mini-stat{background:#f7f8fc;border:1px solid #edf0f5;border-radius:12px;padding:13px}.mini-stat b{display:block;font-size:22px}.mini-stat span{font-size:12px;color:var(--muted)}.preview-task{display:flex;gap:12px;align-items:center;border-bottom:1px solid var(--line);padding:15px 0}.preview-task:last-child{border:0}.fake-check{width:20px;height:20px;border:2px solid #cbd4e3;border-radius:6px;flex:none}.fake-check.done{background:#dcf7eb;border-color:#69c99c}.taskname{font-weight:700}.taskmeta{font-size:12px;color:var(--muted)}.section{padding:68px 0}.section.white{background:#fff}.section-heading{text-align:center;max-width:650px;margin:0 auto 35px}.section-heading h2{font-size:34px;letter-spacing:-1.2px;margin:8px 0}.section-heading p{color:var(--muted)}.feature-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:18px}.feature{padding:24px;background:#fff;border:1px solid var(--line);border-radius:16px}.feature-icon{width:42px;height:42px;border-radius:12px;background:#edf1ff;color:#315efb;display:grid;place-items:center;font-size:20px}.feature h3{margin:17px 0 7px}.feature p{margin:0;color:var(--muted)}.footer{padding:28px 0;background:#fff;border-top:1px solid var(--line);color:var(--muted);font-size:13px}.auth-wrap{min-height:calc(100vh - 72px);display:grid;place-items:center;padding:42px 18px}.auth-card{width:min(460px,100%);background:#fff;border:1px solid var(--line);border-radius:18px;padding:30px;box-shadow:var(--shadow)}.auth-card h1{margin:8px 0;font-size:28px;letter-spacing:-.8px}.muted{color:var(--muted)}.field{display:flex;flex-direction:column;gap:7px;margin:16px 0}.field label{font-size:13px;font-weight:700}.field input,.field textarea,.field select,.search-input{width:100%;padding:12px 13px;border:1px solid #dbe1eb;border-radius:10px;background:#fff;color:var(--ink);outline:none}.field input:focus,.field textarea:focus,.field select:focus,.search-input:focus{border-color:#7390ff;box-shadow:0 0 0 3px #e9eeff}.field textarea{min-height:110px;resize:vertical}.full{width:100%}.notice{padding:12px 15px;border-radius:10px;margin:0 0 18px;font-size:14px}.notice.success{background:#eaf8f0;color:#176743}.notice.error{background:#fff0f0;color:#a52929}.notice.warning{background:#fff7e8;color:#875000}.app{display:grid;grid-template-columns:240px minmax(0,1fr);min-height:calc(100vh - 72px)}.sidebar{background:#fff;border-right:1px solid var(--line);padding:24px 15px}.side-label{padding:12px 12px 7px;color:#98a1b2;text-transform:uppercase;font-size:10px;font-weight:800;letter-spacing:.12em}.side-link{display:flex;align-items:center;gap:11px;padding:11px 12px;border-radius:10px;color:#596579;font-weight:650;margin:3px 0}.side-link:hover,.side-link.active{background:#edf1ff;color:#2b50db}.side-link .ico{width:21px;text-align:center}.main{padding:30px;min-width:0}.page-head{display:flex;align-items:flex-start;justify-content:space-between;gap:20px;margin-bottom:24px}.page-head h1{font-size:28px;letter-spacing:-.9px;margin:0 0 5px}.page-head p{margin:0;color:var(--muted)}.stats{display:grid;grid-template-columns:repeat(4,1fr);gap:15px;margin-bottom:22px}.stat-card,.panel{background:#fff;border:1px solid var(--line);border-radius:15px;padding:20px}.stat-top{display:flex;align-items:center;justify-content:space-between;color:var(--muted);font-size:13px}.stat-icon{width:35px;height:35px;border-radius:10px;background:#edf1ff;color:#315efb;display:grid;place-items:center}.stat-number{font-size:30px;font-weight:800;letter-spacing:-1px;margin-top:12px}.stat-foot{font-size:12px;color:var(--muted)}.panel{margin-bottom:20px}.panel-head{display:flex;align-items:center;justify-content:space-between;gap:15px;margin-bottom:15px}.panel h2,.panel h3{margin:0;font-size:17px}.table-wrap{overflow:auto}table{border-collapse:collapse;width:100%;min-width:650px}th,td{text-align:left;padding:13px 12px;border-bottom:1px solid #edf0f5;font-size:13px;vertical-align:middle}th{font-size:11px;text-transform:uppercase;letter-spacing:.06em;color:#8b95a7;background:#fbfcfe}tr:last-child td{border-bottom:0}.badge{display:inline-flex;padding:4px 9px;border-radius:999px;font-size:11px;font-weight:800;background:#f0f2f6;color:#596579;white-space:nowrap}.badge.approved,.badge.completed,.badge.approved{background:#e7f8ef;color:#167349}.badge.pending,.badge.submitted{background:#fff4db;color:#8b5a00}.badge.in_progress{background:#eaf0ff;color:#2f54ce}.badge.overdue,.badge.rejected,.badge.revision_requested{background:#ffeded;color:#ad2f2f}.badge.high,.badge.urgent{background:#ffeded;color:#ad2f2f}.badge.medium{background:#fff4db;color:#8b5a00}.badge.low{background:#e7f8ef;color:#167349}.inline-form{display:flex;align-items:center;gap:7px;flex-wrap:wrap}.inline-form select{padding:8px;border:1px solid var(--line);border-radius:8px;background:#fff}.small-btn{padding:8px 10px;font-size:12px;border-radius:8px}.two-col{display:grid;grid-template-columns:1fr 1fr;gap:20px}.empty{padding:28px;text-align:center;color:var(--muted)}.task-cards{display:grid;grid-template-columns:repeat(2,1fr);gap:14px}.task-card{background:#fff;border:1px solid var(--line);border-radius:14px;padding:18px}.task-card h3{margin:10px 0 5px}.task-card p{color:var(--muted);font-size:13px}.task-card-meta{display:flex;gap:8px;flex-wrap:wrap;margin:12px 0}.chat-layout{display:grid;grid-template-columns:230px 1fr;min-height:520px;border:1px solid var(--line);border-radius:15px;overflow:hidden;background:#fff}.chat-people{border-right:1px solid var(--line);padding:15px}.chat-person{display:block;padding:11px;border-radius:9px;color:var(--ink);margin:4px 0}.chat-person.active,.chat-person:hover{background:#edf1ff}.chat-main{display:flex;flex-direction:column;min-width:0}.chat-messages{padding:20px;flex:1;max-height:500px;overflow:auto;background:#f9faff}.message{max-width:78%;margin:0 0 15px}.message.mine{margin-left:auto}.bubble{padding:11px 14px;border-radius:13px;background:#fff;border:1px solid var(--line);display:inline-block;white-space:pre-wrap;overflow-wrap:anywhere}.mine .bubble{background:#e9efff;border-color:#dce5ff}.message-meta{font-size:11px;color:var(--muted);margin:0 4px 4px}.chat-compose{display:flex;gap:10px;padding:15px;border-top:1px solid var(--line)}.chat-compose input{flex:1;min-width:0;padding:12px;border:1px solid var(--line);border-radius:10px}.profile-row{display:grid;grid-template-columns:140px 1fr;gap:24px}.avatar{width:82px;height:82px;border-radius:24px;background:#e7edff;color:#315efb;display:grid;place-items:center;font-size:30px;font-weight:800}.helper{font-size:12px;color:var(--muted)}.checkbox-row{display:flex;gap:10px;align-items:center}.checkbox-row input{width:auto}.actions-cell{display:flex;gap:6px;flex-wrap:wrap}
@media(max-width:900px){.hero-grid{grid-template-columns:1fr;gap:35px}.feature-grid{grid-template-columns:repeat(2,1fr)}.stats{grid-template-columns:repeat(2,1fr)}.app{grid-template-columns:1fr}.sidebar{border-right:0;border-bottom:1px solid var(--line);padding:10px;display:flex;gap:5px;overflow:auto}.side-label{display:none}.side-link{white-space:nowrap;margin:0}.main{padding:22px}.task-cards{grid-template-columns:1fr}}
@media(max-width:600px){.navlinks{gap:10px}.navlinks .optional{display:none}.hero{padding:54px 0}.hero h1{letter-spacing:-1.5px}.feature-grid,.two-col{grid-template-columns:1fr}.stats{gap:9px}.stat-card{padding:14px}.stat-number{font-size:25px}.main{padding:15px}.page-head{flex-direction:column}.chat-layout{grid-template-columns:1fr}.chat-people{display:flex;overflow:auto;border-right:0;border-bottom:1px solid var(--line)}.chat-person{white-space:nowrap}.profile-row{grid-template-columns:1fr}.mini-stats{grid-template-columns:1fr 1fr 1fr}.preview{padding:15px}.auth-card{padding:23px}.navlinks a{font-size:13px}}

/* ===== OfficeFlow Landing Page Animations ===== */

@keyframes fadeUp {
    from {
        opacity: 0;
        transform: translateY(25px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes float {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-10px);
    }
}

/* Add these classes to landing page elements */
.home-animate {
    animation: fadeUp 0.8s ease both;
}

.home-animate-delay-1 {
    animation: fadeUp 0.8s ease 0.15s both;
}

.home-animate-delay-2 {
    animation: fadeUp 0.8s ease 0.3s both;
}

.home-animate-delay-3 {
    animation: fadeUp 0.8s ease 0.45s both;
}

.home-fade {
    animation: fadeIn 1s ease both;
}

.home-float {
    animation: float 4s ease-in-out infinite;
}

/* Respect visitors who prefer less motion */
@media (prefers-reduced-motion: reduce) {
    .home-animate,
    .home-animate-delay-1,
    .home-animate-delay-2,
    .home-animate-delay-3,
    .home-fade,
    .home-float {
        animation: none !important;
    }
}

/* ===== Scroll Reveal Effects ===== */

.reveal {
    opacity: 0;
    transform: translateY(25px);
    transition: opacity 0.7s ease, transform 0.7s ease;
}

.reveal.is-visible {
    opacity: 1;
    transform: translateY(0);
}

@media (prefers-reduced-motion: reduce) {
    .reveal,
    .reveal.is-visible {
        opacity: 1;
        transform: none;
        transition: none;
    }
}

/* ===== Mobile Responsive Improvements ===== */

@media (max-width: 768px) {
    .container {
        width: 92%;
    }

    .hero-grid {
        grid-template-columns: 1fr;
        gap: 28px;
    }

    .hero {
        padding-top: 40px;
        padding-bottom: 40px;
    }

    .hero h1 {
        font-size: clamp(32px, 8vw, 44px);
    }

    .preview {
        width: 100%;
        box-sizing: border-box;
    }

    .feature-grid {
        grid-template-columns: 1fr;
    }

    .nav {
        flex-wrap: wrap;
        gap: 12px;
    }

    .navlinks {
        flex-wrap: wrap;
        gap: 10px;
    }

    .actions {
        flex-wrap: wrap;
    }

    .actions .btn {
        max-width: 100%;
    }

    .app {
        grid-template-columns: 1fr;
    }

    .sidebar {
        width: auto;
        position: static;
    }

    .main {
        min-width: 0;
        padding: 20px 14px;
    }
}

@media (max-width: 480px) {
    .mini-stats {
        gap: 8px;
    }

    .mini-stat {
        min-width: 0;
        padding: 10px 6px;
    }

    .mini-stat b {
        font-size: 20px;
    }

    .btn {
        padding: 11px 14px;
    }
}

</style>
