@extends('layouts.bachelier')

{{-- Active le design system PEUB sur cette vue uniquement. --}}
@section('html-attrs', 'data-ds')

@section('title', $opportunite->titre . ' - Opportunité')

@php
    $typeIcones = [
        'bourse' => ['M21.42 10.922a1 1 0 0 0-.019-1.838L12.83 5.18a2 2 0 0 0-1.66 0L2.6 9.08a1 1 0 0 0 0 1.832l8.57 3.908a2 2 0 0 0 1.66 0z', 'M22 10v6', 'M6 12.5V16a6 3 0 0 0 12 0v-3.5'],
        'stage' => ['M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16', 'M4 7h16a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2z'],
        'emploi' => ['M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2', 'M9 3a4 4 0 1 0 0 8 4 4 0 0 0 0-8', 'M22 21v-2a4 4 0 0 0-3-3.87'],
        'formation' => ['M12 7v14', 'M3 18a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h5a4 4 0 0 1 4 4 4 4 0 0 1 4-4h5a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1h-6a3 3 0 0 0-3 3 3 3 0 0 0-3-3z'],
        'concours' => ['M6 9H4.5a2.5 2.5 0 0 1 0-5H6', 'M18 9h1.5a2.5 2.5 0 0 0 0-5H18', 'M4 22h16', 'M18 2H6v7a6 6 0 0 0 12 0z'],
        'event' => ['M8 2v4', 'M16 2v4', 'M3 10h18', 'M5 4h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z'],
        'promotion' => ['m3 11 18-5v12L3 14v-3z', 'M11.6 16.8a3 3 0 1 1-5.8-1.6'],
        'defaut' => ['M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20', 'M12 6a6 6 0 1 0 0 12 6 6 0 0 0 0-12', 'M12 10a2 2 0 1 0 0 4 2 2 0 0 0 0-4'],
    ];
    $paths = $typeIcones[$opportunite->type] ?? $typeIcones['defaut'];

    $limite = $opportunite->date_limite_candidature
        ? \Carbon\Carbon::parse($opportunite->date_limite_candidature)
        : null;
    $joursRestants = $limite ? (int) now()->startOfDay()->diffInDays($limite->copy()->startOfDay(), false) : null;

    $etapes = [
        ['Candidature en ligne', 'Remplissez le formulaire de candidature avec vos informations'],
        ['Évaluation IA', 'Notre IA analyse votre profil et calcule un score de compatibilité'],
        ['Sélection', 'Le partenaire examine les candidatures et sélectionne les meilleurs profils'],
        ['Notification', 'Vous recevez une notification du résultat de votre candidature'],
    ];
@endphp

@section('content')
<div class="ds-container ds-stack" style="padding-block: var(--space-4)">

    <header>
        {{-- Le lien de retour est une cible tactile a part entiere, pas un mot de 16px. --}}
        <p class="ds-overline" style="display:flex; align-items:center; gap:var(--space-0-5); flex-wrap:wrap">
            <a href="{{ route('bachelier.opportunites') }}"
               style="display:inline-flex; align-items:center; gap:var(--space-0-5); min-height:44px; color:inherit">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                    <path d="m15 18-6-6 6-6"/>
                </svg>
                OPPORTUNITÉS
            </a>
        </p>
        <h1 style="margin-top: var(--space-1)">{{ $opportunite->titre }}</h1>
        <p class="ds-text-secondary" style="margin-top: var(--space-1)">{{ $opportunite->partenaire->nom_organisation }}</p>

        <div style="margin-top: var(--space-2); display:flex; align-items:center; gap:var(--space-1); flex-wrap:wrap">
            <span class="ds-badge ds-badge-neutral">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                    @foreach ($paths as $d)<path d="{{ $d }}"/>@endforeach
                </svg>
                {{ ucfirst($opportunite->type) }}
            </span>
            @if(!is_null($joursRestants))
                @if($joursRestants < 0)
                    <span class="ds-badge ds-badge-neutral">Clôturée</span>
                @elseif($joursRestants <= 7)
                    <span class="ds-badge ds-badge-warning">
                        {{ $joursRestants === 0 ? 'Dernier jour' : 'Plus que ' . $joursRestants . ' jour' . ($joursRestants > 1 ? 's' : '') }}
                    </span>
                @endif
            @endif
        </div>
    </header>

    {{-- Bloc d action remonte : postuler est l objet de la page.
         Il vivait auparavant dans la colonne laterale, donc sous trois blocs de texte
         a 360px. --}}
    <div class="ds-card" style="padding: var(--space-3)">
        @if(!$hasApplied)
            <button type="button"
                    onclick="openCandidatureConfirmModal({{ $opportunite->id }}, '{{ addslashes($opportunite->titre) }}', '{{ addslashes($opportunite->partenaire->nom_organisation) }}', '{{ $opportunite->type }}', false)"
                    class="ds-btn ds-btn-primary ds-btn-lg ds-btn-block">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                    <path d="m22 2-7 20-4-9-9-4z"/><path d="M22 2 11 13"/>
                </svg>
                Postuler maintenant
            </button>
        @else
            <p class="ds-alert ds-alert-success">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false" style="flex-shrink:0">
                    <path d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20"/><path d="m9 12 2 2 4-4"/>
                </svg>
                <span>
                    Vous avez déjà postulé à cette opportunité.
                    @if($candidature)
                        <a href="{{ route('bachelier.candidatures.show', $candidature) }}" style="color:inherit; font-weight:var(--font-semibold)">Suivre ma candidature</a>
                    @endif
                </span>
            </p>
        @endif

        <div style="margin-top: var(--space-2); display:flex; gap:var(--space-1); flex-wrap:wrap">
            <button type="button"
                    class="favorite-btn ds-btn ds-btn-secondary ds-btn-md"
                    data-opportunite-id="{{ $opportunite->id }}"
                    aria-pressed="{{ $opportunite->isFavorited ? 'true' : 'false' }}"
                    style="flex:1">
                <svg id="favorite-icon" width="16" height="16" viewBox="0 0 24 24" fill="{{ $opportunite->isFavorited ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                    <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7z"/>
                </svg>
                <span id="favorite-label">{{ $opportunite->isFavorited ? 'En favori' : 'Ajouter aux favoris' }}</span>
            </button>
            <button type="button" class="share-btn ds-btn ds-btn-secondary ds-btn-md" style="flex:1">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                    <path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/><path d="M16 6l-4-4-4 4"/><path d="M12 2v13"/>
                </svg>
                Partager
            </button>
        </div>

        @if($limite)
        <p class="ds-text-secondary" style="margin-top: var(--space-2); font-size:var(--text-caption); text-align:center">
            Date limite de candidature : <strong style="color:var(--text-primary)">{{ $limite->format('d/m/Y') }}</strong>
        </p>
        @endif
    </div>

    @if($opportunite->illustration)
    <img src="{{ asset('storage/' . $opportunite->illustration) }}"
         alt="{{ $opportunite->titre }}"
         width="1200" height="600" loading="lazy" decoding="async"
         style="width:100%; height:auto; max-height:320px; object-fit:cover; display:block; border-radius:var(--radius-card)">
    @endif

    <div style="display:grid; gap:var(--space-3); grid-template-columns:repeat(auto-fit, minmax(280px, 1fr))">

        <div class="ds-stack-sm" style="min-width:0">

            <section class="ds-card" style="padding: var(--space-3)">
                <h2 style="font-size: var(--text-h3)">Description</h2>
                <div class="ds-text-secondary" style="margin-top: var(--space-1-5); font-size:var(--text-caption)">
                    {!! nl2br(e($opportunite->description)) !!}
                </div>
            </section>

            @if($opportunite->competences_requises)
            @php
                $competences = is_array($opportunite->competences_requises)
                    ? $opportunite->competences_requises
                    : explode(',', $opportunite->competences_requises);
            @endphp
            <section class="ds-card" style="padding: var(--space-3)">
                <h2 style="font-size: var(--text-h3)">Compétences requises</h2>
                <div style="margin-top: var(--space-1-5); display:flex; flex-wrap:wrap; gap:var(--space-0-5)">
                    @foreach($competences as $competence)
                        <span class="ds-badge ds-badge-neutral">{{ trim($competence) }}</span>
                    @endforeach
                </div>
            </section>
            @endif

            @if($opportunite->criteres_eligibilite)
            <section class="ds-card" style="padding: var(--space-3)">
                <h2 style="font-size: var(--text-h3)">Critères d'éligibilité</h2>
                @if(is_array($opportunite->criteres_eligibilite))
                    <ul class="ds-text-secondary" style="margin-top: var(--space-1-5); list-style:disc; padding-left:var(--space-2); display:grid; gap:var(--space-0-5); font-size:var(--text-caption)">
                        @foreach($opportunite->criteres_eligibilite as $critere)
                            <li>{{ $critere }}</li>
                        @endforeach
                    </ul>
                @else
                    <div class="ds-text-secondary" style="margin-top: var(--space-1-5); font-size:var(--text-caption)">
                        {!! nl2br(e($opportunite->criteres_eligibilite)) !!}
                    </div>
                @endif
            </section>
            @endif

            <section class="ds-card" style="padding: var(--space-3)">
                <h2 style="font-size: var(--text-h3)">Processus de candidature</h2>
                <ol style="margin-top: var(--space-2); display:grid; gap:var(--space-2); list-style:none; padding:0">
                    @foreach($etapes as $index => [$titre, $texte])
                    <li style="display:flex; align-items:flex-start; gap:var(--space-1-5)">
                        <span style="display:grid; place-items:center; width:32px; height:32px; flex-shrink:0; border-radius:var(--radius-pill); background:var(--accent-surface); color:var(--accent); font-size:var(--text-caption); font-weight:var(--font-semibold)">
                            {{ $index + 1 }}
                        </span>
                        <span style="min-width:0">
                            <span style="display:block; font-size:var(--text-caption); font-weight:var(--font-medium)">{{ $titre }}</span>
                            <span class="ds-text-secondary" style="display:block; font-size:var(--text-label)">{{ $texte }}</span>
                        </span>
                    </li>
                    @endforeach
                </ol>
            </section>
        </div>

        <div class="ds-stack-sm" style="min-width:0">

            <section class="ds-card" style="padding: var(--space-3)">
                <h2 style="font-size: var(--text-h3)">Informations clés</h2>
                <dl style="margin-top: var(--space-2); display:grid; gap:var(--space-1-5)">
                    @if($opportunite->ville || $opportunite->pays)
                    <div>
                        <dt class="ds-text-secondary" style="font-size:var(--text-label)">Localisation</dt>
                        <dd style="font-size:var(--text-caption); font-weight:var(--font-medium)">{{ $opportunite->ville }}{{ $opportunite->ville && $opportunite->pays ? ', ' : '' }}{{ $opportunite->pays }}</dd>
                    </div>
                    @endif
                    @if($opportunite->duree)
                    <div>
                        <dt class="ds-text-secondary" style="font-size:var(--text-label)">Durée</dt>
                        <dd style="font-size:var(--text-caption); font-weight:var(--font-medium)">{{ $opportunite->duree }}</dd>
                    </div>
                    @endif
                    @if($opportunite->remuneration)
                    <div>
                        <dt class="ds-text-secondary" style="font-size:var(--text-label)">Rémunération</dt>
                        <dd style="font-size:var(--text-caption); font-weight:var(--font-medium)">{{ $opportunite->remuneration }}</dd>
                    </div>
                    @endif
                    @if($limite)
                    <div>
                        <dt class="ds-text-secondary" style="font-size:var(--text-label)">Date limite</dt>
                        <dd style="font-size:var(--text-caption); font-weight:var(--font-medium)">{{ $limite->format('d/m/Y') }}</dd>
                    </div>
                    @endif
                    @if($opportunite->nombre_places)
                    <div>
                        <dt class="ds-text-secondary" style="font-size:var(--text-label)">Places disponibles</dt>
                        <dd style="font-size:var(--text-caption); font-weight:var(--font-medium)">{{ $opportunite->nombre_places }}</dd>
                    </div>
                    @endif
                    <div>
                        <dt class="ds-text-secondary" style="font-size:var(--text-label)">Vues</dt>
                        <dd style="font-size:var(--text-caption); font-weight:var(--font-medium)">{{ $opportunite->vues }}</dd>
                    </div>
                    <div>
                        <dt class="ds-text-secondary" style="font-size:var(--text-label)">Candidatures reçues</dt>
                        <dd style="font-size:var(--text-caption); font-weight:var(--font-medium)">{{ $opportunite->candidatures_count }}</dd>
                    </div>
                </dl>
            </section>

            <section class="ds-card" style="padding: var(--space-3)">
                <h2 style="font-size: var(--text-h3)">À propos du partenaire</h2>
                <div style="margin-top: var(--space-2); display:flex; align-items:center; gap:var(--space-1-5)">
                    <span style="display:grid; place-items:center; width:48px; height:48px; flex-shrink:0; border-radius:var(--radius-chip); background:var(--surface-secondary); overflow:hidden">
                        @if($opportunite->partenaire->logo)
                            <img src="{{ asset('storage/' . $opportunite->partenaire->logo) }}"
                                 alt="{{ $opportunite->partenaire->nom_organisation }}"
                                 width="40" height="40" loading="lazy" decoding="async"
                                 style="width:40px; height:40px; object-fit:contain">
                        @else
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false" style="color:var(--text-secondary)">
                                <path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/><path d="M10 6h4"/><path d="M10 10h4"/><path d="M10 14h4"/>
                            </svg>
                        @endif
                    </span>
                    <span style="min-width:0">
                        <span style="display:block; font-size:var(--text-caption); font-weight:var(--font-medium)">{{ $opportunite->partenaire->nom_organisation }}</span>
                        <span class="ds-text-secondary" style="display:block; font-size:var(--text-label)">{{ $opportunite->partenaire->secteur_activite }}</span>
                    </span>
                </div>

                @if($opportunite->partenaire->description)
                <p class="ds-text-secondary" style="margin-top: var(--space-2); font-size:var(--text-caption)">
                    {{ Str::limit($opportunite->partenaire->description, 150) }}
                </p>
                @endif

                @if($opportunite->partenaire->region || $opportunite->partenaire->commune)
                <p class="ds-text-secondary" style="margin-top: var(--space-1-5); display:flex; align-items:center; gap:var(--space-0-5); font-size:var(--text-label)">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                        <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0"/><path d="M12 8a2 2 0 1 0 0 4 2 2 0 0 0 0-4"/>
                    </svg>
                    {{ $opportunite->partenaire->commune }}{{ $opportunite->partenaire->commune && $opportunite->partenaire->region ? ', ' : '' }}{{ $opportunite->partenaire->region }}
                </p>
                @endif
            </section>

            @if($opportunites_similaires->count() > 0)
            <section class="ds-card" style="padding: var(--space-3)">
                <h2 style="font-size: var(--text-h3)">Opportunités similaires</h2>
                <div style="margin-top: var(--space-1-5)">
                    @foreach($opportunites_similaires->take(3) as $similaire)
                        @php $pathsSim = $typeIcones[$similaire->type] ?? $typeIcones['defaut']; @endphp
                        <a href="{{ route('bachelier.opportunites.show', $similaire) }}"
                           style="display:flex; align-items:center; gap:var(--space-1-5); min-height:44px; padding:var(--space-1-5) 0; border-top:1px solid var(--border-default); color:inherit; text-decoration:none">
                            <span style="display:grid; place-items:center; width:32px; height:32px; flex-shrink:0; border-radius:var(--radius-chip); background:var(--accent-surface); color:var(--accent)">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                                    @foreach ($pathsSim as $d)<path d="{{ $d }}"/>@endforeach
                                </svg>
                            </span>
                            <span style="flex:1; min-width:0">
                                <span class="line-clamp-2" style="display:block; font-size:var(--text-caption); font-weight:var(--font-medium)">{{ $similaire->titre }}</span>
                                <span class="ds-text-secondary" style="display:block; font-size:var(--text-label)">{{ $similaire->partenaire->nom_organisation }}</span>
                            </span>
                        </a>
                    @endforeach
                </div>
            </section>
            @endif
        </div>
    </div>
</div>

@include('bachelier.candidature-confirm-modal')

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Favoris. Le marqueur visuel est l attribut fill du SVG plus aria-pressed et le
    // libelle, et non une classe de palette posee sur une balise <i>.
    const favoriteBtn = document.querySelector('.favorite-btn');
    if (favoriteBtn) {
        favoriteBtn.addEventListener('click', function () {
            const opportuniteId = this.dataset.opportuniteId;
            const icone = document.getElementById('favorite-icon');
            const libelle = document.getElementById('favorite-label');

            fetch('/bachelier/favoris/toggle', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ opportunite_id: opportuniteId })
            })
            .then(function (response) { return response.json(); })
            .then(function (data) {
                const estFavori = !!data.isFavorited;
                favoriteBtn.setAttribute('aria-pressed', estFavori ? 'true' : 'false');
                if (icone) { icone.setAttribute('fill', estFavori ? 'currentColor' : 'none'); }
                if (libelle) { libelle.textContent = estFavori ? 'En favori' : 'Ajouter aux favoris'; }
            })
            .catch(function (error) { console.error('Erreur:', error); });
        });
    }

    const shareBtn = document.querySelector('.share-btn');
    if (shareBtn) {
        shareBtn.addEventListener('click', function () {
            if (navigator.share) {
                navigator.share({
                    title: @json($opportunite->titre),
                    text: 'Découvrez cette opportunité sur PEUB',
                    url: window.location.href,
                });
            } else if (navigator.clipboard) {
                navigator.clipboard.writeText(window.location.href).then(function () {
                    alert('Lien copié dans le presse-papiers !');
                });
            }
        });
    }
});
</script>
@endpush
@endsection
