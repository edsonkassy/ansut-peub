@extends('layouts.bachelier')

{{-- Active le design system PEUB sur cette vue uniquement. --}}
@section('html-attrs', 'data-ds')

@section('title', 'Mon Parcours Académique - Bachelier PEUB')

@php
    // Roles de statut dedies. « Abandonne » est rendu en neutre et non en rouge :
    // un parcours interrompu ne doit pas etre presente comme une faute.
    $statutRoles = [
        'en_cours' => ['review', 'En cours'],
        'termine' => ['accepted', 'Terminé'],
        'abandonne' => ['draft', 'Abandonné'],
    ];
@endphp

@section('content')
<div class="ds-container ds-stack" style="padding-block: var(--space-4)">

    <header style="display:flex; align-items:flex-start; justify-content:space-between; gap:var(--space-2); flex-wrap:wrap">
        <div>
            <p class="ds-overline">PARCOURS / MON PARCOURS ACADÉMIQUE</p>
            <h1 style="margin-top: var(--space-1)">Mon parcours académique</h1>
            @if($parcours->isNotEmpty())
            <p class="ds-text-secondary" style="margin-top: var(--space-1)">
                {{ $parcours->count() }} {{ $parcours->count() > 1 ? 'formations enregistrées' : 'formation enregistrée' }}
            </p>
            @endif
        </div>
        @if($parcours->isNotEmpty())
        <a href="{{ route('bachelier.parcours.create') }}" class="ds-btn ds-btn-primary ds-btn-md">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                <path d="M5 12h14"/><path d="M12 5v14"/>
            </svg>
            Ajouter une formation
        </a>
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

    @if($parcours->isEmpty())
        <div class="ds-panel" style="padding: var(--space-6); text-align:center">
            <span class="ds-text-secondary" style="display:inline-flex">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                    <path d="M21.42 10.922a1 1 0 0 0-.019-1.838L12.83 5.18a2 2 0 0 0-1.66 0L2.6 9.08a1 1 0 0 0 0 1.832l8.57 3.908a2 2 0 0 0 1.66 0z"/><path d="M22 10v6"/><path d="M6 12.5V16a6 3 0 0 0 12 0v-3.5"/>
                </svg>
            </span>
            <h2 style="margin-top: var(--space-2); font-size: var(--text-h3)">Commencez à suivre votre parcours</h2>
            <p class="ds-text-secondary" style="margin-top: var(--space-1)">
                Enregistrez vos diplômes, certificats et formations ici
            </p>
            <a href="{{ route('bachelier.parcours.create') }}" class="ds-btn ds-btn-primary ds-btn-lg" style="margin-top: var(--space-3)">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                    <path d="M5 12h14"/><path d="M12 5v14"/>
                </svg>
                Ajouter votre première formation
            </a>
        </div>
    @else
        <div class="ds-stack-sm">
            @foreach($parcours as $item)
                @php
                    [$roleStatut, $libelleStatut] = $statutRoles[$item->statut]
                        ?? ['draft', ucfirst(str_replace('_', ' ', $item->statut))];
                @endphp
                <article class="ds-card" style="padding: var(--space-3)">
                    <div style="display:flex; align-items:flex-start; justify-content:space-between; gap:var(--space-1-5)">
                        <div style="display:flex; align-items:flex-start; gap:var(--space-1-5); min-width:0">
                            <span style="display:grid; place-items:center; width:44px; height:44px; flex-shrink:0; border-radius:var(--radius-chip); background:var(--accent-surface); color:var(--accent)">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                                    <path d="M21.42 10.922a1 1 0 0 0-.019-1.838L12.83 5.18a2 2 0 0 0-1.66 0L2.6 9.08a1 1 0 0 0 0 1.832l8.57 3.908a2 2 0 0 0 1.66 0z"/><path d="M22 10v6"/><path d="M6 12.5V16a6 3 0 0 0 12 0v-3.5"/>
                                </svg>
                            </span>
                            <div style="min-width:0">
                                <h2 style="font-size: var(--text-h3)">{{ $item->universite_nom }}</h2>
                                <p class="ds-text-secondary" style="margin-top: var(--space-0-5); font-size:var(--text-caption)">
                                    {{-- La colonne stocke le niveau de base (« licence »), le detail
                                         saisi au formulaire etant reduit par mapNiveauToBase(). --}}
                                    {{ ucfirst($item->niveau) }} &middot; {{ $item->annee_academique }}
                                </p>
                            </div>
                        </div>
                        <span style="display:inline-flex; align-items:center; gap:var(--space-0-5); flex-shrink:0; font-size:var(--text-label); font-weight:var(--font-semibold); color:var(--status-{{ $roleStatut }}-text)">
                            <span style="width:8px; height:8px; flex-shrink:0; border-radius:var(--radius-pill); background:currentColor"></span>
                            {{ $libelleStatut }}
                        </span>
                    </div>

                    <dl style="margin-top: var(--space-2); display:grid; gap:var(--space-1); grid-template-columns:repeat(auto-fit, minmax(110px, 1fr)); font-size:var(--text-label)">
                        <div>
                            <dt class="ds-text-secondary">Pays</dt>
                            <dd style="font-weight:var(--font-medium)">{{ $item->pays ?: 'Non renseigné' }}</dd>
                        </div>
                        <div>
                            <dt class="ds-text-secondary">Moyenne</dt>
                            <dd style="font-weight:var(--font-medium)">{{ $item->performance ? $item->performance . '/20' : 'Non renseignée' }}</dd>
                        </div>
                        <div>
                            <dt class="ds-text-secondary">Mention</dt>
                            <dd style="font-weight:var(--font-medium)">{{ $item->mention ?: 'Non renseignée' }}</dd>
                        </div>
                    </dl>

                    @if($item->attestation_admission_file)
                    <p style="margin-top: var(--space-2)">
                        <a href="{{ asset('storage/' . $item->attestation_admission_file) }}" target="_blank" rel="noopener"
                           class="ds-text-accent"
                           style="display:inline-flex; align-items:center; gap:var(--space-0-5); min-height:44px; font-size:var(--text-caption); font-weight:var(--font-medium)">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                                <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M16 13H8"/><path d="M16 17H8"/>
                            </svg>
                            Voir le justificatif
                        </a>
                    </p>
                    @endif

                    <div style="margin-top: var(--space-2); padding-top: var(--space-2); border-top:1px solid var(--border-default); display:flex; justify-content:flex-end; gap:var(--space-1); flex-wrap:wrap">
                        <a href="{{ route('bachelier.parcours.edit', $item) }}" class="ds-btn ds-btn-secondary ds-btn-md">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4z"/>
                            </svg>
                            Modifier
                        </a>
                        <form action="{{ route('bachelier.parcours.destroy', $item) }}" method="POST"
                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce parcours ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="ds-btn ds-btn-ghost ds-btn-md">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                                    <path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                </svg>
                                Supprimer
                            </button>
                        </form>
                    </div>
                </article>
            @endforeach
        </div>
    @endif

</div>
@endsection
