@extends('layouts.guest')

@section('title', 'Vérification de votre candidature - PEUB')

@section('content')
<div class="min-h-screen py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-5xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-10">
            <div class="flex items-center justify-center mb-4">
                <div style="background: linear-gradient(135deg, #0E7490, #0c5f7a);" class="rounded-full p-4 shadow-lg">
                    <i data-lucide="eye" class="w-10 h-10 text-white"></i>
                </div>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-3">
                Vérifiez vos informations
            </h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Prenez le temps de relire attentivement toutes vos informations avant de finaliser votre inscription
            </p>
        </div>

        <!-- Alert info -->
        <div class="mb-6 bg-gradient-to-r from-[#0E7490]/10 via-blue-50/50 to-transparent p-6 rounded-2xl shadow-sm">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 rounded-xl bg-[#0E7490] flex items-center justify-center shadow-md">
                        <i data-lucide="info" class="w-5 h-5 text-white"></i>
                    </div>
                </div>
                <div class="flex-1">
                    <h4 class="text-base font-bold text-gray-900 mb-2">Important</h4>
                    <p class="text-sm text-gray-700 leading-relaxed">
                        Une fois validées, ces informations seront analysées par notre système IA pour calculer votre score PEUB. Assurez-vous qu'elles sont exactes et complètes.
                    </p>
                </div>
            </div>
        </div>

        <!-- Preview Card -->
        <div class="bg-white shadow-2xl rounded-xl overflow-hidden border border-gray-100 mb-6">
            <!-- Section 1: Informations générales -->
            <div class="border-b border-gray-200">
                <div class="bg-gradient-to-r from-[#0E7490]/10 to-[#0c5f7a]/10 px-8 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-[#0E7490] text-white flex items-center justify-center shadow-md">
                            <i data-lucide="user" class="w-5 h-5"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">Informations générales</h3>
                    </div>
                </div>
                <div class="px-8 py-6">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nom</dt>
                            <dd class="mt-1 text-base text-gray-900 font-semibold">{{ $data['nom'] }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Prénoms</dt>
                            <dd class="mt-1 text-base text-gray-900 font-semibold">{{ $data['prenoms'] }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Date de naissance</dt>
                            <dd class="mt-1 text-base text-gray-900">{{ \Carbon\Carbon::parse($data['date_naissance'])->format('d/m/Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Lieu de naissance</dt>
                            <dd class="mt-1 text-base text-gray-900">{{ $data['lieu_naissance'] }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Sexe</dt>
                            <dd class="mt-1 text-base text-gray-900">{{ $data['sexe'] === 'M' ? 'Masculin' : 'Féminin' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Type de pièce d'identité</dt>
                            <dd class="mt-1 text-base text-gray-900">{{ ucfirst(str_replace('_', ' ', $data['piece_identite_type'])) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Téléphone (Élève)</dt>
                            <dd class="mt-1 text-base text-gray-900">{{ $data['telephone_eleve'] }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Téléphone (Parent)</dt>
                            <dd class="mt-1 text-base text-gray-900">{{ $data['telephone_parent'] }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email (Élève)</dt>
                            <dd class="mt-1 text-base text-gray-900">{{ $data['email_eleve'] }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email (Parent)</dt>
                            <dd class="mt-1 text-base text-gray-900">{{ $data['email_parent'] }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Région</dt>
                            <dd class="mt-1 text-base text-gray-900">{{ $data['region'] }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Commune</dt>
                            <dd class="mt-1 text-base text-gray-900">{{ $data['commune'] }}</dd>
                        </div>
                    </dl>

                    <!-- Documents uploadés -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h4 class="text-sm font-medium text-gray-700 mb-4">Documents téléchargés</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex items-center gap-3 p-3 bg-green-50 rounded-lg border border-green-200">
                                <i data-lucide="file-check" class="w-5 h-5 text-green-600"></i>
                                <span class="text-sm text-green-800 font-medium">Pièce d'identité</span>
                            </div>
                            @if(isset($tempData['photo_profil_temp']))
                            <div class="flex items-center gap-3 p-3 bg-green-50 rounded-lg border border-green-200">
                                <i data-lucide="file-check" class="w-5 h-5 text-green-600"></i>
                                <span class="text-sm text-green-800 font-medium">Photo de profil</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 2: Informations scolaires -->
            <div class="border-b border-gray-200">
                <div class="bg-gradient-to-r from-[#0E7490]/10 to-[#0c5f7a]/10 px-8 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-[#0E7490] text-white flex items-center justify-center shadow-md">
                            <i data-lucide="graduation-cap" class="w-5 h-5"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">Informations scolaires</h3>
                    </div>
                </div>
                <div class="px-8 py-6">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Matricule BAC</dt>
                            <dd class="mt-1 text-base text-gray-900 font-mono font-semibold">{{ $data['matricule_bac'] }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Série BAC</dt>
                            <dd class="mt-1 text-base text-gray-900 font-semibold">
                                @php
                                    $series = [
                                        'C' => 'C - Scientifique (Maths, Physique)',
                                        'E' => 'E - Technique (Maths, Technologie)',
                                        'D' => 'D - Scientifique (SVT, Maths)',
                                        'A1' => 'A1 - Littéraire (Maths + Langues)',
                                        'A2' => 'A2 - Littéraire (Langues, Histoire, Géo)',
                                        'F1' => 'F1 - Mécanique Générale',
                                        'F2' => 'F2 - Électronique',
                                        'F3' => 'F3 - Électrotechnique',
                                        'F4' => 'F4 - Génie Civil',
                                        'F5' => 'F5 - Physique-Chimie',
                                        'F6' => 'F6 - Constructions Mécaniques',
                                        'F7' => 'F7 - Bois et Matériaux',
                                        'F8' => 'F8 - Arts Appliqués',
                                        'G1' => 'G1 - Secrétariat',
                                        'G2' => 'G2 - Comptabilité',
                                        'G3' => 'G3 - Commerce',
                                        'BT' => 'BT - Brevet de Technicien',
                                        'BP' => 'BP - Brevet Professionnel'
                                    ];
                                @endphp
                                {{ $series[$data['serie_bac']] ?? $data['serie_bac'] }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Note BAC</dt>
                            <dd class="mt-1">
                                <span class="text-2xl font-bold text-[#0E7490]">{{ $data['note_bac'] }}</span>
                                <span class="text-sm text-gray-500">/400</span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Mention</dt>
                            <dd class="mt-1">
                                @if($mention)
                                    @php
                                        $mentionColors = [
                                            'passable' => 'bg-blue-100 text-blue-800',
                                            'assez_bien' => 'bg-green-100 text-green-800',
                                            'bien' => 'bg-orange-100 text-orange-800',
                                            'tres_bien' => 'bg-purple-100 text-purple-800'
                                        ];
                                        $mentionLabels = [
                                            'passable' => 'Passable',
                                            'assez_bien' => 'Assez Bien',
                                            'bien' => 'Bien',
                                            'tres_bien' => 'Très Bien'
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold {{ $mentionColors[$mention] ?? 'bg-gray-100 text-gray-800' }}">
                                        🎓 {{ $mentionLabels[$mention] ?? $mention }}
                                    </span>
                                @else
                                    <span class="text-gray-400 text-sm">Aucune mention</span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Année d'obtention</dt>
                            <dd class="mt-1 text-base text-gray-900">{{ $data['annee_bac'] }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Établissement</dt>
                            <dd class="mt-1 text-base text-gray-900">{{ $data['etablissement_nom'] }}</dd>
                        </div>
                        <div class="md:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Type d'établissement</dt>
                            <dd class="mt-1 text-base text-gray-900">{{ ucfirst(str_replace('_', ' ', $data['etablissement_type'])) }}</dd>
                        </div>
                    </dl>

                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="flex items-center gap-3 p-3 bg-green-50 rounded-lg border border-green-200 w-fit">
                            <i data-lucide="file-check" class="w-5 h-5 text-green-600"></i>
                            <span class="text-sm text-green-800 font-medium">Collante BAC téléchargée</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 3: Situation sociale -->
            <div class="border-b border-gray-200">
                <div class="bg-gradient-to-r from-[#0E7490]/10 to-[#0c5f7a]/10 px-8 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-[#0E7490] text-white flex items-center justify-center shadow-md">
                            <i data-lucide="home" class="w-5 h-5"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">Situation sociale</h3>
                    </div>
                </div>
                <div class="px-8 py-6">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Pensionnaire</dt>
                            <dd class="mt-1 text-base text-gray-900">{{ $data['pensionnaire_internat'] ? 'Oui' : 'Non' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Bourse au lycée</dt>
                            <dd class="mt-1 text-base text-gray-900">{{ $data['bourse_scolaire_lycee'] ? 'Oui' : 'Non' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Profession du père</dt>
                            <dd class="mt-1 text-base text-gray-900">
                                @php
                                    $professions = [
                                        'cadres_professions_intellectuelles' => 'Cadres, professions intellectuelles sup.',
                                        'administration_services' => 'Administration / services',
                                        'employes_bureau' => 'Employés de bureau',
                                        'ouvriers_qualifies_artisans' => 'Ouvriers qualifiés / artisans',
                                        'travailleurs_agricoles_pecheurs' => 'Travailleurs agricoles, pêcheurs',
                                        'travailleurs_non_qualifies' => 'Travailleurs non qualifiés',
                                        'sans_emploi_informel' => 'Sans emploi ou informel non déclaré',
                                        'non_applicable' => 'Non applicable'
                                    ];
                                @endphp
                                {{ $professions[$data['profession_pere']] ?? $data['profession_pere'] }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Profession de la mère</dt>
                            <dd class="mt-1 text-base text-gray-900">
                                {{ $professions[$data['profession_mere']] ?? $data['profession_mere'] }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Connexion Internet</dt>
                            <dd class="mt-1 text-base text-gray-900">{{ ucfirst(str_replace('_', ' ', $data['connexion_internet'])) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Équipement numérique</dt>
                            <dd class="mt-1 space-y-1">
                                <div class="flex items-center gap-2 text-sm">
                                    <i data-lucide="{{ $data['possede_ordinateur'] ? 'check-circle' : 'x-circle' }}" 
                                       class="w-4 h-4 {{ $data['possede_ordinateur'] ? 'text-green-600' : 'text-red-600' }}"></i>
                                    <span class="text-gray-700">Ordinateur</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm">
                                    <i data-lucide="{{ $data['acces_smartphone'] ? 'check-circle' : 'x-circle' }}" 
                                       class="w-4 h-4 {{ $data['acces_smartphone'] ? 'text-green-600' : 'text-red-600' }}"></i>
                                    <span class="text-gray-700">Smartphone</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm">
                                    <i data-lucide="{{ $data['acces_ia'] ? 'check-circle' : 'x-circle' }}" 
                                       class="w-4 h-4 {{ $data['acces_ia'] ? 'text-green-600' : 'text-red-600' }}"></i>
                                    <span class="text-gray-700">Accès IA</span>
                                </div>
                            </dd>
                        </div>
                        @if(!empty($data['situations_particulieres']))
                        <div class="md:col-span-2">
                            <dt class="text-sm font-medium text-gray-500 mb-2">Situations particulières</dt>
                            <dd class="flex flex-wrap gap-2">
                                @foreach($data['situations_particulieres'] as $situation)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-orange-100 text-orange-800 font-medium">
                                        {{ ucfirst($situation) }}
                                    </span>
                                @endforeach
                            </dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>

            <!-- Section 4: Motivation -->
            <div>
                <div class="bg-gradient-to-r from-[#0E7490]/10 to-[#0c5f7a]/10 px-8 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-[#0E7490] text-white flex items-center justify-center shadow-md">
                            <i data-lucide="message-square" class="w-5 h-5"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">Lettre de motivation</h3>
                    </div>
                </div>
                <div class="px-8 py-6">
                    <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                        <p class="text-gray-800 leading-relaxed whitespace-pre-line">{{ $data['motivation'] }}</p>
                        <div class="mt-4 pt-4 border-t border-gray-300 text-sm text-gray-500">
                            {{ strlen($data['motivation']) }} caractères
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex flex-col sm:flex-row gap-4 justify-between items-center bg-white p-6 rounded-xl shadow-lg border border-gray-100">
            <a href="{{ route('auth.complete-profile') }}" 
               class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-white border-2 border-gray-300 rounded-xl text-gray-700 font-semibold hover:bg-gray-50 transition-all duration-200">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
                Modifier les informations
            </a>

            <form action="{{ route('auth.complete-profile.store') }}" method="POST" class="w-full sm:w-auto">
                @csrf
                <button type="submit"
                        style="background: linear-gradient(to right, #0E7490, #0c5f7a);"
                        onmouseover="this.style.background='linear-gradient(to right, #0c5f7a, #0a4f63)'"
                        onmouseout="this.style.background='linear-gradient(to right, #0E7490, #0c5f7a)'"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-4 text-white font-bold rounded-xl transition-all duration-300 shadow-lg hover:shadow-2xl transform hover:scale-[1.02]">
                    <i data-lucide="check-circle" class="w-6 h-6"></i>
                    <span class="text-lg">Confirmer et finaliser</span>
                    <i data-lucide="arrow-right" class="w-5 h-5"></i>
                </button>
            </form>
        </div>

        <!-- Information supplémentaire -->
        <div class="mt-6 bg-gradient-to-br from-[#0E7490]/5 to-blue-50 rounded-xl p-6 border border-[#0E7490]/20">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0">
                    <i data-lucide="info" class="w-6 h-6 text-[#0E7490]"></i>
                </div>
                <div class="text-sm text-gray-700">
                    <p class="font-semibold text-[#0E7490] mb-2">Que se passe-t-il après ?</p>
                    <ul class="space-y-1 list-disc list-inside">
                        <li>Vos documents seront analysés par notre IA pour vérifier leur authenticité</li>
                        <li>Votre score PEUB sera calculé automatiquement</li>
                        <li>Vous recevrez une confirmation par email</li>
                        <li>Vous pourrez accéder à votre tableau de bord immédiatement</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>
@endpush
@endsection

