{{-- Opportunites : les six categories du programme.
     Bloc editorial, volontairement non branche sur $featured_opportunities :
     ce sont des categories, pas des enregistrements. --}}
@php
    $opportunityCategories = [
        [
            'type' => 'bourse',
            'image' => 'images/opportunites/bourses.jpg',
            'title' => "Bourses d'études",
            'badge' => '45+ disponibles',
            'description' => "Accédez à un financement complet pour vos études supérieures en Côte d'Ivoire et à l'international",
            'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
        ],
        [
            'type' => 'stage',
            'image' => 'images/opportunites/stages.jpg',
            'title' => 'Stages & Emplois',
            'badge' => '78+ disponibles',
            'description' => "Découvrez des opportunités professionnelles adaptées à votre profil et vos ambitions",
            'icon' => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
        ],
        [
            'type' => 'formation',
            'image' => 'images/opportunites/formations.jpg',
            'title' => 'Formations',
            'badge' => '32+ disponibles',
            'description' => "Développez vos compétences avec des formations certifiantes et des programmes d'excellence",
            'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
        ],
        [
            'type' => 'event',
            'image' => 'images/opportunites/evenements.jpg',
            'title' => 'Événements',
            'badge' => '12+ disponibles',
            'description' => "Participez à des masterclass, conférences et événements exclusifs pour enrichir votre réseau",
            'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
        ],
        [
            'type' => 'concours',
            'image' => 'images/opportunites/concours.jpg',
            'title' => 'Concours',
            'badge' => '8+ disponibles',
            'description' => "Relevez des défis d'excellence et mesurez-vous aux meilleurs talents de votre génération",
            'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
        ],
        [
            'type' => 'promotion',
            'image' => 'images/opportunites/promotion.jpg',
            'title' => 'Promotions',
            'badge' => 'Nouvelles',
            'description' => "Bénéficiez d'offres exclusives et de réductions spéciales négociées avec nos partenaires",
            'icon' => 'M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7',
        ],
    ];
@endphp

<section id="opportunities" class="ds-bg-raised" style="padding-block: clamp(var(--space-6), 9vw, var(--space-10))">
    <div class="ds-container ds-stack-lg">

        <header style="text-align: center; max-width: 62ch; margin-inline: auto">
            <h2 style="font-size: clamp(var(--text-h2), 6.5vw, var(--text-display))">
                TYPES D'OPPORTUNITÉS
                <span style="display: block; color: var(--accent); font-weight: var(--font-regular)">
                    Disponibles
                </span>
            </h2>
            <p class="ds-overline" style="margin-top: var(--space-1-5)">
                EXPLOREZ TOUTES LES POSSIBILITÉS QUI S'OFFRENT À VOUS
            </p>
        </header>

        <div style="display: grid; gap: var(--space-2); grid-template-columns: repeat(auto-fit, minmax(260px, 1fr))">
            @foreach ($opportunityCategories as $category)
                <a href="{{ route('auth.login', ['redirect_to' => route('bachelier.opportunites', ['type' => $category['type']])]) }}"
                   class="ds-card-interactive"
                   style="display: flex; flex-direction: column; overflow: hidden; text-decoration: none; color: inherit">

                    <img src="{{ asset($category['image']) }}"
                         alt=""
                         width="800" height="450"
                         loading="lazy" decoding="async"
                         style="width: 100%; aspect-ratio: 16 / 9; object-fit: cover; display: block">

                    <div style="padding: var(--space-2); display: flex; flex-direction: column; gap: var(--space-1); flex: 1">

                        <div style="display: flex; align-items: center; justify-content: space-between; gap: var(--space-1)">
                            <span style="width: 44px; height: 44px; flex-shrink: 0; display: grid; place-items: center; border-radius: var(--radius-chip); background: var(--accent-surface); color: var(--accent)">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                                    <path d="{{ $category['icon'] }}"/>
                                </svg>
                            </span>
                            <span class="ds-badge ds-badge-accent">{{ $category['badge'] }}</span>
                        </div>

                        <h3>{{ $category['title'] }}</h3>

                        <p class="ds-text-secondary" style="font-size: var(--text-caption)">
                            {{ $category['description'] }}
                        </p>

                        {{-- Affordance permanente : l ancienne version cachait ce libelle dans une
                             couche revelee au survol seulement, invisible au tactile et au clavier. --}}
                        <span style="margin-top: auto; padding-top: var(--space-1); display: inline-flex; align-items: center; gap: var(--space-0-5); font-size: var(--text-caption); font-weight: var(--font-semibold)">
                            Se connecter pour voir les détails
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

        <div style="text-align: center">
            <a href="{{ route('partenaire.register') }}" class="ds-btn ds-btn-primary ds-btn-lg">
                Devenir partenaire
            </a>
        </div>

    </div>
</section>
