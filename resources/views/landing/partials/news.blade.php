{{-- Actualites : les trois derniers articles publies.
     ANSUT : le sous-titre script « Confiance » a ete retire ici, c etait un copier-coller
     de partners.blade.php sous un titre « ACTUALITÉS PEUB ». Mot a fournir si le motif
     en deux lignes doit revenir sur cette section. --}}
@php
    $newsCategories = [
        'annonce' => ['ds-badge-accent', 'Annonce'],
        'success' => ['ds-badge-success', 'Réussite'],
        'evenement' => ['ds-badge-info', 'Événement'],
        'partenariat' => ['ds-badge-accent', 'Partenariat'],
        'formation' => ['ds-badge-neutral', 'Formation'],
        'conseil' => ['ds-badge-warning', 'Conseil'],
        'interview' => ['ds-badge-neutral', 'Interview'],
        'actualite' => ['ds-badge-neutral', 'Actualité'],
    ];
@endphp

<section id="news" class="ds-bg-raised ds-bloom" style="padding-block: clamp(var(--space-6), 9vw, var(--space-10))">
    <div class="ds-container ds-stack-lg">

        <header style="text-align: center; max-width: 62ch; margin-inline: auto">
            <h2 style="font-size: clamp(var(--text-h2), 6.5vw, var(--text-display))">
                ACTUALITÉS PEUB
            </h2>
            <p class="ds-overline" style="margin-top: var(--space-1-5)">
                RESTEZ INFORMÉ DES DERNIÈRES NOUVELLES ET ÉVÉNEMENTS
            </p>
            <div style="margin-top: var(--space-3)">
                <a href="{{ route('actualites') }}" class="ds-btn ds-btn-primary ds-btn-lg">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                        <path d="M4 22h16a2 2 0 002-2V4a2 2 0 00-2-2H8a2 2 0 00-2 2v16a2 2 0 01-2 2zm0 0a2 2 0 01-2-2v-9h4M18 14h-8M15 18h-5M10 6h8v4h-8V6z"/>
                    </svg>
                    Voir toutes les actualités
                </a>
            </div>
        </header>

        @if($featured_articles->isNotEmpty())
            <div style="display: grid; gap: var(--space-2); grid-template-columns: repeat(auto-fit, minmax(260px, 1fr))">
                @foreach($featured_articles as $article)
                    @php
                        [$badgeClass, $badgeLabel] = $newsCategories[$article->categorie] ?? $newsCategories['actualite'];
                    @endphp
                    <a href="{{ route('actualite', $article->slug) }}"
                       class="ds-card-interactive"
                       style="display: flex; flex-direction: column; overflow: hidden; text-decoration: none; color: inherit">

                        <div style="position: relative; aspect-ratio: 16 / 9; background: var(--surface-secondary)">
                            @if($article->image_principale)
                                <img src="{{ asset('storage/' . $article->image_principale) }}"
                                     alt=""
                                     loading="lazy" decoding="async"
                                     style="width: 100%; height: 100%; object-fit: cover; display: block">
                            @else
                                <span style="position: absolute; inset: 0; display: grid; place-items: center; color: var(--text-secondary)">
                                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                                        <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6zM14 2v6h6M16 13H8M16 17H8M10 9H8"/>
                                    </svg>
                                </span>
                            @endif

                            @if($article->featured)
                                <span class="ds-badge ds-badge-solid" style="position: absolute; top: var(--space-1); left: var(--space-1)">
                                    À la une
                                </span>
                            @endif
                        </div>

                        <div style="padding: var(--space-2); display: flex; flex-direction: column; gap: var(--space-1); flex: 1">

                            <div style="display: flex; align-items: center; gap: var(--space-1); flex-wrap: wrap">
                                <span class="ds-badge {{ $badgeClass }}">{{ $badgeLabel }}</span>
                                <time datetime="{{ $article->date_publication->toDateString() }}"
                                      class="ds-text-secondary"
                                      style="font-size: var(--text-caption)">
                                    {{ $article->date_publication->locale('fr')->translatedFormat('j F Y') }}
                                </time>
                            </div>

                            <h3 class="line-clamp-2">{{ $article->titre }}</h3>

                            <p class="ds-text-secondary line-clamp-3" style="font-size: var(--text-caption)">
                                {{ $article->excerpt }}
                            </p>

                            <span style="margin-top: auto; padding-top: var(--space-1); display: inline-flex; align-items: center; gap: var(--space-0-5); font-size: var(--text-caption); font-weight: var(--font-semibold)">
                                Lire la suite
                                <span class="ds-text-accent" style="display: inline-flex">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                                        <path d="M5 12h14M13 6l6 6-6 6"/>
                                    </svg>
                                </span>
                            </span>

                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="ds-panel" style="padding: var(--space-6); text-align: center">
                <span class="ds-text-secondary" style="display: inline-flex">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                        <path d="M4 22h16a2 2 0 002-2V4a2 2 0 00-2-2H8a2 2 0 00-2 2v16a2 2 0 01-2 2zm0 0a2 2 0 01-2-2v-9h4M18 14h-8M15 18h-5M10 6h8v4h-8V6z"/>
                    </svg>
                </span>
                <h3 style="margin-top: var(--space-2)">Aucun article disponible</h3>
                <p class="ds-text-secondary" style="margin-top: var(--space-1)">
                    Les dernières actualités seront bientôt disponibles.
                </p>
            </div>
        @endif

        {{-- Newsletter : le formulaire n a jamais eu de backend (ni <form>, ni route).
             Conserve tel quel, le libelle etant valide par ANSUT. --}}
        <div class="ds-surface-brand" style="border-radius: var(--radius-card); padding: clamp(var(--space-3), 6vw, var(--space-6)); text-align: center">
            <h3 style="font-size: clamp(var(--text-h2), 6vw, var(--text-display))">Restez informé</h3>
            <p style="margin-top: var(--space-1); margin-inline: auto; max-width: 52ch; opacity: .85">
                Abonnez-vous à notre newsletter pour recevoir les dernières actualités PEUB
            </p>
            <div style="margin-top: var(--space-3); margin-inline: auto; max-width: 520px; display: grid; gap: var(--space-1-5); grid-template-columns: repeat(auto-fit, minmax(220px, 1fr))">
                <label for="newsletter-email" class="sr-only">Votre adresse email</label>
                <input id="newsletter-email"
                       type="email"
                       name="email"
                       autocomplete="email"
                       class="ds-field"
                       placeholder="Votre adresse email">
                <button type="button" class="ds-btn ds-btn-highlight ds-btn-md" style="height: 48px">
                    S'abonner
                </button>
            </div>
        </div>

    </div>
</section>
