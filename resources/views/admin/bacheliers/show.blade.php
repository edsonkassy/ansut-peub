@extends('layouts.admin')

@section('title', 'Détails Bachelier - ' . $bachelier->nom . ' ' . $bachelier->prenoms)

@section('page-title', 'Détails du Bachelier')

@section('content')
<!-- Header avec informations principales -->
<div class="bg-white border border-gray-300 mb-6 p-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <a href="{{ route('admin.bacheliers.index') }}" 
               class="mr-6 text-gray-600 hover:text-gray-900">
                <i data-lucide="arrow-left" class="w-6 h-6"></i>
            </a>
            <div class="w-24 h-24 rounded-lg overflow-hidden border-2 border-gray-200 mr-6 bg-gray-50">
                @if($bachelier->photo_profil)
                    <img src="{{ asset('storage/' . $bachelier->photo_profil) }}" 
                         alt="Photo de profil" 
                         class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center bg-primary-50">
                        <i data-lucide="user" class="w-12 h-12 text-primary-300"></i>
                    </div>
                @endif
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ $bachelier->nom }} {{ $bachelier->prenoms }}</h2>
                <p class="text-gray-600">{{ $bachelier->email_eleve }}</p>
                <div class="flex items-center mt-2 space-x-4">
                    @switch($bachelier->status_profil)
                        @case('verifie')
                            <span class="px-3 py-1 text-sm bg-primary-100 text-primary-700">✓ Profil Vérifié</span>
                            @break
                        @case('en_attente')
                            <span class="px-3 py-1 text-sm bg-secondary-100 text-secondary-700">⏱ En Attente</span>
                            @break
                        @case('incomplet')
                            <span class="px-3 py-1 text-sm bg-gray-100 text-gray-700">⚠ Incomplet</span>
                            @break
                    @endswitch
                    
                    @if($bachelier->boursier_peub)
                        <span class="px-3 py-1 text-sm bg-secondary-100 text-secondary-700">
                            <i data-lucide="award" class="w-4 h-4 inline mr-1"></i>
                            Boursier PEUB
                        </span>
                    @endif
                </div>
            </div>
        </div>
        <div class="flex flex-col space-y-2">
            <!-- Actions rapides -->
            @if($bachelier->user->status === 'active')
                <form action="{{ route('admin.bacheliers.suspend', $bachelier->user->id) }}" method="POST">
                    @csrf
                    <button type="submit" 
                            class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 flex items-center justify-center border-2 border-red-600 hover:border-red-700 rounded-md"
                            onclick="return confirm('Êtes-vous sûr de vouloir désactiver ce compte ?')">
                        <i data-lucide="user-x" class="w-4 h-4 mr-2"></i>
                        Désactiver le compte
                    </button>
                </form>
            @else
                <form action="{{ route('admin.bacheliers.validate', $bachelier->user->id) }}" method="POST">
                    @csrf
                    <button type="submit" 
                            class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 flex items-center justify-center border-2 border-green-600 hover:border-green-700 rounded-md">
                        <i data-lucide="user-check" class="w-4 h-4 mr-2"></i>
                        Activer le compte
                    </button>
                </form>
            @endif

            @if(!$bachelier->boursier_peub)
                <form action="{{ route('admin.bacheliers.toggle-boursier', $bachelier) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" 
                            class="w-full bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 flex items-center justify-center border-2 border-primary-600 hover:border-primary-700 rounded-md"
                            onclick="return confirm('Êtes-vous sûr de vouloir attribuer la bourse PEUB ?')">
                        <i data-lucide="award" class="w-4 h-4 mr-2"></i>
                        Attribuer la bourse PEUB
                    </button>
                </form>
            @else
                <form action="{{ route('admin.bacheliers.toggle-boursier', $bachelier) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" 
                            class="w-full bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 flex items-center justify-center border-2 border-orange-600 hover:border-orange-700 rounded-md"
                            onclick="return confirm('Êtes-vous sûr de vouloir retirer la bourse PEUB ?')">
                        <i data-lucide="award-off" class="w-4 h-4 mr-2"></i>
                        Retirer la bourse PEUB
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Colonne principale -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Score PEUB et Graphique Radar -->
        <div class="bg-white border border-gray-300 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i data-lucide="pie-chart" class="w-5 h-5 mr-2 text-primary-600"></i>
                Score PEUB (100 points)
            </h3>
            
            @if($bachelier->score_final_peub !== null)
                <!-- Score global -->
                <div class="mb-8">
                    <div class="flex justify-between items-center mb-2">
                        <div class="flex items-center">
                            <span class="text-2xl font-bold text-gray-900">{{ number_format($bachelier->score_final_peub, 2) }}/100</span>
                            @if($bachelier->rang_temporaire)
                                <div class="ml-3 flex items-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-50 text-blue-700 border border-blue-200">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                        </svg>
                                        Rang #{{ $bachelier->rang_temporaire }}
                                    </span>
                                    <span class="ml-1 text-xs text-gray-400 cursor-help" 
                                          title="Ce rang est temporaire et peut évoluer avec les nouvelles inscriptions">
                                        *
                                    </span>
                                </div>
                            @endif
                        </div>
                        @if($bachelier->date_calcul_scoring)
                            <span class="text-sm text-gray-500">
                                Mis à jour le {{ \Carbon\Carbon::parse($bachelier->date_calcul_scoring)->format('d/m/Y') }}
                            </span>
                        @endif
                    </div>
                    <div class="bg-gray-200 h-3 rounded-full overflow-hidden">
                        <div class="bg-primary-600 h-3 rounded-full transition-all duration-500" 
                             style="width: {{ $bachelier->score_final_peub }}%"></div>
                    </div>
                    @if($bachelier->rang_temporaire)
                        <p class="mt-2 text-xs text-gray-500 italic">
                            * Classé {{ $bachelier->rang_temporaire }}{{ $bachelier->rang_temporaire == 1 ? 'er' : 'ème' }} sur {{ \App\Models\Bachelier::whereNotNull('score_final_peub')->count() }} bacheliers évalués
                        </p>
                    @endif
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Graphique Radar -->
                    <div class="bg-white p-4 border border-gray-200">
                        <h4 class="text-sm font-medium text-gray-700 mb-4">Répartition des scores</h4>
                        <div class="aspect-square">
                            <canvas id="scoreRadarChart"></canvas>
                        </div>
                    </div>
                    
                    <!-- Scores détaillés -->
                    <div class="bg-white p-4 border border-gray-200">
                        <h4 class="text-sm font-medium text-gray-700 mb-4">Détail des composantes (barème 100 points)</h4>
                        <div class="space-y-4">
                            <!-- 1. Excellence Académique -->
                            <div>
                                <div class="flex justify-between text-sm mb-2">
                                    <span class="text-gray-600">Excellence Académique</span>
                                    <span class="font-medium">{{ number_format($bachelier->score_academique, 0) }}/50</span>
                                </div>
                                <div class="bg-gray-100 h-2 rounded-full overflow-hidden">
                                    <div class="bg-blue-500 h-2 transition-all duration-500" 
                                         style="width: {{ ($bachelier->score_academique / 50) * 100 }}%"></div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">
                                    Mention: {{ ucfirst(str_replace('_', ' ', $bachelier->mention ?? 'N/A')) }}
                                </p>
                            </div>
                            
                            <!-- 2. Situation Handicap -->
                            <div>
                                <div class="flex justify-between text-sm mb-2">
                                    <span class="text-gray-600">Situation Handicap</span>
                                    <span class="font-medium">{{ number_format($bachelier->score_geographique, 0) }}/20</span>
                                </div>
                                <div class="bg-gray-100 h-2 rounded-full overflow-hidden">
                                    <div class="bg-yellow-500 h-2 transition-all duration-500" 
                                         style="width: {{ ($bachelier->score_geographique / 20) * 100 }}%"></div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">
                                    @if(is_array($bachelier->situations_particulieres) && in_array('handicap', $bachelier->situations_particulieres))
                                        Oui (20pts)
                                    @else
                                        Non (10pts)
                                    @endif
                                </p>
                            </div>
                            
                            <!-- 3. Situation Matrimoniale (Orphelinat) -->
                            <div>
                                <div class="flex justify-between text-sm mb-2">
                                    <span class="text-gray-600">Situation Matrimoniale (Orphelinat)</span>
                                    <span class="font-medium">{{ number_format($bachelier->score_socio_economique, 0) }}/20</span>
                                </div>
                                <div class="bg-gray-100 h-2 rounded-full overflow-hidden">
                                    <div class="bg-green-500 h-2 transition-all duration-500" 
                                         style="width: {{ ($bachelier->score_socio_economique / 20) * 100 }}%"></div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">
                                    @if($bachelier->situation_orphelinat === 'pere_et_mere')
                                        Orphelin de père et mère (20pts)
                                    @elseif($bachelier->situation_orphelinat === 'pere_ou_mere')
                                        Orphelin de père ou mère (15pts)
                                    @elseif(is_array($bachelier->situations_particulieres) && in_array('orphelin', $bachelier->situations_particulieres))
                                        Orphelin (15pts)
                                    @else
                                        Non orphelin (0pts)
                                    @endif
                                </p>
                            </div>
                            
                            <!-- 4. Genre -->
                            <div>
                                <div class="flex justify-between text-sm mb-2">
                                    <span class="text-gray-600">Genre</span>
                                    <span class="font-medium">{{ number_format($bachelier->score_motivations, 0) }}/10</span>
                                </div>
                                <div class="bg-gray-100 h-2 rounded-full overflow-hidden">
                                    <div class="bg-purple-500 h-2 transition-all duration-500" 
                                         style="width: {{ ($bachelier->score_motivations / 10) * 100 }}%"></div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">
                                    Sexe: {{ $bachelier->sexe === 'F' ? 'Féminin (10pts)' : 'Masculin (5pts)' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-8">
                    <div class="text-gray-400 mb-2">
                        <i data-lucide="pie-chart" class="w-12 h-12 mx-auto"></i>
                    </div>
                    <p class="text-gray-600">Le score PEUB n'a pas encore été calculé pour ce bachelier.</p>
                </div>
            @endif
        </div>

        <!-- Gestion des dotations -->
        @if($bachelier->boursier_peub)
            @livewire('admin-bachelier-dotations', ['bachelier' => $bachelier])
        @endif


        <!-- Informations personnelles -->
        <div class="bg-white border border-gray-300 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i data-lucide="user" class="w-5 h-5 mr-2 text-primary-600"></i>
                Informations Personnelles
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nom complet</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $bachelier->nom }} {{ $bachelier->prenoms }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Date de naissance</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $bachelier->date_naissance ? \Carbon\Carbon::parse($bachelier->date_naissance)->format('d/m/Y') : 'Non renseignée' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Genre</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $bachelier->sexe === 'M' ? 'Masculin' : 'Féminin' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Téléphone Élève</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $bachelier->telephone_eleve }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Téléphone Parent</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $bachelier->telephone_parent }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email Élève</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $bachelier->email_eleve }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email Parent</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $bachelier->email_parent }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Région</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $bachelier->region }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Commune</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $bachelier->commune }}</p>
                </div>
            </div>
        </div>

        <!-- Informations académiques -->
        <div class="bg-white border border-gray-300 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i data-lucide="book" class="w-5 h-5 mr-2 text-primary-600"></i>
                Informations Académiques
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Série du Bac</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $bachelier->serie_bac }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Année du Bac</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $bachelier->annee_bac }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Note du Bac</label>
                    <p class="mt-1 text-sm text-gray-900">
                        {{ number_format($bachelier->note_bac, 0) }}/400
                        <span class="text-sm text-gray-500 ml-2">({{ number_format(($bachelier->note_bac / 400) * 100, 1) }}%)</span>
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Mention</label>
                    <p class="mt-1 text-sm text-gray-900">
                        @switch($bachelier->mention)
                            @case('passable')
                                <span class="px-2 py-1 bg-gray-100 text-gray-700 text-sm">Passable</span>
                                @break
                            @case('assez_bien')
                                <span class="px-2 py-1 bg-blue-100 text-blue-700 text-sm">Assez Bien</span>
                                @break
                            @case('bien')
                                <span class="px-2 py-1 bg-green-100 text-green-700 text-sm">Bien</span>
                                @break
                            @case('tres_bien')
                                <span class="px-2 py-1 bg-purple-100 text-purple-700 text-sm">Très Bien</span>
                                @break
                            @default
                                <span class="text-gray-500">Non renseignée</span>
                        @endswitch
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Établissement</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $bachelier->etablissement_nom }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Type d'établissement</label>
                    <p class="mt-1 text-sm text-gray-900">
                        @switch($bachelier->etablissement_type)
                            @case('public')
                                Public
                                @break
                            @case('prive_homologue')
                                Privé homologué
                                @break
                            @case('prive_non_homologue')
                                Privé non homologué
                                @break
                        @endswitch
                    </p>
                </div>
            </div>
        </div>

        <!-- Parcours Universitaire -->
        <div class="bg-white border border-gray-300 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i data-lucide="graduation-cap" class="w-5 h-5 mr-2 text-primary-600"></i>
                Parcours Universitaire
            </h3>
            @if($bachelier->parcoursUniversitaires->isNotEmpty())
                <div class="space-y-4">
                    @foreach($bachelier->parcoursUniversitaires as $parcours)
                        <div class="border border-gray-200 p-4 rounded-lg">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-bold text-gray-800">{{ $parcours->universite_nom }}</p>
                                    <p class="text-sm text-gray-600">{{ $parcours->pays }}</p>
                                </div>
                                <span class="px-2 py-1 text-xs font-medium rounded-full
                                    @switch($parcours->statut)
                                        @case('en_cours') bg-blue-100 text-blue-800 @break
                                        @case('termine') bg-green-100 text-green-800 @break
                                        @case('abandonne') bg-red-100 text-red-800 @break
                                    @endswitch
                                ">
                                    {{ ucfirst($parcours->statut) }}
                                </span>
                            </div>
                            <div class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                <div>
                                    <label class="block text-xs text-gray-500">Niveau</label>
                                    <p>{{ ucfirst($parcours->niveau) }}</p>
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-500">Année Académique</label>
                                    <p>{{ $parcours->annee_academique }}</p>
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-500">Performance</label>
                                    <p>{{ $parcours->performance ? $parcours->performance . '/20' : 'N/A' }}</p>
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-500">Mention</label>
                                    <p>{{ $parcours->mention ?? 'N/A' }}</p>
                                </div>
                            </div>
                             @if($parcours->attestation_admission_file)
                                <div class="mt-4">
                                    <a href="{{ asset('storage/' . $parcours->attestation_admission_file) }}" target="_blank" class="text-sm text-primary-600 hover:text-primary-800 flex items-center">
                                        <i data-lucide="file-down" class="w-4 h-4 mr-1"></i>
                                        Voir l'attestation d'admission
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <div class="text-gray-400 mb-2">
                        <i data-lucide="graduation-cap" class="w-12 h-12 mx-auto"></i>
                    </div>
                    <p class="text-gray-600">Aucun parcours universitaire n'a été renseigné pour ce bachelier.</p>
                </div>
            @endif
        </div>

        <!-- Informations socio-économiques -->
        <div class="bg-white border border-gray-300 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i data-lucide="users" class="w-5 h-5 mr-2 text-primary-600"></i>
                Informations Socio-économiques
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Profession du père</label>
                    <p class="mt-1 text-sm text-gray-900">
                        @switch($bachelier->profession_pere)
                            @case('1')
                                Cadres, professions intellectuelles supérieures
                                @break
                            @case('2')
                                Intermédiaires de l'administration/services
                                @break
                            @case('3')
                                Employés de bureau
                                @break
                            @case('4')
                                Ouvriers qualifiés/artisans
                                @break
                            @case('5')
                                Travailleurs agricoles, pêcheurs
                                @break
                            @case('6')
                                Travailleurs non qualifiés
                                @break
                            @case('7')
                                Personnes sans emploi ou informel non déclaré
                                @break
                            @case('non_applicable')
                                Non applicable
                                @break
                            @default
                                {{ $bachelier->profession_pere }}
                        @endswitch
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Profession de la mère</label>
                    <p class="mt-1 text-sm text-gray-900">
                        @switch($bachelier->profession_mere)
                            @case('1')
                                Cadres, professions intellectuelles supérieures
                                @break
                            @case('2')
                                Intermédiaires de l'administration/services
                                @break
                            @case('3')
                                Employés de bureau
                                @break
                            @case('4')
                                Ouvriers qualifiés/artisans
                                @break
                            @case('5')
                                Travailleurs agricoles, pêcheurs
                                @break
                            @case('6')
                                Travailleurs non qualifiés
                                @break
                            @case('7')
                                Personnes sans emploi ou informel non déclaré
                                @break
                            @case('non_applicable')
                                Non applicable
                                @break
                            @default
                                {{ $bachelier->profession_mere }}
                        @endswitch
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Situations particulières</label>
                    <div class="mt-1 space-y-1">
                        @if(!empty($bachelier->situations_particulieres))
                            @foreach($bachelier->situations_particulieres as $situation)
                                <span class="inline-block px-2 py-1 text-sm bg-gray-100 text-gray-700 mr-2">
                                    @switch($situation)
                                        @case('handicap')
                                            Situation de handicap
                                            @break
                                        @case('orphelin')
                                            Orphelin
                                            @break
                                        @case('autre')
                                            Autre situation
                                            @break
                                        @default
                                            {{ $situation }}
                                    @endswitch
                                </span>
                            @endforeach
                        @else
                            <p class="text-sm text-gray-500">Aucune situation particulière</p>
                        @endif
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Équipement numérique</label>
                    <div class="mt-1 space-y-1">
                        <p class="text-sm">
                            <i data-lucide="laptop" class="w-4 h-4 inline mr-1"></i>
                            Ordinateur: {{ $bachelier->possede_ordinateur ? 'Oui' : 'Non' }}
                        </p>
                        <p class="text-sm">
                            <i data-lucide="smartphone" class="w-4 h-4 inline mr-1"></i>
                            Smartphone: {{ $bachelier->acces_smartphone ? 'Oui' : 'Non' }}
                        </p>
                        <p class="text-sm">
                            <i data-lucide="wifi" class="w-4 h-4 inline mr-1"></i>
                            Internet: {{ ucfirst($bachelier->connexion_internet) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Motivation et Projet Professionnel -->
        <div class="bg-white border border-gray-300 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i data-lucide="target" class="w-5 h-5 mr-2 text-primary-600"></i>
                Motivation et Projet Professionnel
            </h3>
            
            <!-- Motivation -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Lettre de Motivation</label>
                <div class="bg-gray-50 p-4 rounded">
                    <p class="text-sm text-gray-900 whitespace-pre-line">{{ $bachelier->motivation }}</p>
                </div>
                @if($bachelier->motivation_ai_score)
                <div class="mt-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">Score IA Motivation</span>
                        <span class="text-sm font-medium">{{ number_format($bachelier->motivation_ai_score, 2) }}/5</span>
                    </div>
                    <div class="bg-gray-200 h-2 rounded-full overflow-hidden">
                        <div class="bg-primary-600 h-2 transition-all duration-500" 
                             style="width: {{ ($bachelier->motivation_ai_score / 5) * 100 }}%"></div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Projet Professionnel -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Projet Professionnel</label>
                <div class="bg-gray-50 p-4 rounded">
                    <p class="text-sm text-gray-900 whitespace-pre-line">{{ $bachelier->projet_professionnel }}</p>
                </div>
            </div>
        </div>

        <!-- Candidatures -->
        <div class="bg-white border border-gray-300 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i data-lucide="file-text" class="w-5 h-5 mr-2 text-primary-600"></i>
                Candidatures ({{ $bachelier->candidatures->count() }})
            </h3>
            @forelse($bachelier->candidatures as $candidature)
            <div class="border border-gray-200 p-4 mb-4 last:mb-0">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="font-medium text-gray-900">{{ $candidature->opportunite->titre }}</h4>
                        <p class="text-sm text-gray-600">{{ $candidature->opportunite->partenaire->nom_organisation }}</p>
                        <p class="text-xs text-gray-500">Candidature du {{ $candidature->created_at->format('d/m/Y à H:i') }}</p>
                    </div>
                    <div class="text-right">
                        @switch($candidature->status)
                            @case('pending')
                                <span class="px-2 py-1 text-xs bg-secondary-100 text-secondary-700">En attente</span>
                                @break
                            @case('accepted')
                                <span class="px-2 py-1 text-xs bg-primary-100 text-primary-700">Acceptée</span>
                                @break
                            @case('rejected')
                                <span class="px-2 py-1 text-xs bg-gray-100 text-gray-700">Refusée</span>
                                @break
                            @case('reviewed')
                                <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-700">En cours de revue</span>
                                @break
                            @case('participated')
                                <span class="px-2 py-1 text-xs bg-green-100 text-green-700">Participé</span>
                                @break
                        @endswitch
                    </div>
                </div>
                @if($candidature->lettre_motivation)
                <div class="mt-3">
                    <p class="text-sm text-gray-700">
                        <strong>Lettre de motivation :</strong>
                        {{ Str::limit($candidature->lettre_motivation, 200) }}
                    </p>
                </div>
                @endif
            </div>
            @empty
            <p class="text-gray-500 text-center py-4">Aucune candidature pour le moment.</p>
            @endforelse
        </div>

        <!-- Favoris -->
        <div class="bg-white border border-gray-300 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i data-lucide="heart" class="w-5 h-5 mr-2 text-primary-600"></i>
                Opportunités en Favoris ({{ $bachelier->favoris->count() }})
            </h3>
            @forelse($bachelier->favoris as $favori)
            <div class="flex items-center justify-between p-3 border border-gray-200 mb-2 last:mb-0">
                <div>
                    <p class="font-medium text-gray-900">{{ $favori->opportunite->titre }}</p>
                    <p class="text-sm text-gray-600">{{ $favori->opportunite->partenaire->nom_organisation }}</p>
                </div>
                <p class="text-xs text-gray-500">{{ $favori->created_at->format('d/m/Y') }}</p>
            </div>
            @empty
            <p class="text-gray-500 text-center py-4">Aucun favori pour le moment.</p>
            @endforelse
        </div>

        <!-- Analyse IA -->
        <div class="bg-white border border-gray-300 mb-6">
            <div class="p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Analyse de l'Intelligence Artificielle</h2>
                
                <div class="space-y-6">
                    <!-- Score Motivation -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-gray-700">Score Motivation</span>
                            <span class="text-sm font-medium">{{ number_format($bachelier->motivation_ai_score, 2) }}/5</span>
                        </div>
                        <div class="h-2 bg-gray-200">
                            <div class="h-2 bg-blue-600" style="width: {{ ($bachelier->motivation_ai_score / 5) * 100 }}%"></div>
                        </div>
                        @if($bachelier->motivation_ai_feedback)
                            <p class="mt-2 text-sm text-gray-600">{{ $bachelier->motivation_ai_feedback }}</p>
                        @endif
                    </div>

                    <!-- Score Projet Professionnel -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-gray-700">Score Projet Professionnel</span>
                            <span class="text-sm font-medium">{{ number_format($bachelier->projet_professionnel_ai_score, 2) }}/5</span>
                        </div>
                        <div class="h-2 bg-gray-200">
                            <div class="h-2 bg-blue-600" style="width: {{ ($bachelier->projet_professionnel_ai_score / 5) * 100 }}%"></div>
                        </div>
                        @if($bachelier->projet_professionnel_ai_feedback)
                            <p class="mt-2 text-sm text-gray-600">{{ $bachelier->projet_professionnel_ai_feedback }}</p>
                        @endif
                    </div>

                    <!-- Analyse des compétences -->
                    @if($bachelier->competences_extraites)
                        <div>
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Compétences identifiées</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach(json_decode($bachelier->competences_extraites) as $competence)
                                    <span class="px-2 py-1 bg-blue-50 text-blue-700 text-sm">{{ $competence }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Analyse des centres d'intérêt -->
                    @if($bachelier->centres_interet_extraits)
                        <div>
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Centres d'intérêt identifiés</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach(json_decode($bachelier->centres_interet_extraits) as $interet)
                                    <span class="px-2 py-1 bg-green-50 text-green-700 text-sm">{{ $interet }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Recommandations IA -->
                    @if($bachelier->recommandations_ia)
                        <div>
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Recommandations</h3>
                            <div class="bg-yellow-50 border border-yellow-100 p-4 rounded">
                                <ul class="list-disc list-inside space-y-1 text-sm text-gray-600">
                                    @foreach(json_decode($bachelier->recommandations_ia) as $recommandation)
                                        <li>{{ $recommandation }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <!-- Score PEUB -->
                    @if($bachelier->peub_score)
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-gray-700">Score PEUB Global</span>
                                <span class="text-sm font-medium">{{ number_format($bachelier->peub_score, 2) }}/100</span>
                            </div>
                            <div class="h-2 bg-gray-200">
                                <div class="h-2 {{ $bachelier->peub_score >= 70 ? 'bg-green-600' : ($bachelier->peub_score >= 50 ? 'bg-yellow-600' : 'bg-red-600') }}" 
                                    style="width: {{ $bachelier->peub_score }}%">
                                </div>
                            </div>
                            @if($bachelier->peub_score_details)
                                <div class="mt-4 space-y-2">
                                    @foreach(json_decode($bachelier->peub_score_details) as $critere => $score)
                                        <div>
                                            <div class="flex items-center justify-between text-sm">
                                                <span class="text-gray-600">{{ $critere }}</span>
                                                <span class="font-medium">{{ number_format($score, 2) }}/20</span>
                                            </div>
                                            <div class="h-1 bg-gray-200">
                                                <div class="h-1 bg-blue-600" style="width: {{ ($score / 20) * 100 }}%"></div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Colonne latérale -->
    <div class="space-y-6">
        <!-- Documents -->
        <div class="bg-white border border-gray-300 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i data-lucide="file" class="w-5 h-5 mr-2 text-primary-600"></i>
                Documents
            </h3>
            <div class="space-y-4">
                @if($bachelier->piece_identite_file)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                    <div class="flex items-center">
                        <i data-lucide="id-card" class="w-5 h-5 text-gray-400 mr-3"></i>
                <div>
                            <p class="text-sm font-medium text-gray-900">Pièce d'identité</p>
                            <p class="text-xs text-gray-500">{{ ucfirst(str_replace('_', ' ', $bachelier->piece_identite_type)) }}</p>
                        </div>
                    </div>
                    <a href="{{ asset('storage/' . $bachelier->piece_identite_file) }}" 
                       class="text-primary-600 hover:text-primary-900"
                       target="_blank">
                        <i data-lucide="download" class="w-5 h-5"></i>
                    </a>
                </div>
                @endif

                @if($bachelier->collante_bac_file)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                    <div class="flex items-center">
                        <i data-lucide="award" class="w-5 h-5 text-gray-400 mr-3"></i>
                <div>
                            <p class="text-sm font-medium text-gray-900">Collante du BAC</p>
                            <p class="text-xs text-gray-500">Année {{ $bachelier->annee_bac }}</p>
                        </div>
                    </div>
                    <a href="{{ asset('storage/' . $bachelier->collante_bac_file) }}" 
                       class="text-primary-600 hover:text-primary-900"
                       target="_blank">
                        <i data-lucide="download" class="w-5 h-5"></i>
                    </a>
                </div>
                @endif

                @if($bachelier->cv_path)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                    <div class="flex items-center">
                        <i data-lucide="file-text" class="w-5 h-5 text-gray-400 mr-3"></i>
                <div>
                            <p class="text-sm font-medium text-gray-900">CV</p>
                </div>
                    </div>
                    <a href="{{ asset('storage/' . $bachelier->cv_path) }}" 
                       class="text-primary-600 hover:text-primary-900"
                       target="_blank">
                        <i data-lucide="download" class="w-5 h-5"></i>
                    </a>
                </div>
                @endif
            </div>
        </div>

        <!-- Dotations -->
        @if($bachelier->boursier_peub)
        <div class="bg-white border border-gray-300 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i data-lucide="gift" class="w-5 h-5 mr-2 text-primary-600"></i>
                Dotations
            </h3>
            @forelse($bachelier->dotations as $dotation)
            <div class="mb-4 p-4 bg-gray-50 rounded">
                <div class="flex items-center justify-between mb-2">
                    <span class="font-medium text-gray-900">{{ $dotation->nom_dotation }}</span>
                    <span class="px-2 py-1 text-xs {{ 
                        match($dotation->status) {
                            'active' => 'bg-green-100 text-green-800',
                            'suspendue' => 'bg-yellow-100 text-yellow-800',
                            'terminee' => 'bg-gray-100 text-gray-800',
                            default => 'bg-blue-100 text-blue-800'
                        }
                    }} rounded-full">
                        {{ ucfirst($dotation->status) }}
                    </span>
                </div>
                <p class="text-sm text-gray-600 mb-2">{{ $dotation->description }}</p>
                <div class="text-xs text-gray-500">
                    <p>Attribution: {{ \Carbon\Carbon::parse($dotation->date_attribution)->format('d/m/Y') }}</p>
                    @if($dotation->date_debut && $dotation->date_fin)
                    <p>Période: {{ \Carbon\Carbon::parse($dotation->date_debut)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($dotation->date_fin)->format('d/m/Y') }}</p>
                    @endif
                </div>
            </div>
            @empty
            <p class="text-sm text-gray-500">Aucune dotation attribuée</p>
            @endforelse
        </div>
        @endif

        <!-- Actions rapides -->
        <div class="bg-white border border-gray-300 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i data-lucide="zap" class="w-5 h-5 mr-2 text-primary-600"></i>
                Autres actions
            </h3>
            <div class="space-y-3">
                @if($bachelier->email_eleve)
                <a href="mailto:{{ $bachelier->email_eleve }}?subject=Contact%20depuis%20PEUB%20Admin&body=Bonjour%20{{ urlencode($bachelier->prenoms) }},%0A%0A" 
                   class="w-full bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 flex items-center justify-center rounded-md">
                    <i data-lucide="mail" class="w-4 h-4 mr-2"></i>
                    Envoyer un email
                </a>
                @else
                <button disabled 
                        class="w-full bg-gray-400 text-white px-4 py-2 flex items-center justify-center rounded-md cursor-not-allowed">
                    <i data-lucide="mail" class="w-4 h-4 mr-2"></i>
                    Email non disponible
                </button>
                @endif
                
                @if($bachelier->telephone_eleve)
                <a href="tel:{{ $bachelier->telephone_eleve }}" 
                   class="w-full bg-secondary-600 hover:bg-secondary-700 text-white px-4 py-2 flex items-center justify-center rounded-md">
                    <i data-lucide="phone" class="w-4 h-4 mr-2"></i>
                    Appeler: {{ $bachelier->telephone_eleve }}
                </a>
                @else
                <button disabled 
                        class="w-full bg-gray-400 text-white px-4 py-2 flex items-center justify-center rounded-md cursor-not-allowed">
                    <i data-lucide="phone" class="w-4 h-4 mr-2"></i>
                    Téléphone non disponible
                </button>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
    
    @if($bachelier->score_final_peub !== null)
    // Configuration du graphique radar
    const ctx = document.getElementById('scoreRadarChart').getContext('2d');
    new Chart(ctx, {
        type: 'radar',
        data: {
            labels: [
                'Excellence Académique (50pts)',
                'Handicap (20pts)',
                'Orphelinat (20pts)',
                'Genre (10pts)'
            ],
            datasets: [{
                label: 'Score PEUB (100 points)',
                data: [
                    {{ $bachelier->score_academique }},
                    {{ $bachelier->score_geographique }},
                    {{ $bachelier->score_socio_economique }},
                    {{ $bachelier->score_motivations }}
                ],
                fill: true,
                backgroundColor: 'rgba(59, 130, 246, 0.15)',
                borderColor: 'rgb(59, 130, 246)',
                pointBackgroundColor: 'rgb(59, 130, 246)',
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: 'rgb(59, 130, 246)',
                borderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                r: {
                    angleLines: {
                        color: 'rgba(0, 0, 0, 0.1)',
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)',
                    },
                    pointLabels: {
                        font: {
                            size: 12,
                            family: "'Inter', sans-serif"
                        }
                    },
                    suggestedMin: 0,
                    suggestedMax: 50,
                    ticks: {
                        stepSize: 10,
                        font: {
                            size: 10
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleFont: {
                        size: 13,
                        family: "'Inter', sans-serif"
                    },
                    bodyFont: {
                        size: 12,
                        family: "'Inter', sans-serif"
                    },
                    padding: 12,
                    displayColors: false
                }
            }
        }
    });
    @endif
});
</script>
@endpush 