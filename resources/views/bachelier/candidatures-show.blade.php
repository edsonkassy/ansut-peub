@extends('layouts.bachelier')

{{-- Active le design system PEUB sur cette vue uniquement. --}}
@section('html-attrs', 'data-ds')

@section('title', 'Détails de la candidature - Bachelier')

@php
    $statutRoles = [
        'pending' => ['pending', 'En attente'],
        'reviewed' => ['review', "En cours d'examen"],
        'accepted' => ['accepted', 'Acceptée'],
        'rejected' => ['rejected', 'Refusée'],
        'participated' => ['accepted', 'Participé'],
    ];
    [$roleStatut, $libelleStatut] = $statutRoles[$candidature->status] ?? ['draft', ucfirst($candidature->status)];

    $statutIcones = [
        'pending' => ['M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20', 'M12 6v6l4 2'],
        'reviewed' => ['M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z', 'M12 9a3 3 0 1 0 0 6 3 3 0 0 0 0-6'],
        'accepted' => ['M20 6 9 17l-5-5'],
        'rejected' => ['M18 6 6 18', 'm6 6 12 12'],
        'participated' => ['M20 6 9 17l-5-5'],
    ];
    $iconeStatut = $statutIcones[$candidature->status] ?? $statutIcones['pending'];
@endphp

@section('content')
<div class="ds-container ds-stack" style="padding-block: var(--space-4)">

    <header>
        {{-- Le lien de retour est une cible tactile a part entiere : 44px de haut,
             et non un simple mot de 16px dans une ligne de fil d Ariane. --}}
        <p class="ds-overline" style="display:flex; align-items:center; gap:var(--space-0-5)">
            <a href="{{ route('bachelier.candidatures') }}"
               style="display:inline-flex; align-items:center; gap:var(--space-0-5); min-height:44px; color:inherit">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                    <path d="m15 18-6-6 6-6"/>
                </svg>
                CANDIDATURES
            </a>
            <span aria-hidden="true">/</span>
            <span>DÉTAILS</span>
        </p>
        <h1 style="margin-top: var(--space-1)">Candidature n<sup>o</sup> {{ $candidature->id }}</h1>
        <p class="ds-text-secondary" style="margin-top: var(--space-1)">{{ $candidature->opportunite->titre }}</p>
    </header>

    {{-- Statut en tete : c est l information que le bachelier vient chercher.
         Point de couleur et libelle, jamais un aplat plein sur la carte. --}}
    <div class="ds-card" style="padding: var(--space-3)">
        <div style="display:flex; align-items:center; gap:var(--space-2); flex-wrap:wrap">
            <span style="display:grid; place-items:center; width:44px; height:44px; flex-shrink:0; border-radius:var(--radius-chip); background:var(--status-{{ $roleStatut }}-surface); color:var(--status-{{ $roleStatut }}-text)">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                    @foreach ($iconeStatut as $d)<path d="{{ $d }}"/>@endforeach
                </svg>
            </span>
            <div style="flex:1; min-width:0">
                <p style="font-size:var(--text-h3); font-weight:var(--font-semibold); color:var(--status-{{ $roleStatut }}-text)">{{ $libelleStatut }}</p>
                <p class="ds-text-secondary" style="font-size:var(--text-caption)">
                    {{ $candidature->opportunite->partenaire->nom_organisation }}
                </p>
            </div>
            @if($candidature->score_matching)
            <span class="ds-badge ds-badge-accent">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                    <path d="M4 14a1 1 0 0 1-.78-1.63l9.9-10.2a.5.5 0 0 1 .86.46l-1.92 6.02A1 1 0 0 0 13 10h7a1 1 0 0 1 .78 1.63l-9.9 10.2a.5.5 0 0 1-.86-.46l1.92-6.02A1 1 0 0 0 11 14z"/>
                </svg>
                Score IA {{ $candidature->score_matching }}%
            </span>
            @endif
        </div>
    </div>

    <div style="display:grid; gap:var(--space-3); grid-template-columns:repeat(auto-fit, minmax(280px, 1fr))">

        {{-- Colonne principale --}}
        <div class="ds-stack-sm" style="min-width:0">

            <section class="ds-card" style="padding: var(--space-3)">
                <h2 style="font-size: var(--text-h3)">Ma candidature</h2>

                <dl style="margin-top: var(--space-2); display:grid; gap:var(--space-2); grid-template-columns:repeat(auto-fit, minmax(150px, 1fr))">
                    <div>
                        <dt class="ds-text-secondary" style="font-size:var(--text-label)">Date de soumission</dt>
                        <dd style="font-size:var(--text-caption); font-weight:var(--font-medium)">
                            {{ $candidature->date_soumission?->format('d/m/Y à H:i') ?? 'Non renseignée' }}
                        </dd>
                    </div>
                    @if($candidature->date_reponse)
                    <div>
                        <dt class="ds-text-secondary" style="font-size:var(--text-label)">Date de réponse</dt>
                        <dd style="font-size:var(--text-caption); font-weight:var(--font-medium)">{{ $candidature->date_reponse->format('d/m/Y à H:i') }}</dd>
                    </div>
                    @endif
                    <div>
                        <dt class="ds-text-secondary" style="font-size:var(--text-label)">Type d'interaction</dt>
                        <dd style="font-size:var(--text-caption); font-weight:var(--font-medium)">{{ ucfirst($candidature->type_interaction) }}</dd>
                    </div>
                    @if($candidature->evaluation_experience)
                    <div>
                        <dt class="ds-text-secondary" style="font-size:var(--text-label)">Évaluation</dt>
                        <dd style="display:flex; align-items:center; gap:2px; color:var(--accent-highlight)">
                            @for($i = 1; $i <= 5; $i++)
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="{{ $i <= $candidature->evaluation_experience ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false" style="{{ $i <= $candidature->evaluation_experience ? '' : 'color:var(--border-strong)' }}">
                                    <path d="M11.5 2.5a.6.6 0 0 1 1 0l2.4 5 5.4.8a.6.6 0 0 1 .3 1l-3.9 3.8.9 5.4a.6.6 0 0 1-.9.6L12 16.6l-4.8 2.5a.6.6 0 0 1-.9-.6l.9-5.4-3.9-3.8a.6.6 0 0 1 .3-1l5.4-.8z"/>
                                </svg>
                            @endfor
                            <span class="ds-text-secondary" style="margin-left:var(--space-0-5); font-size:var(--text-caption); color:var(--text-secondary)">{{ $candidature->evaluation_experience }}/5</span>
                        </dd>
                    </div>
                    @endif
                </dl>

                @if($candidature->lettre_motivation)
                <h3 style="margin-top: var(--space-3)">Lettre de motivation</h3>
                <div class="ds-panel" style="margin-top: var(--space-1); padding: var(--space-2); font-size:var(--text-caption)">
                    {!! nl2br(e($candidature->lettre_motivation)) !!}
                </div>
                @endif

                @if($candidature->documents_joints && count($candidature->documents_joints) > 0)
                <h3 style="margin-top: var(--space-3)">Documents joints</h3>
                <div style="margin-top: var(--space-1); display:grid; gap:var(--space-1); grid-template-columns:repeat(auto-fit, minmax(220px, 1fr))">
                    @foreach($candidature->documents_joints as $document)
                        @php
                            // Gerer les anciens formats (string) et nouveaux formats (array)
                            $path = is_array($document) ? $document['path'] : $document;
                            $name = is_array($document) ? ($document['original_name'] ?? 'Document ' . $loop->iteration) : 'Document ' . $loop->iteration;
                            $mimeType = is_array($document) ? ($document['mime_type'] ?? '') : '';
                            $isPdf = str_contains($mimeType, 'pdf') || str_ends_with($path, '.pdf');
                            $size = is_array($document) ? ($document['size'] ?? 0) : 0;
                            $sizeFormatted = $size > 0 ? number_format($size / 1024 / 1024, 1) . ' MB' : '';
                        @endphp
                        <a href="{{ asset('storage/' . $path) }}" target="_blank" rel="noopener"
                           style="display:flex; align-items:center; gap:var(--space-1); min-height:44px; padding:var(--space-1); border:1px solid var(--border-default); border-radius:var(--radius-chip); color:inherit; text-decoration:none">
                            <span style="display:grid; place-items:center; width:32px; height:32px; flex-shrink:0; border-radius:var(--radius-chip); background:var(--surface-secondary); color:var(--text-secondary)">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                                    @if($isPdf)
                                        <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M16 13H8"/><path d="M16 17H8"/>
                                    @else
                                        <path d="M3 5h18a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2z"/><path d="M9 11a2 2 0 1 0 0-4 2 2 0 0 0 0 4"/><path d="m21 15-5-5L5 21"/>
                                    @endif
                                </svg>
                            </span>
                            <span style="flex:1; min-width:0">
                                <span class="line-clamp-2" style="display:block; font-size:var(--text-caption); font-weight:var(--font-medium)">{{ $name }}</span>
                                <span class="ds-text-secondary" style="display:block; font-size:var(--text-label)">
                                    {{ $isPdf ? 'PDF' : 'Image' }}@if($sizeFormatted) &middot; {{ $sizeFormatted }} @endif
                                </span>
                            </span>
                        </a>
                    @endforeach
                </div>
                @endif
            </section>

            @if($candidature->commentaire_partenaire)
            <section class="ds-card" style="padding: var(--space-3)">
                <h2 style="font-size: var(--text-h3)">Commentaire du partenaire</h2>
                <div style="margin-top: var(--space-2); display:flex; align-items:flex-start; gap:var(--space-1-5)">
                    <span style="display:grid; place-items:center; width:32px; height:32px; flex-shrink:0; border-radius:var(--radius-pill); background:var(--surface-secondary); overflow:hidden">
                        @if($candidature->opportunite->partenaire->logo)
                            <img src="{{ asset('storage/' . $candidature->opportunite->partenaire->logo) }}"
                                 alt="{{ $candidature->opportunite->partenaire->nom_organisation }}"
                                 width="32" height="32" loading="lazy" decoding="async"
                                 style="width:32px; height:32px; object-fit:contain">
                        @else
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false" style="color:var(--text-secondary)">
                                <path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/><path d="M10 6h4"/><path d="M10 10h4"/><path d="M10 14h4"/>
                            </svg>
                        @endif
                    </span>
                    <div style="min-width:0">
                        <p style="font-size:var(--text-caption); font-weight:var(--font-medium)">{{ $candidature->opportunite->partenaire->nom_organisation }}</p>
                        <p class="ds-text-secondary" style="margin-top:var(--space-0-5); font-size:var(--text-caption)">{{ $candidature->commentaire_partenaire }}</p>
                    </div>
                </div>
            </section>
            @endif

            @if($candidature->commentaire_evaluation)
            <section class="ds-card" style="padding: var(--space-3)">
                <h2 style="font-size: var(--text-h3)">Évaluation et commentaire</h2>
                <p class="ds-text-secondary" style="margin-top: var(--space-1); font-size:var(--text-caption)">{{ $candidature->commentaire_evaluation }}</p>
            </section>
            @endif

            @if($candidature->certificat_obtenu)
            <section class="ds-card" style="padding: var(--space-3)">
                <h2 style="font-size: var(--text-h3)">Certificat obtenu</h2>
                <p class="ds-alert ds-alert-success" style="margin-top: var(--space-2)">
                    <span>
                        <strong>{{ $candidature->certificat_obtenu }}</strong><br>
                        Félicitations ! Vous avez obtenu ce certificat.
                    </span>
                </p>
            </section>
            @endif
        </div>

        {{-- Colonne laterale --}}
        <div class="ds-stack-sm" style="min-width:0">

            <section class="ds-card" style="padding: var(--space-3)">
                <h2 style="font-size: var(--text-h3)">L'opportunité</h2>

                <dl style="margin-top: var(--space-2); display:grid; gap:var(--space-1-5)">
                    <div>
                        <dt class="ds-text-secondary" style="font-size:var(--text-label)">Type</dt>
                        <dd style="font-size:var(--text-caption); font-weight:var(--font-medium)">{{ ucfirst($candidature->opportunite->type) }}</dd>
                    </div>
                    @if($candidature->opportunite->ville)
                    <div>
                        <dt class="ds-text-secondary" style="font-size:var(--text-label)">Localisation</dt>
                        <dd style="font-size:var(--text-caption); font-weight:var(--font-medium)">{{ $candidature->opportunite->ville }}{{ $candidature->opportunite->pays ? ', ' . $candidature->opportunite->pays : '' }}</dd>
                    </div>
                    @endif
                    @if($candidature->opportunite->duree)
                    <div>
                        <dt class="ds-text-secondary" style="font-size:var(--text-label)">Durée</dt>
                        <dd style="font-size:var(--text-caption); font-weight:var(--font-medium)">{{ $candidature->opportunite->duree }}</dd>
                    </div>
                    @endif
                    @if($candidature->opportunite->remuneration)
                    <div>
                        <dt class="ds-text-secondary" style="font-size:var(--text-label)">Rémunération</dt>
                        <dd style="font-size:var(--text-caption); font-weight:var(--font-medium)">{{ $candidature->opportunite->remuneration }}</dd>
                    </div>
                    @endif
                    @if($candidature->opportunite->date_limite_candidature)
                    <div>
                        <dt class="ds-text-secondary" style="font-size:var(--text-label)">Date limite</dt>
                        <dd style="font-size:var(--text-caption); font-weight:var(--font-medium)">{{ $candidature->opportunite->date_limite_candidature->format('d/m/Y') }}</dd>
                    </div>
                    @endif
                    <div>
                        <dt class="ds-text-secondary" style="font-size:var(--text-label)">Vues de l'opportunité</dt>
                        <dd style="font-size:var(--text-caption); font-weight:var(--font-medium)">{{ $candidature->opportunite->vues }}</dd>
                    </div>
                    <div>
                        <dt class="ds-text-secondary" style="font-size:var(--text-label)">Total candidatures</dt>
                        <dd style="font-size:var(--text-caption); font-weight:var(--font-medium)">{{ $candidature->opportunite->candidatures_count }}</dd>
                    </div>
                </dl>

                {{-- Un seul appel vers l offre : l en-tete et cette colonne en portaient
                     chacun un, vers la meme route. --}}
                <a href="{{ route('bachelier.opportunites.show', $candidature->opportunite) }}"
                   class="ds-btn ds-btn-primary ds-btn-md ds-btn-block" style="margin-top: var(--space-3)">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                        <path d="M15 3h6v6"/><path d="M10 14 21 3"/><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
                    </svg>
                    Voir l'opportunité
                </a>
            </section>

            <section class="ds-card" style="padding: var(--space-3)">
                <h2 style="font-size: var(--text-h3)">Actions</h2>

                <div class="ds-stack-sm" style="margin-top: var(--space-2)">
                    @if($candidature->status === 'pending')
                    {{-- Cablee sur la route existante bachelier.candidatures.destroy, comme la
                         liste. Le bouton appelait auparavant une fonction JS qui se contentait
                         d un console.log : il n a jamais rien retire. --}}
                    <form action="{{ route('bachelier.candidatures.destroy', $candidature) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                onclick="return confirm('Êtes-vous sûr de vouloir retirer cette candidature ? Cette action est irréversible.')"
                                class="ds-btn ds-btn-secondary ds-btn-md ds-btn-block">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                                <path d="M18 6 6 18"/><path d="m6 6 12 12"/>
                            </svg>
                            Retirer ma candidature
                        </button>
                    </form>
                    @endif

                    @if($candidature->status === 'accepted')
                    <a href="{{ route('bachelier.inbox.index') }}" class="ds-btn ds-btn-primary ds-btn-md ds-btn-block">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                            <path d="M4 4h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z"/><path d="m22 6-10 7L2 6"/>
                        </svg>
                        Contacter le partenaire
                    </a>
                    @endif
                </div>
            </section>
        </div>
    </div>
</div>
@endsection
