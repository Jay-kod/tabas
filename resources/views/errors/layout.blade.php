<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ?? 'Error' }} - {{ config('app.name', 'TABAS') }}</title>
        <style>
            :root {
                color-scheme: dark;
                --bg-1: #020617;
                --bg-2: #0f172a;
                --card: rgba(15, 23, 42, 0.78);
                --line: rgba(148, 163, 184, 0.22);
                --text: #e2e8f0;
                --muted: #94a3b8;
            }

            * { box-sizing: border-box; }
            body {
                margin: 0;
                min-height: 100vh;
                font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
                color: var(--text);
                background:
                    radial-gradient(circle at top left, rgba(56, 189, 248, 0.25), transparent 30%),
                    radial-gradient(circle at bottom right, rgba(16, 185, 129, 0.18), transparent 28%),
                    linear-gradient(160deg, var(--bg-1), var(--bg-2));
            }

            .shell {
                min-height: 100vh;
                display: grid;
                place-items: center;
                padding: 24px;
            }

            .card {
                width: min(900px, 100%);
                border: 1px solid var(--line);
                border-radius: 28px;
                background: var(--card);
                backdrop-filter: blur(24px);
                box-shadow: 0 30px 80px rgba(2, 6, 23, 0.45);
                overflow: hidden;
            }

            .content {
                padding: clamp(28px, 5vw, 56px);
                display: grid;
                gap: 28px;
            }

            .eyebrow {
                display: inline-flex;
                align-items: center;
                gap: 10px;
                color: var(--muted);
                font-size: 12px;
                letter-spacing: 0.26em;
                text-transform: uppercase;
            }

            .code {
                display: inline-grid;
                place-items: center;
                width: 84px;
                height: 84px;
                border-radius: 22px;
                background: rgba(56, 189, 248, 0.16);
                color: #e0f2fe;
                font-size: 28px;
                font-weight: 800;
            }

            h1 {
                margin: 0;
                font-size: clamp(2rem, 4vw, 3.6rem);
                line-height: 1.05;
            }

            p {
                margin: 0;
                color: var(--muted);
                font-size: 1rem;
                line-height: 1.8;
            }

            .actions {
                display: flex;
                flex-wrap: wrap;
                gap: 12px;
            }

            .button {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                min-height: 46px;
                padding: 0 18px;
                border-radius: 999px;
                border: 1px solid transparent;
                text-decoration: none;
                font-weight: 700;
                transition: transform 0.2s ease, border-color 0.2s ease, background 0.2s ease;
            }

            .button:hover { transform: translateY(-1px); }
            .button.primary { background: linear-gradient(135deg, #38bdf8, #0ea5e9); color: white; }
            .button.secondary { border-color: var(--line); background: rgba(15, 23, 42, 0.45); color: var(--text); }

            .panel {
                border-top: 1px solid var(--line);
                background: rgba(2, 6, 23, 0.3);
                padding: 18px 24px;
                color: var(--muted);
                font-size: 14px;
            }

            @media (min-width: 768px) {
                .content {
                    grid-template-columns: 1fr auto;
                    align-items: start;
                }
            }
        </style>
    </head>
    <body>
        <main class="shell">
            <section class="card" aria-labelledby="error-title">
                <div class="content">
                    <div>
                        <div class="eyebrow">TABAS system error</div>
                        <h1 id="error-title">@yield('heading')</h1>
                        <div style="margin-top: 18px; max-width: 42rem;">
                            <p>@yield('message')</p>
                        </div>

                        <div style="margin-top: 28px;" class="actions">
                            @yield('actions')
                        </div>
                    </div>

                    <div class="code">@yield('code')</div>
                </div>

                <div class="panel">
                    TABAS is a clinical workflow prototype. If this error repeats, refresh the page or contact the system administrator.
                </div>
            </section>
        </main>
    </body>
</html>
