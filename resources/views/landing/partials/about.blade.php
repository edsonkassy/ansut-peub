{{-- A propos : presentation ANSUT et les trois piliers du programme. --}}
<section id="about" class="ds-bg-surface" style="padding-block: clamp(var(--space-6), 9vw, var(--space-10))">
    <div class="ds-container ds-stack-lg">

        <div style="display: grid; gap: var(--space-4); grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); align-items: center">

            <div class="ds-stack-sm">
                <h2 style="font-size: clamp(var(--text-h2), 6.5vw, var(--text-display))">
                    UNE PLATEFORME INTELLIGENTE
                    <span style="display: block; color: var(--accent); font-weight: var(--font-regular)">
                        Pour l'Excellence
                    </span>
                </h2>

                <p class="ds-text-secondary" style="display: flex; align-items: center; gap: var(--space-1); font-size: var(--text-caption)">
                    Une initiative de
                    <img src="{{ asset('images/logo_ansut_original.png') }}"
                         alt="ANSUT"
                         width="96" height="32"
                         loading="lazy" decoding="async"
                         style="height: 32px; width: auto">
                </p>

                <p style="max-width: 62ch">
                    <strong>L'Agence Nationale du Service Universel des Télécommunications (ANSUT)</strong>
                    s'engage à transformer l'éducation en Côte d'Ivoire en connectant l'excellence
                    académique aux meilleures opportunités mondiales.
                </p>

                <p class="ds-text-secondary" style="max-width: 62ch">
                    PEUB représente notre vision d'un avenir où chaque talent ivoirien peut accéder
                    aux ressources et opportunités nécessaires pour exceller sur la scène internationale.
                </p>

                <div style="margin-top: var(--space-2); display: grid; gap: var(--space-1-5); grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); max-width: 520px">
                    <a href="{{ route('auth.register') }}" class="ds-btn ds-btn-primary ds-btn-lg">
                        S'inscrire maintenant
                    </a>
                    <a href="{{ route('faq') }}" class="ds-btn ds-btn-secondary ds-btn-lg">
                        Questions fréquentes
                    </a>
                </div>
            </div>

            {{-- about.jpg (1,6 Mo) retenu a la place de about.png (2,4 Mo), meme visuel. --}}
            <img src="{{ asset('images/about.jpg') }}"
                 alt="Plateforme PEUB - Excellence académique"
                 width="1200" height="800"
                 loading="lazy" decoding="async"
                 style="width: 100%; height: auto; display: block; border-radius: var(--radius-card)">

        </div>

        <div style="display: grid; gap: var(--space-2); grid-template-columns: repeat(auto-fit, minmax(260px, 1fr))">

            <div class="ds-card" style="padding: var(--space-3)">
                <span style="width: 44px; height: 44px; display: grid; place-items: center; border-radius: var(--radius-chip); background: var(--accent-surface); color: var(--accent)">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </span>
                <h3 style="margin-top: var(--space-2)">Sélection d'Excellence</h3>
                <p class="ds-text-secondary" style="margin-top: var(--space-1); font-size: var(--text-caption)">
                    Processus rigoureux de sélection pour identifier et accompagner les meilleurs talents.
                </p>
                <ul class="ds-text-secondary" style="margin-top: var(--space-2); list-style: disc; padding-left: var(--space-2); display: grid; gap: var(--space-0-5); font-size: var(--text-caption)">
                    <li>Critères académiques stricts</li>
                    <li>Évaluation par intelligence artificielle</li>
                    <li>Jury d'experts multidisciplinaires</li>
                </ul>
            </div>

            <div class="ds-card" style="padding: var(--space-3)">
                <span style="width: 44px; height: 44px; display: grid; place-items: center; border-radius: var(--radius-chip); background: var(--accent-surface); color: var(--accent)">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                        <path d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </span>
                <h3 style="margin-top: var(--space-2)">Opportunités Premium</h3>
                <p class="ds-text-secondary" style="margin-top: var(--space-1); font-size: var(--text-caption)">
                    Accès exclusif aux meilleures bourses, stages et formations de partenaires vérifiés.
                </p>
                <ul class="ds-text-secondary" style="margin-top: var(--space-2); list-style: disc; padding-left: var(--space-2); display: grid; gap: var(--space-0-5); font-size: var(--text-caption)">
                    <li>Bourses d'études internationales</li>
                    <li>Stages dans des entreprises leaders</li>
                    <li>Formations spécialisées gratuites</li>
                </ul>
            </div>

            <div class="ds-card" style="padding: var(--space-3)">
                <span style="width: 44px; height: 44px; display: grid; place-items: center; border-radius: var(--radius-chip); background: var(--accent-surface); color: var(--accent)">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                        <path d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                </span>
                <h3 style="margin-top: var(--space-2)">IA Personnalisée</h3>
                <p class="ds-text-secondary" style="margin-top: var(--space-1); font-size: var(--text-caption)">
                    Assistant intelligent pour l'orientation, les conseils et les recommandations personnalisées.
                </p>
                <ul class="ds-text-secondary" style="margin-top: var(--space-2); list-style: disc; padding-left: var(--space-2); display: grid; gap: var(--space-0-5); font-size: var(--text-caption)">
                    <li>Analyse de profil avancée</li>
                    <li>Recommandations personnalisées</li>
                    <li>Accompagnement 24/7</li>
                </ul>
            </div>

        </div>

    </div>
</section>
