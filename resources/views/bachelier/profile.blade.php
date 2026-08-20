@extends('layouts.bachelier')

{{-- Active le design system PEUB sur cette vue uniquement. --}}
@section('html-attrs', 'data-ds')

@section('title', 'Mon Profil - Bachelier PEUB')

@php
    // Champ en lecture seule : meme gabarit que .ds-field, fond secondaire pour signaler
    // qu il n est pas modifiable. .ds-field n a pas de variante readonly.
    $lecture = 'background: var(--surface-secondary); cursor: not-allowed;';
@endphp

@section('content')
<div class="ds-container ds-stack-lg" style="padding-block: var(--space-4)">

    <header style="display:flex; align-items:flex-start; justify-content:space-between; gap:var(--space-2); flex-wrap:wrap">
        <div>
            <p class="ds-overline">PARAMÈTRES / MON PROFIL</p>
            <h1 style="margin-top: var(--space-1); font-size: clamp(var(--text-h1), 7vw, var(--text-display))">
                Mon profil
            </h1>
        </div>

        @if($bachelier->boursier_peub)
            <span class="ds-badge ds-badge-success">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                    <path d="M12 2a6 6 0 1 0 0 12 6 6 0 0 0 0-12"/><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/>
                </svg>
                Boursier PEUB
            </span>
        @else
            <span class="ds-badge ds-badge-neutral">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                    <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H19a1 1 0 0 1 1 1v18a1 1 0 0 1-1 1H6.5a1 1 0 0 1 0-5H20"/>
                </svg>
                Candidat Standard
            </span>
        @endif
    </header>

    <section class="ds-card" style="padding: var(--space-3)">
        <h2 style="font-size: var(--text-h3)">Informations personnelles</h2>

        <form id="profile-form" action="{{ route('bachelier.profile.update') }}" method="POST" enctype="multipart/form-data" class="ds-stack" style="margin-top: var(--space-3)">
            @csrf
            @method('PUT')

            {{-- Photo de profil --}}
            <div style="display:flex; align-items:center; gap:var(--space-3); flex-wrap:wrap">
                <div style="position:relative; flex-shrink:0">
                    <div id="photo-container"
                         style="width:96px; height:96px; display:grid; place-items:center; overflow:hidden; border-radius:var(--radius-pill); background:var(--surface-secondary); border:1px solid var(--border-default); color:var(--text-secondary)">
                        @if($bachelier->photo)
                            <img id="profile-image" src="{{ asset('storage/' . $bachelier->photo) }}" alt="Photo de profil" width="96" height="96" style="width:96px; height:96px; object-fit:cover">
                        @else
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                                <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><path d="M12 3a4 4 0 1 0 0 8 4 4 0 0 0 0-8"/>
                            </svg>
                        @endif
                    </div>
                </div>

                <div style="flex:1; min-width:200px">
                    <p class="ds-label">Photo de profil</p>
                    {{-- Retire de l ordre de tabulation : le bouton « Choisir une photo » ci-dessous
                         est la commande visible et accessible qui le declenche. Sans tabindex="-1",
                         ce champ masque cree un arret de tabulation de 1x1 px sans focus visible. --}}
                    <input type="file" id="photo" name="photo" accept="image/*" class="sr-only" tabindex="-1">
                    <div id="photo-actions" style="display:flex; align-items:center; gap:var(--space-1); flex-wrap:wrap">
                        <button type="button" id="choose-photo" class="ds-btn ds-btn-secondary ds-btn-md">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="m17 8-5-5-5 5"/><path d="M12 3v12"/>
                            </svg>
                            Choisir une photo
                        </button>
                        @if($bachelier->photo)
                        <button type="button" id="remove-photo" class="ds-btn ds-btn-ghost ds-btn-md">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                                <path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                            </svg>
                            Supprimer
                        </button>
                        @endif
                    </div>
                    <p class="ds-hint">JPG, PNG ou GIF. Taille max : 2MB</p>
                </div>
            </div>

            <hr class="ds-divider">

            {{-- Informations de base (non modifiables) --}}
            <div style="display:grid; gap:var(--space-2); grid-template-columns:repeat(auto-fit, minmax(260px, 1fr))">
                <div>
                    <label class="ds-label" for="nom">Nom</label>
                    <input type="text" id="nom" name="nom" value="{{ $bachelier->nom }}" readonly class="ds-field" style="{{ $lecture }}">
                </div>
                <div>
                    <label class="ds-label" for="prenoms">Prénoms</label>
                    <input type="text" id="prenoms" name="prenoms" value="{{ $bachelier->prenoms }}" readonly class="ds-field" style="{{ $lecture }}">
                </div>
                <div>
                    <label class="ds-label" for="date_naissance">Date de naissance</label>
                    <input type="date" id="date_naissance" name="date_naissance" value="{{ $bachelier->date_naissance?->format('Y-m-d') }}" readonly class="ds-field" style="{{ $lecture }}">
                </div>
                <div>
                    <label class="ds-label" for="lieu_naissance">Lieu de naissance</label>
                    <input type="text" id="lieu_naissance" name="lieu_naissance" value="{{ $bachelier->lieu_naissance }}" readonly class="ds-field" style="{{ $lecture }}">
                </div>
                <div>
                    <label class="ds-label" for="sexe">Sexe</label>
                    <select id="sexe" name="sexe" disabled class="ds-field" style="{{ $lecture }}">
                        <option value="M" {{ $bachelier->sexe === 'M' ? 'selected' : '' }}>Masculin</option>
                        <option value="F" {{ $bachelier->sexe === 'F' ? 'selected' : '' }}>Féminin</option>
                    </select>
                </div>
                <div>
                    <label class="ds-label" for="region">Région</label>
                    <input type="text" id="region" name="region" value="{{ $bachelier->region }}" readonly class="ds-field" style="{{ $lecture }}">
                </div>
            </div>

            <hr class="ds-divider">

            {{-- Contact (modifiable) --}}
            <div style="display:grid; gap:var(--space-2); grid-template-columns:repeat(auto-fit, minmax(260px, 1fr))">
                <div>
                    <label class="ds-label" for="telephone_eleve">Téléphone élève <span style="color: var(--error-text)" aria-hidden="true">*</span></label>
                    <input type="tel" id="telephone_eleve" name="telephone_eleve" value="{{ $bachelier->telephone_eleve }}" required class="ds-field">
                </div>
                <div>
                    <label class="ds-label" for="telephone_parent">Téléphone parent <span style="color: var(--error-text)" aria-hidden="true">*</span></label>
                    <input type="tel" id="telephone_parent" name="telephone_parent" value="{{ $bachelier->telephone_parent }}" required class="ds-field">
                </div>
                <div>
                    <label class="ds-label" for="email_eleve">Email élève <span style="color: var(--error-text)" aria-hidden="true">*</span></label>
                    <input type="email" id="email_eleve" name="email_eleve" value="{{ $bachelier->email_eleve }}" required class="ds-field">
                </div>
                <div>
                    <label class="ds-label" for="email_parent">Email parent <span style="color: var(--error-text)" aria-hidden="true">*</span></label>
                    <input type="email" id="email_parent" name="email_parent" value="{{ $bachelier->email_parent }}" required class="ds-field">
                </div>
            </div>

            <hr class="ds-divider">

            {{-- Informations academiques (non modifiables) --}}
            <div style="display:grid; gap:var(--space-2); grid-template-columns:repeat(auto-fit, minmax(260px, 1fr))">
                <div>
                    <label class="ds-label" for="matricule_bac">Matricule BAC</label>
                    <input type="text" id="matricule_bac" name="matricule_bac" value="{{ $bachelier->matricule_bac }}" readonly class="ds-field" style="{{ $lecture }}">
                </div>
                <div>
                    <label class="ds-label" for="serie_bac">Série BAC</label>
                    <select id="serie_bac" name="serie_bac" disabled class="ds-field" style="{{ $lecture }}">
                        <optgroup label="Séries Scientifiques">
                            <option value="C" {{ $bachelier->serie_bac === 'C' ? 'selected' : '' }}>C - Scientifique (Maths, Physique)</option>
                            <option value="E" {{ $bachelier->serie_bac === 'E' ? 'selected' : '' }}>E - Technique (Maths, Technologie)</option>
                            <option value="D" {{ $bachelier->serie_bac === 'D' ? 'selected' : '' }}>D - Scientifique (SVT, Maths)</option>
                        </optgroup>
                        <optgroup label="Séries Littéraires">
                            <option value="A1" {{ $bachelier->serie_bac === 'A1' ? 'selected' : '' }}>A1 - Littéraire (Maths + Langues)</option>
                            <option value="A2" {{ $bachelier->serie_bac === 'A2' ? 'selected' : '' }}>A2 - Littéraire (Langues, Histoire, Géo)</option>
                        </optgroup>
                        <optgroup label="Techniques Industrielles">
                            <option value="F1" {{ $bachelier->serie_bac === 'F1' ? 'selected' : '' }}>F1 - Mécanique Générale</option>
                            <option value="F2" {{ $bachelier->serie_bac === 'F2' ? 'selected' : '' }}>F2 - Électronique</option>
                            <option value="F3" {{ $bachelier->serie_bac === 'F3' ? 'selected' : '' }}>F3 - Électrotechnique</option>
                            <option value="F4" {{ $bachelier->serie_bac === 'F4' ? 'selected' : '' }}>F4 - Génie Civil</option>
                            <option value="F5" {{ $bachelier->serie_bac === 'F5' ? 'selected' : '' }}>F5 - Physique-Chimie</option>
                            <option value="F6" {{ $bachelier->serie_bac === 'F6' ? 'selected' : '' }}>F6 - Constructions Mécaniques</option>
                            <option value="F7" {{ $bachelier->serie_bac === 'F7' ? 'selected' : '' }}>F7 - Bois et Matériaux</option>
                            <option value="F8" {{ $bachelier->serie_bac === 'F8' ? 'selected' : '' }}>F8 - Arts Appliqués</option>
                        </optgroup>
                        <optgroup label="Techniques Tertiaires">
                            <option value="G1" {{ $bachelier->serie_bac === 'G1' ? 'selected' : '' }}>G1 - Secrétariat</option>
                            <option value="G2" {{ $bachelier->serie_bac === 'G2' ? 'selected' : '' }}>G2 - Comptabilité</option>
                            <option value="G3" {{ $bachelier->serie_bac === 'G3' ? 'selected' : '' }}>G3 - Commerce</option>
                        </optgroup>
                        <optgroup label="Brevets Professionnels">
                            <option value="BT" {{ $bachelier->serie_bac === 'BT' ? 'selected' : '' }}>BT - Brevet de Technicien</option>
                            <option value="BP" {{ $bachelier->serie_bac === 'BP' ? 'selected' : '' }}>BP - Brevet Professionnel</option>
                        </optgroup>
                    </select>
                </div>
                <div>
                    <label class="ds-label" for="note_bac">Note BAC</label>
                    <input type="number" id="note_bac" name="note_bac" value="{{ $bachelier->note_bac }}" step="0.01" min="0" max="400" readonly class="ds-field" style="{{ $lecture }}">
                    <p class="ds-hint">Sur 400 points (système ivoirien)</p>
                </div>
                <div>
                    <label class="ds-label" for="mention">Mention</label>
                    <select id="mention" name="mention" disabled class="ds-field" style="{{ $lecture }}">
                        <option value="passable" {{ $bachelier->mention === 'passable' ? 'selected' : '' }}>Passable</option>
                        <option value="assez_bien" {{ $bachelier->mention === 'assez_bien' ? 'selected' : '' }}>Assez Bien</option>
                        <option value="bien" {{ $bachelier->mention === 'bien' ? 'selected' : '' }}>Bien</option>
                        <option value="tres_bien" {{ $bachelier->mention === 'tres_bien' ? 'selected' : '' }}>Très Bien</option>
                    </select>
                </div>
                <div>
                    <label class="ds-label" for="etablissement_nom">Établissement</label>
                    <input type="text" id="etablissement_nom" name="etablissement_nom" value="{{ $bachelier->etablissement_nom }}" readonly class="ds-field" style="{{ $lecture }}">
                </div>
                <div>
                    <label class="ds-label" for="annee_bac">Année BAC</label>
                    <input type="number" id="annee_bac" name="annee_bac" value="{{ $bachelier->annee_bac }}" min="2020" max="2025" readonly class="ds-field" style="{{ $lecture }}">
                </div>
            </div>

            <hr class="ds-divider">

            {{-- Competences et langues --}}
            <div style="display:grid; gap:var(--space-2); grid-template-columns:repeat(auto-fit, minmax(260px, 1fr))">
                <div>
                    <label class="ds-label" for="competences">Compétences</label>
                    <textarea id="competences" name="competences" rows="3" class="ds-field ds-textarea">{{ is_array($bachelier->competences) ? implode(', ', $bachelier->competences) : $bachelier->competences }}</textarea>
                    <p class="ds-hint">Séparez les compétences par des virgules</p>
                </div>
                <div>
                    <label class="ds-label" for="langues">Langues</label>
                    <textarea id="langues" name="langues" rows="3" class="ds-field ds-textarea">{{ is_array($bachelier->langues) ? implode(', ', $bachelier->langues) : $bachelier->langues }}</textarea>
                    <p class="ds-hint">Séparez les langues par des virgules</p>
                </div>
            </div>

            <div>
                <label class="ds-label" for="motivation">Motivation</label>
                <textarea id="motivation" name="motivation" rows="4" class="ds-field ds-textarea">{{ $bachelier->motivation }}</textarea>
            </div>

            <div>
                <label class="ds-label" for="cv_path">CV (PDF)</label>
                <input type="file" id="cv_path" name="cv_path" accept=".pdf" class="ds-field" style="height:auto; padding:var(--space-1-5) var(--space-2)">
                @if($bachelier->cv_path)
                    <p class="ds-hint">
                        CV actuel :
                        <a href="{{ asset('storage/' . $bachelier->cv_path) }}" target="_blank" rel="noopener" class="ds-text-accent">Voir le CV</a>
                    </p>
                @endif
            </div>

            <hr class="ds-divider">

            <div style="display:flex; justify-content:flex-end">
                <button type="submit" id="profile-submit" class="ds-btn ds-btn-primary ds-btn-lg">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                        <path d="M15.2 3a2 2 0 0 1 1.4.6l3.8 3.8a2 2 0 0 1 .6 1.4V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z"/><path d="M17 21v-7a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v7"/><path d="M7 3v4a1 1 0 0 0 1 1h7"/>
                    </svg>
                    Enregistrer
                </button>
            </div>
        </form>
    </section>

</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const photoInput = document.getElementById('photo');
    const photoContainer = document.getElementById('photo-container');
    const photoActions = document.getElementById('photo-actions');
    const choosePhotoBtn = document.getElementById('choose-photo');
    const form = document.getElementById('profile-form');
    let removePhotoBtn = document.getElementById('remove-photo');

    if (!photoInput || !photoContainer || !form) { return; }

    // Icones en SVG inline : cette vue n utilise plus la police d icones externe,
    // le JS ne doit donc plus injecter de balise <i> ni rappeler lucide.createIcons().
    const SVG_OPEN = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">';
    const ICON_USER = '<svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><path d="M12 3a4 4 0 1 0 0 8 4 4 0 0 0 0-8"/></svg>';
    const ICON_TRASH = SVG_OPEN + '<path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>';
    const ICON_LOADER = SVG_OPEN + '<path d="M12 2v4"/><path d="M16.2 7.8l2.9-2.9"/><path d="M18 12h4"/><path d="M16.2 16.2l2.9 2.9"/><path d="M12 18v4"/><path d="M4.9 19.1l2.9-2.9"/><path d="M2 12h4"/><path d="M4.9 4.9l2.9 2.9"/></svg>';

    if (choosePhotoBtn) {
        choosePhotoBtn.addEventListener('click', function () { photoInput.click(); });
    }

    function removePhoto() {
        photoInput.value = '';
        photoContainer.innerHTML = ICON_USER;

        const existing = document.getElementById('remove-photo');
        if (existing) { existing.remove(); }
        removePhotoBtn = null;

        let removeInput = document.getElementById('remove_photo');
        if (!removeInput) {
            removeInput = document.createElement('input');
            removeInput.type = 'hidden';
            removeInput.id = 'remove_photo';
            removeInput.name = 'remove_photo';
            removeInput.value = '1';
            form.appendChild(removeInput);
        }
    }

    photoInput.addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (!file) { return; }

        if (file.size > 2 * 1024 * 1024) {
            alert('La taille du fichier ne doit pas dépasser 2MB');
            this.value = '';
            return;
        }
        if (!file.type.startsWith('image/')) {
            alert('Veuillez sélectionner un fichier image');
            this.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function (ev) {
            let img = document.getElementById('profile-image');
            if (img) {
                img.src = ev.target.result;
            } else {
                img = document.createElement('img');
                img.id = 'profile-image';
                img.src = ev.target.result;
                img.alt = 'Photo de profil';
                img.width = 96;
                img.height = 96;
                img.style.width = '96px';
                img.style.height = '96px';
                img.style.objectFit = 'cover';
                photoContainer.innerHTML = '';
                photoContainer.appendChild(img);
            }

            if (!document.getElementById('remove-photo') && photoActions) {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.id = 'remove-photo';
                btn.className = 'ds-btn ds-btn-ghost ds-btn-md';
                btn.innerHTML = ICON_TRASH + 'Supprimer';
                photoActions.appendChild(btn);
                btn.addEventListener('click', removePhoto);
                removePhotoBtn = btn;
            }
        };
        reader.readAsDataURL(file);
    });

    if (removePhotoBtn) {
        removePhotoBtn.addEventListener('click', removePhoto);
    }

    form.addEventListener('submit', function () {
        const submitBtn = document.getElementById('profile-submit');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = ICON_LOADER + 'Enregistrement...';
        }
    });
});
</script>
@endpush
@endsection
