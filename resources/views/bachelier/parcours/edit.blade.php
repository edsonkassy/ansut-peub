@extends('layouts.bachelier')

{{-- Active le design system PEUB sur cette vue uniquement.

     NOTE DE REVUE : ce formulaire est le jumeau de parcours/create.blade.php.
     Les deux vues ne different que par le titre, le verbe @method, les valeurs
     old(), le caractere obligatoire du justificatif et le libelle du bouton.
     Elles gagneraient a etre factorisees dans un partiel commun ; cela suppose de
     creer un fichier hors du perimetre de ce lot, donc signale plutot que fait. --}}
@section('html-attrs', 'data-ds')

@section('title', 'Modifier un Parcours - Bachelier PEUB')

@section('content')
<div class="ds-container-tight ds-stack" style="padding-block: var(--space-4)">

    <header>
        <p class="ds-overline" style="display:flex; align-items:center; gap:var(--space-0-5)">
            <a href="{{ route('bachelier.parcours.index') }}"
               style="display:inline-flex; align-items:center; gap:var(--space-0-5); min-height:44px; color:inherit">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                    <path d="m15 18-6-6 6-6"/>
                </svg>
                PARCOURS
            </a>
        </p>
        <h1 style="margin-top: var(--space-1)">Modifier le parcours</h1>
        <p class="ds-text-secondary" style="margin-top: var(--space-1)">Mettez à jour les informations de votre parcours</p>
    </header>

    <section class="ds-card" style="padding: var(--space-3)">
        <form action="{{ route('bachelier.parcours.update', $parcour) }}" method="POST" enctype="multipart/form-data" class="ds-stack-sm">
            @csrf
            @method('PATCH')
                <div style="display:grid; gap:var(--space-2); grid-template-columns:repeat(auto-fit, minmax(240px, 1fr))">

                    <div style="grid-column:1 / -1">
                        <label class="ds-label" for="universite_nom">Nom de l'établissement</label>
                        <input type="text" name="universite_nom" id="universite_nom" required
                               class="ds-field @error('universite_nom') ds-field-error @enderror"
                               value="{{ old('universite_nom', $parcour->universite_nom) }}">
                        @error('universite_nom')<p class="ds-error-text">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="ds-label" for="pays">Pays</label>
                        <select name="pays" id="pays" required class="ds-field @error('pays') ds-field-error @enderror">
                            @foreach($pays as $p)
                                <option value="{{ $p }}" @selected(old('pays', $parcour->pays) === $p)>{{ $p }}</option>
                            @endforeach
                        </select>
                        @error('pays')<p class="ds-error-text">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="ds-label" for="niveau">Niveau d'étude</label>
                        <select name="niveau" id="niveau" required class="ds-field @error('niveau') ds-field-error @enderror">
                            <option value="">Sélectionnez un niveau</option>
                            @foreach($niveaux as $group => $options)
                                <optgroup label="{{ $group }}">
                                    @foreach($options as $option)
                                        <option value="{{ $option }}" @selected(old('niveau', $niveauSelectionne) == $option)>{{ $option }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        @error('niveau')<p class="ds-error-text">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="ds-label" for="annee_academique">Année académique</label>
                        <select name="annee_academique" id="annee_academique" required class="ds-field @error('annee_academique') ds-field-error @enderror">
                            <option value="">Sélectionnez une année</option>
                            @foreach($annees as $annee)
                                <option value="{{ $annee }}" @selected(old('annee_academique', $parcour->annee_academique) == $annee)>{{ $annee }}</option>
                            @endforeach
                        </select>
                        @error('annee_academique')<p class="ds-error-text">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="ds-label" for="performance">Moyenne sur 20</label>
                        <input type="number" step="0.01" min="0" max="20" required
                               name="performance" id="performance" placeholder="Ex : 15,50"
                               class="ds-field @error('performance') ds-field-error @enderror"
                               value="{{ old('performance', $parcour->performance) }}">
                        @error('performance')<p class="ds-error-text">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="ds-label" for="mention">Mention</label>
                        <select name="mention" id="mention" required class="ds-field @error('mention') ds-field-error @enderror">
                            <option value="">Sélectionnez une mention</option>
                            @foreach($mentions as $mention)
                                <option value="{{ $mention }}" @selected(old('mention', $parcour->mention) == $mention)>{{ $mention }}</option>
                            @endforeach
                        </select>
                        @error('mention')<p class="ds-error-text">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="ds-label" for="statut">Statut</label>
                        <select name="statut" id="statut" required class="ds-field @error('statut') ds-field-error @enderror">
                            @foreach($statuts as $statut)
                                <option value="{{ $statut }}" @selected(old('statut', $parcour->statut) == $statut)>{{ ucfirst(str_replace('_', ' ', $statut)) }}</option>
                            @endforeach
                        </select>
                        @error('statut')<p class="ds-error-text">{{ $message }}</p>@enderror
                    </div>

                    <div style="grid-column:1 / -1">
                        <label class="ds-label" for="attestation_admission_file">Justificatif</label>
                        <input type="file" name="attestation_admission_file" id="attestation_admission_file"
                               class="ds-field @error('attestation_admission_file') ds-field-error @enderror"
                               style="height:auto; padding:var(--space-1-5) var(--space-2)">
                        <p class="ds-hint">Attestation d'admission, bulletin ou tout document équivalent.</p>
                        @if ($parcour->attestation_admission_file)
                            <p class="ds-hint">
                                Fichier actuel :
                                <a href="{{ asset('storage/' . $parcour->attestation_admission_file) }}" target="_blank" rel="noopener" class="ds-text-accent">
                                    {{ basename($parcour->attestation_admission_file) }}
                                </a>
                            </p>
                        @endif
                        @error('attestation_admission_file')<p class="ds-error-text">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div style="display:flex; justify-content:flex-end; gap:var(--space-1); flex-wrap:wrap; padding-top:var(--space-2); border-top:1px solid var(--border-default)">
                    <a href="{{ route('bachelier.parcours.index') }}" class="ds-btn ds-btn-secondary ds-btn-md">
                        Annuler
                    </a>
                    <button type="submit" class="ds-btn ds-btn-primary ds-btn-md">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                            <path d="M15.2 3a2 2 0 0 1 1.4.6l3.8 3.8a2 2 0 0 1 .6 1.4V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z"/><path d="M17 21v-7a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v7"/><path d="M7 3v4a1 1 0 0 0 1 1h7"/>
                        </svg>
                        Mettre à jour
                    </button>
                </div>
        </form>
    </section>

</div>
@endsection
