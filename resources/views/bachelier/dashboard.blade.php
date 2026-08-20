@extends('layouts.bachelier')

{{-- Active le design system PEUB sur cette vue uniquement.
     Le layout bachelier est partage avec 24 vues non encore migrees. --}}
@section('html-attrs', 'data-ds')

@section('title', 'Dashboard - Bachelier PEUB')

@php
    // Icones de type d opportunite et de ressource, tracees en SVG inline.
    $typeIcons = [
        'bourse' => ['M21.42 10.922a1 1 0 0 0-.019-1.838L12.83 5.18a2 2 0 0 0-1.66 0L2.6 9.08a1 1 0 0 0 0 1.832l8.57 3.908a2 2 0 0 0 1.66 0z', 'M22 10v6', 'M6 12.5V16a6 3 0 0 0 12 0v-3.5'],
        'stage' => ['M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16', 'M4 7h16a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2z'],
        'formation' => ['M12 7v14', 'M3 18a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h5a4 4 0 0 1 4 4 4 4 0 0 1 4-4h5a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1h-6a3 3 0 0 0-3 3 3 3 0 0 0-3-3z'],
        'defaut' => ['M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20', 'M12 6a6 6 0 1 0 0 12 6 6 0 0 0 0-12', 'M12 10a2 2 0 1 0 0 4 2 2 0 0 0 0-4'],
    ];
    $ressourceIcons = [
        'pdf' => ['M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7z', 'M14 2v4a2 2 0 0 0 2 2h4', 'M16 13H8', 'M16 17H8'],
        'video' => ['M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20', 'm10 8 6 4-6 4z'],
        'audio' => ['M3 14h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-7a9 9 0 0 1 18 0v7a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3'],
        'defaut' => ['M12 7v14', 'M3 18a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h5a4 4 0 0 1 4 4 4 4 0 0 1 4-4h5a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1h-6a3 3 0 0 0-3 3 3 3 0 0 0-3-3z'],
    ];

    $statutLibelles = [
        'pending' => 'En attente',
        'reviewed' => 'En cours',
        'accepted' => 'Acceptée',
        'rejected' => 'Refusée',
    ];

    $dotationTuiles = [
        ['Ordinateur', ['M20 16V7a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v9', 'M2.72 18.55A1 1 0 0 0 3.62 20h16.76a1 1 0 0 0 .9-1.45L20 16H4z']],
        ['Internet', ['M12 20h.01', 'M2 8.82a15 15 0 0 1 20 0', 'M5 12.86a10 10 0 0 1 14 0', 'M8.5 16.43a5 5 0 0 1 7 0']],
        ['IA Premium', ['M4 14a1 1 0 0 1-.78-1.63l9.9-10.2a.5.5 0 0 1 .86.46l-1.92 6.02A1 1 0 0 0 13 10h7a1 1 0 0 1 .78 1.63l-9.9 10.2a.5.5 0 0 1-.86-.46l1.92-6.02A1 1 0 0 0 11 14z']],
    ];

    $statTuiles = [
        [(int) ($stats['candidatures'] ?? 0), 'Mes candidatures'],
        [(int) ($stats['favoris'] ?? 0), 'Mes favoris'],
        [(int) ($stats['opportunites_disponibles'] ?? 0), 'Opportunités ouvertes'],
    ];
@endphp

@section('content')
<div class="ds-container ds-stack-lg" style="padding-block: var(--space-4)">

    {{-- En-tete de page : la page se nomme et nomme l utilisateur.
         Auparavant la vue s ouvrait sur un h2, sans aucun h1 sur mobile. --}}
    <header>
        <p class="ds-overline">DASHBOARD / ACCUEIL</p>
        <h1 style="margin-top: var(--space-1); font-size: clamp(var(--text-h1), 7vw, var(--text-display))">
            Tableau de bord
        </h1>
        <p class="ds-text-secondary" style="margin-top: var(--space-1)">
            {{ $bachelier->prenoms }} {{ $bachelier->nom }}, lauréat PEUB {{ $bachelier->annee_bac }}
        </p>
    </header>

    {{-- Statut boursier : remonte en tete, c est l information la plus structurante
         pour un bachelier admis. Il etait auparavant en bas de page, apres trois listes. --}}
    @if($bachelier->boursier_peub)
    <section class="ds-card" style="padding: var(--space-3)">
        <h2 style="font-size: var(--text-h3)">Statut Boursier PEUB</h2>
        <div style="margin-top: var(--space-2); display: grid; gap: var(--space-1-5); grid-template-columns: repeat(auto-fit, minmax(80px, 1fr))">
            @foreach ($dotationTuiles as [$libelle, $paths])
                <div class="ds-panel" style="padding: var(--space-2); text-align: center">
                    <span style="display:inline-grid; place-items:center; width:44px; height:44px; border-radius:var(--radius-chip); background:var(--accent-surface); color:var(--accent)">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                            @foreach ($paths as $d)<path d="{{ $d }}"/>@endforeach
                        </svg>
                    </span>
                    <p style="margin-top: var(--space-1); font-size: var(--text-caption); font-weight: var(--font-medium)">{{ $libelle }}</p>
                </div>
            @endforeach
        </div>
        <p class="ds-alert ds-alert-success" style="margin-top: var(--space-2)">
            Félicitations ! Vous bénéficiez de la dotation numérique complète.
        </p>
    </section>
    @else
    <section class="ds-card" style="padding: var(--space-3)">
        <h2 style="font-size: var(--text-h3)">Programme Boursier PEUB</h2>
        <p style="margin-top: var(--space-2); font-weight: var(--font-medium)">Critères de sélection :</p>
        <ul class="ds-text-secondary" style="margin-top: var(--space-1); list-style: disc; padding-left: var(--space-2); display: grid; gap: var(--space-0-5); font-size: var(--text-caption)">
            <li>Excellence académique (note BAC ≥ 320/400)</li>
            <li>Motivation et projet professionnel</li>
            <li>Situation socio-économique</li>
            <li>Engagement communautaire</li>
        </ul>
        <p class="ds-alert ds-alert-info" style="margin-top: var(--space-2)">
            Les boursiers sont sélectionnés automatiquement selon le score PEUB calculé à partir de votre profil.
        </p>
    </section>
    @endif

    {{-- Reperes chiffres : $stats etait calcule par le controleur et n etait affiche nulle part. --}}
    <section aria-label="Mes repères">
        <div style="display: grid; gap: var(--space-2); grid-template-columns: repeat(auto-fit, minmax(150px, 1fr))">
            @foreach ($statTuiles as [$valeur, $libelle])
                <div class="ds-card" style="padding: var(--space-2); text-align: center">
                    <p class="ds-stat ds-text-accent" style="font-size: clamp(var(--text-h2), 8vw, var(--text-display))">{{ $valeur }}</p>
                    <p class="ds-overline" style="margin-top: var(--space-0-5)">{{ $libelle }}</p>
                </div>
            @endforeach
        </div>
    </section>

    <div style="display: grid; gap: var(--space-3); grid-template-columns: repeat(auto-fit, minmax(300px, 1fr))">

        {{-- Opportunites recommandees --}}
        <section class="ds-card" style="padding: var(--space-3)">
            <div style="display:flex; align-items:center; justify-content:space-between; gap:var(--space-1)">
                <h2 style="font-size: var(--text-h3)">Opportunités recommandées</h2>
                <a href="{{ route('bachelier.opportunites') }}" class="ds-btn ds-btn-ghost ds-btn-sm">Voir tout</a>
            </div>

            <div style="margin-top: var(--space-2)">
                @forelse($dernieres_opportunites->take(5) as $opportunite)
                    @php $paths = $typeIcons[$opportunite->type] ?? $typeIcons['defaut']; @endphp
                    <a href="{{ route('bachelier.opportunites.show', $opportunite) }}"
                       style="display:flex; align-items:center; gap:var(--space-1-5); min-height:44px; padding:var(--space-1-5) 0; border-top:1px solid var(--border-default); color:inherit; text-decoration:none">
                        <span style="display:grid; place-items:center; width:36px; height:36px; flex-shrink:0; border-radius:var(--radius-chip); background:var(--accent-surface); color:var(--accent)">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                                @foreach ($paths as $d)<path d="{{ $d }}"/>@endforeach
                            </svg>
                        </span>
                        <span style="flex:1; min-width:0">
                            <span class="line-clamp-2" style="display:block; font-size:var(--text-caption); font-weight:var(--font-medium)">{{ $opportunite->titre }}</span>
                            <span class="ds-text-secondary" style="display:block; font-size:var(--text-label)">{{ $opportunite->partenaire->nom_organisation ?? 'Partenaire' }}</span>
                        </span>
                        @if($opportunite->date_limite_candidature && $opportunite->date_limite_candidature->isFuture())
                            <span class="ds-badge ds-badge-warning" style="flex-shrink:0">J-{{ now()->startOfDay()->diffInDays($opportunite->date_limite_candidature) }}</span>
                        @endif
                    </a>
                @empty
                    <div class="ds-panel" style="padding: var(--space-3); text-align:center">
                        <p class="ds-text-secondary">Aucune opportunité disponible</p>
                    </div>
                @endforelse
            </div>
        </section>

        {{-- Suivi des candidatures --}}
        <section class="ds-card" style="padding: var(--space-3)">
            <div style="display:flex; align-items:center; justify-content:space-between; gap:var(--space-1)">
                <h2 style="font-size: var(--text-h3)">Suivi des candidatures</h2>
                <a href="{{ route('bachelier.candidatures') }}" class="ds-btn ds-btn-ghost ds-btn-sm">Voir tout</a>
            </div>

            <div style="margin-top: var(--space-2)">
                @forelse($dernieres_candidatures->take(5) as $candidature)
                    <a href="{{ route('bachelier.candidatures.show', $candidature) }}"
                       style="display:flex; align-items:center; gap:var(--space-1-5); min-height:44px; padding:var(--space-1-5) 0; border-top:1px solid var(--border-default); color:inherit; text-decoration:none">
                        <span style="display:grid; place-items:center; width:36px; height:36px; flex-shrink:0; border-radius:var(--radius-chip); background:var(--accent-surface); color:var(--accent)">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                                <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M16 13H8"/><path d="M16 17H8"/>
                            </svg>
                        </span>
                        <span style="flex:1; min-width:0">
                            <span class="line-clamp-2" style="display:block; font-size:var(--text-caption); font-weight:var(--font-medium)">{{ $candidature->opportunite->titre }}</span>
                            <span class="ds-text-secondary" style="display:block; font-size:var(--text-label)">{{ $statutLibelles[$candidature->status] ?? ucfirst($candidature->status) }}</span>
                        </span>
                        @if($candidature->opportunite->date_limite_candidature && $candidature->opportunite->date_limite_candidature->isFuture())
                            <span class="ds-badge ds-badge-warning" style="flex-shrink:0">J-{{ now()->startOfDay()->diffInDays($candidature->opportunite->date_limite_candidature) }}</span>
                        @endif
                    </a>
                @empty
                    <div class="ds-panel" style="padding: var(--space-3); text-align:center">
                        <p class="ds-text-secondary">Aucune candidature en cours</p>
                        <a href="{{ route('bachelier.opportunites') }}" class="ds-btn ds-btn-secondary ds-btn-md" style="margin-top: var(--space-2)">
                            Découvrir des opportunités
                        </a>
                    </div>
                @endforelse
            </div>
        </section>
    </div>

    {{-- Ressources recentes --}}
    <section class="ds-card" style="padding: var(--space-3)">
        <div style="display:flex; align-items:center; justify-content:space-between; gap:var(--space-1)">
            <h2 style="font-size: var(--text-h3)">Ressources récentes</h2>
            <a href="{{ route('bachelier.library.index') }}" class="ds-btn ds-btn-ghost ds-btn-sm">Voir tout</a>
        </div>

        <div style="margin-top: var(--space-2)">
            @forelse($ressources_recentes->take(3) as $ressource)
                @php $paths = $ressourceIcons[$ressource->type] ?? $ressourceIcons['defaut']; @endphp
                <a href="{{ route('bachelier.library.show', $ressource) }}"
                   style="display:flex; align-items:center; gap:var(--space-1-5); min-height:44px; padding:var(--space-1-5) 0; border-top:1px solid var(--border-default); color:inherit; text-decoration:none">
                    <span style="display:grid; place-items:center; width:36px; height:36px; flex-shrink:0; border-radius:var(--radius-chip); background:var(--accent-surface); color:var(--accent)">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                            @foreach ($paths as $d)<path d="{{ $d }}"/>@endforeach
                        </svg>
                    </span>
                    <span style="flex:1; min-width:0">
                        <span class="line-clamp-2" style="display:block; font-size:var(--text-caption); font-weight:var(--font-medium)">{{ $ressource->title }}</span>
                        <span class="ds-text-secondary" style="display:block; font-size:var(--text-label)">{{ $ressource->category->name ?? 'Ressource' }}</span>
                    </span>
                </a>
            @empty
                <div class="ds-panel" style="padding: var(--space-3); text-align:center">
                    <p class="ds-text-secondary">Aucune ressource disponible</p>
                </div>
            @endforelse
        </div>
    </section>

</div>
@endsection
