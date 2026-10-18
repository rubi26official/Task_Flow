
<?php
require_once 'partials.php';
header_html('Office task management');
?>

<section class="hero">
    <div class="container hero-grid">
        <div>
            <span class="eyebrow">A calmer way to work together</span>

            <h1 class="home-animate">
                Good work starts with
                <span style="color:#315efb">clear tasks.</span>
            </h1>

            <p class="home-animate home-animate-delay-1">
                One simple workspace for assigning work, tracking progress,
                and keeping your whole office connected. Less chasing.
                More getting things done.
            </p>

            <div class="actions home-animate home-animate-delay-2">
                <a class="btn" href="register.php">
                    Join your team <span>→</span>
                </a>
                <a class="btn ghost" href="login.php">
                    Sign in to workspace
                </a>
            </div>

            <p style="font-size:13px;margin-top:18px">
                Made for small teams that value clarity, ownership,
                and on-time delivery.
            </p>
        </div>

        <div class="preview home-float">
            <div class="preview-head">
                <div>
                    <div class="preview-title">Your work overview</div>
                    <div class="muted" style="font-size:12px">
                        A little more organized, every day.
                    </div>
                </div>
                <span class="badge approved">On track</span>
            </div>

            <div class="mini-stats">
                <div class="mini-stat">
                    <b>08</b>
                    <span>In progress</span>
                </div>
                <div class="mini-stat">
                    <b>12</b>
                    <span>Completed</span>
                </div>
                <div class="mini-stat">
                    <b>03</b>
                    <span>Due soon</span>
                </div>
            </div>

            <h3 style="font-size:14px;margin:22px 0 0">
                Today's focus
            </h3>

            <div class="preview-task">
                <span class="fake-check done"></span>
                <div>
                    <div class="taskname">Prepare client summary</div>
                    <div class="taskmeta">Completed · 10:15 AM</div>
                </div>
            </div>

            <div class="preview-task">
                <span class="fake-check"></span>
                <div>
                    <div class="taskname">Review weekly report</div>
                    <div class="taskmeta">High priority · Due today</div>
                </div>
            </div>

            <div class="preview-task">
                <span class="fake-check"></span>
                <div>
                    <div class="taskname">Share project update</div>
                    <div class="taskmeta">In progress · Due tomorrow</div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section white" id="features">
    <div class="container">
        <div class="section-heading reveal">
            <span class="eyebrow">Everything in one place</span>
            <h2>Simple tools. Better teamwork.</h2>
            <p>
                Keep assignments, deadlines, conversations, and progress
                in one dependable workspace.
            </p>
        </div>

        <div class="feature-grid">
            <article class="feature reveal">
                <div class="feature-icon">☷</div>
                <h3>Clear task ownership</h3>
                <p>
                    Assign work to one person or the whole team, with
                    priorities and due dates everyone understands.
                </p>
            </article>

            <article class="feature reveal">
                <div class="feature-icon">✓</div>
                <h3>Progress you can trust</h3>
                <p>
                    See what's in progress, overdue, submitted for review,
                    or completed without chasing updates.
                </p>
            </article>

            <article class="feature reveal">
                <div class="feature-icon">◌</div>
                <h3>Team communication</h3>
                <p>
                    Keep office group messages and direct conversations
                    close to the work that matters.
                </p>
            </article>

            <article class="feature reveal">
                <div class="feature-icon">♙</div>
                <h3>Controlled access</h3>
                <p>
                    New members request access, and an admin approves them
                    before they enter the workspace.
                </p>
            </article>

            <article class="feature reveal">
                <div class="feature-icon">◷</div>
                <h3>Deadlines in view</h3>
                <p>
                    Make upcoming work and overdue tasks visible, so
                    important dates are less likely to be missed.
                </p>
            </article>

            <article class="feature reveal">
                <div class="feature-icon">▤</div>
                <h3>A clear activity trail</h3>
                <p>
                    Keep task status and submissions organized, with
                    room to add reporting and audit history.
                </p>
            </article>
        </div>
    </div>
</section>

<section class="section" id="how">
    <div class="container">
        <div class="section-heading reveal">
            <span class="eyebrow">How it works</span>
            <h2>From assignment to done.</h2>
            <p>
                A straightforward workflow keeps everyone on the same page.
            </p>
        </div>

        <div class="feature-grid">
            <article class="feature reveal">
                <div class="feature-icon">01</div>
                <h3>Request access</h3>
                <p>
                    Team members register and wait for the admin to
                    approve their account.
                </p>
            </article>

            <article class="feature reveal">
                <div class="feature-icon">02</div>
                <h3>Get assigned</h3>
                <p>
                    The admin creates tasks for individuals or assigns
                    a shared task to the whole team.
                </p>
            </article>

            <article class="feature reveal">
                <div class="feature-icon">03</div>
                <h3>Submit and review</h3>
                <p>
                    Members submit finished work; the admin can approve
                    it or request a revision.
                </p>
            </article>
        </div>

        <div style="text-align:center;margin-top:32px">
            <a class="btn" href="register.php">Get started →</a>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const elements = document.querySelectorAll('.reveal');

    if (!('IntersectionObserver' in window)) {
        elements.forEach(function (element) {
            element.classList.add('is-visible');
        });
        return;
    }

    const observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.12
    });

    elements.forEach(function (element) {
        observer.observe(element);
    });
});
</script>

<?php footer_html(); ?>
