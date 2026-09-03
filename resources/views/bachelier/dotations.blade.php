@extends('layouts.bachelier')

{{-- Active le design system PEUB sur cette vue uniquement. --}}
@section('html-attrs', 'data-ds')

@section('title', 'Mes Dotations - Bachelier PEUB')

@php
    // Memes traces que le bloc de dotation du tableau de bord, livre au lot 1.
    $dotationIcones = [
        'ordinateur_portable' => ['M20 16V7a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v9', 'M2.72 18.55A1 1 0 0 0 3.62 20h16.76a1 1 0 0 0 .9-1.45L20 16H4z'],
        'connexion_internet' => ['M12 20h.01', 'M2 8.82a15 15 0 0 1 20 0', 'M5 12.86a10 10 0 0 1 14 0', 'M8.5 16.43a5 5 0 0 1 7 0'],
        'abonnement_ia' => ['M4 14a1 1 0 0 1-.78-1.63l9.9-10.2a.5.5 0 0 1 .86.46l-1.92 6.02A1 1 0 0 0 13 10h7a1 1 0 0 1 .78 1.63l-9.9 10.2a.5.5 0 0 1-.86-.46l1.92-6.02A1 1 0 0 0 11 14z'],
        'defaut' => ['M20 12v10H4V12', 'M2 7h20v5H2z', 'M12 22V7', 'M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7', 'M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7'],
    ];

    // Roles de statut dedies de theme.css, plutot que des aplats de palette.
    $statutRoles = [
        'active' => ['accepted', 'Active'],
        'en_attente' => ['pending', 'En attente'],
        'suspendue' => ['rejected', 'Suspendue'],
        'terminee' => ['draft', 'Terminée'],
        'retournee' => ['review', 'Retournée'],
    ];
@endphp

@section('content')
<div class="ds-container ds-stack" style="padding-block: var(--space-4)">

    <header>
        <p class="ds-overline">MES DOTATIONS / ÉQUIPEMENTS PEUB</p>
        <h1 style="margin-top: var(--space-1)">Mes dotations</h1>
        @if($dotations->isNotEmpty())
        <p class="ds-text-secondary" style="margin-top: var(--space-1)">
            {{ $dotations->count() }} {{ $dotations->count() > 1 ? 'équipements attribués' : 'équipement attribué' }}
        </p>
        @endif
    </header>

    @if(session('success'))
        <p class="ds-alert ds-alert-success" role="status">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false" style="flex-shrink:0">
                <path d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20"/><path d="m9 12 2 2 4-4"/>
            </svg>
            <span>{{ session('success') }}</span>
        </p>
    @endif

    @if($dotations->isEmpty())
        <div class="ds-panel" style="padding: var(--space-6); text-align:center">
            <span class="ds-text-secondary" style="display:inline-flex">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                    <path d="M20 12v10H4V12"/><path d="M2 7h20v5H2z"/><path d="M12 22V7"/><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7"/><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7"/>
                </svg>
            </span>
            <h2 style="margin-top: var(--space-2); font-size: var(--text-h3)">Aucune dotation pour le moment</h2>
            <p class="ds-text-secondary" style="margin-top: var(--space-1)">
                Vous n'avez pas encore reçu de dotation numérique
            </p>
        </div>
    @else
        <div style="display:grid; gap:var(--space-2); grid-template-columns:repeat(auto-fit, minmax(260px, 1fr))">
            @foreach($dotations as $dotation)
                @php
                    $typeDotation = $dotation->inventaire->type_dotation ?? '';
                    $paths = $dotationIcones[$typeDotation] ?? $dotationIcones['defaut'];
                    [$roleStatut, $libelleStatut] = $statutRoles[$dotation->status]
                        ?? ['draft', ucfirst(str_replace('_', ' ', $dotation->status))];
                @endphp
                <article class="ds-card" style="padding: var(--space-3)">
                    <div style="display:flex; align-items:flex-start; justify-content:space-between; gap:var(--space-1)">
                        <span style="display:grid; place-items:center; width:44px; height:44px; flex-shrink:0; border-radius:var(--radius-chip); background:var(--accent-surface); color:var(--accent)">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                                @foreach ($paths as $d)<path d="{{ $d }}"/>@endforeach
                            </svg>
                        </span>
                        {{-- Statut en point de couleur et libelle : la carte reste neutre,
                             y compris pour une dotation suspendue. --}}
                        <span style="display:inline-flex; align-items:center; gap:var(--space-0-5); font-size:var(--text-label); font-weight:var(--font-semibold); color:var(--status-{{ $roleStatut }}-text)">
                            <span style="width:8px; height:8px; flex-shrink:0; border-radius:var(--radius-pill); background:currentColor"></span>
                            {{ $libelleStatut }}
                        </span>
                    </div>

                    <h2 style="margin-top: var(--space-2); font-size: var(--text-h3)">
                        {{ $dotation->inventaire->nom ?? 'Équipement' }}
                    </h2>
                    <p class="ds-text-secondary" style="margin-top: var(--space-0-5); font-size:var(--text-caption)">
                        {{ $typeDotation ? ucfirst(str_replace('_', ' ', $typeDotation)) : 'Type non renseigné' }}
                    </p>

                    <dl style="margin-top: var(--space-2); display:grid; gap:var(--space-1); font-size:var(--text-label)">
                        <div style="display:flex; justify-content:space-between; gap:var(--space-1)">
                            <dt class="ds-text-secondary">Date d'attribution</dt>
                            <dd style="font-weight:var(--font-medium)">
                                {{ $dotation->date_attribution ? \Carbon\Carbon::parse($dotation->date_attribution)->format('d/m/Y') : 'Non renseignée' }}
                            </dd>
                        </div>
                    </dl>

                    @if($dotation->inventaire->description ?? false)
                    <p class="ds-text-secondary" style="margin-top: var(--space-2); padding-top: var(--space-2); border-top:1px solid var(--border-default); font-size:var(--text-label)">
                        {{ Str::limit($dotation->inventaire->description, 80) }}
                    </p>
                    @endif
                </article>
            @endforeach
        </div>
    @endif

</div>
@endsection
