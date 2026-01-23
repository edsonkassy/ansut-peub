@extends('layouts.admin')

@section('title', 'Barème de Scoring - PEUB Admin')

@section('page-title', 'Barème de Scoring PEUB')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- En-tête -->
    <div class="bg-white border border-gray-300 rounded-lg p-6 mb-6">
        <div class="flex items-center mb-4">
            <i data-lucide="award" class="w-8 h-8 text-primary-600 mr-3"></i>
            <h1 class="text-3xl font-bold text-gray-900">Barème de Scoring PEUB</h1>
        </div>
        <p class="text-gray-600">
            Le score PEUB est calculé sur la base de 100 points répartis selon 4 critères principaux. 
            Ce barème est utilisé pour évaluer et classer les bacheliers.
        </p>
    </div>

    <!-- Vue d'ensemble -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-lg p-6">
            <div class="flex items-center mb-3">
                <div class="w-10 h-10 rounded-lg bg-blue-500 flex items-center justify-center mr-3">
                    <i data-lucide="book" class="w-5 h-5 text-white"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900">Total des Points</h3>
            </div>
            <p class="text-4xl font-bold text-blue-600">100</p>
            <p class="text-sm text-gray-600 mt-2">Points répartis entre 4 critères</p>
        </div>

        <div class="bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-lg p-6">
            <div class="flex items-center mb-3">
                <div class="w-10 h-10 rounded-lg bg-green-500 flex items-center justify-center mr-3">
                    <i data-lucide="check-circle" class="w-5 h-5 text-white"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900">Critères</h3>
            </div>
            <p class="text-4xl font-bold text-green-600">4</p>
            <p class="text-sm text-gray-600 mt-2">Critères d'évaluation</p>
        </div>
    </div>

    <!-- Détail des critères -->
    <div class="space-y-6">
        <!-- 1. Excellence Académique -->
        <div class="bg-white border border-gray-300 rounded-lg overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center mr-3">
                        <span class="text-xl font-bold text-white">1</span>
                    </div>
                    <h3 class="text-xl font-semibold text-white">Excellence Académique</h3>
                </div>
            </div>
            <div class="p-6">
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-lg font-semibold text-gray-900">Points Totaux</span>
                        <span class="text-3xl font-bold text-blue-600">50</span>
                    </div>
                    <div class="bg-gray-200 h-3 rounded-full overflow-hidden">
                        <div class="bg-blue-500 h-3 rounded-full" style="width: 50%"></div>
                    </div>
                </div>
                
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-3">Critères de notation:</h4>
                    <ul class="space-y-2">
                        <li class="flex items-start">
                            <span class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-blue-500 text-white text-sm font-bold mr-3 flex-shrink-0">✓</span>
                            <span class="text-gray-700"><strong>Très Bien:</strong> 50 points</span>
                        </li>
                        <li class="flex items-start">
                            <span class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-green-500 text-white text-sm font-bold mr-3 flex-shrink-0">✓</span>
                            <span class="text-gray-700"><strong>Bien:</strong> 40 points</span>
                        </li>
                        <li class="flex items-start">
                            <span class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-yellow-500 text-white text-sm font-bold mr-3 flex-shrink-0">✓</span>
                            <span class="text-gray-700"><strong>Assez Bien:</strong> 30 points</span>
                        </li>
                        <li class="flex items-start">
                            <span class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-orange-500 text-white text-sm font-bold mr-3 flex-shrink-0">✓</span>
                            <span class="text-gray-700"><strong>Passable:</strong> 20 points</span>
                        </li>
                    </ul>
                </div>
                
                <p class="text-sm text-gray-600 mt-4">
                    <strong>Basé sur:</strong> La mention obtenue au baccalauréat (note sur 400)
                </p>
            </div>
        </div>

        <!-- 2. Situation Handicap -->
        <div class="bg-white border border-gray-300 rounded-lg overflow-hidden">
            <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 px-6 py-4">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center mr-3">
                        <span class="text-xl font-bold text-white">2</span>
                    </div>
                    <h3 class="text-xl font-semibold text-white">Situation Handicap</h3>
                </div>
            </div>
            <div class="p-6">
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-lg font-semibold text-gray-900">Points Totaux</span>
                        <span class="text-3xl font-bold text-yellow-600">20</span>
                    </div>
                    <div class="bg-gray-200 h-3 rounded-full overflow-hidden">
                        <div class="bg-yellow-500 h-3 rounded-full" style="width: 20%"></div>
                    </div>
                </div>
                
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-3">Critères de notation:</h4>
                    <ul class="space-y-2">
                        <li class="flex items-start">
                            <span class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-yellow-500 text-white text-sm font-bold mr-3 flex-shrink-0">✓</span>
                            <span class="text-gray-700"><strong>Avec handicap reconnu:</strong> 20 points</span>
                        </li>
                        <li class="flex items-start">
                            <span class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-gray-400 text-white text-sm font-bold mr-3 flex-shrink-0">✗</span>
                            <span class="text-gray-700"><strong>Sans handicap:</strong> 10 points</span>
                        </li>
                    </ul>
                </div>
                
                <p class="text-sm text-gray-600 mt-4">
                    <strong>Basé sur:</strong> Situation de handicap déclarée et documentée
                </p>
            </div>
        </div>

        <!-- 3. Situation Matrimoniale (Orphelinat) -->
        <div class="bg-white border border-gray-300 rounded-lg overflow-hidden">
            <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center mr-3">
                        <span class="text-xl font-bold text-white">3</span>
                    </div>
                    <h3 class="text-xl font-semibold text-white">Situation Matrimoniale (Orphelinat)</h3>
                </div>
            </div>
            <div class="p-6">
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-lg font-semibold text-gray-900">Points Totaux</span>
                        <span class="text-3xl font-bold text-green-600">20</span>
                    </div>
                    <div class="bg-gray-200 h-3 rounded-full overflow-hidden">
                        <div class="bg-green-500 h-3 rounded-full" style="width: 20%"></div>
                    </div>
                </div>
                
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-3">Critères de notation:</h4>
                    <ul class="space-y-2">
                        <li class="flex items-start">
                            <span class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-green-500 text-white text-sm font-bold mr-3 flex-shrink-0">✓</span>
                            <span class="text-gray-700"><strong>Orphelin de père et mère:</strong> 20 points</span>
                        </li>
                        <li class="flex items-start">
                            <span class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-blue-500 text-white text-sm font-bold mr-3 flex-shrink-0">✓</span>
                            <span class="text-gray-700"><strong>Orphelin de père ou mère:</strong> 15 points</span>
                        </li>
                        <li class="flex items-start">
                            <span class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-gray-400 text-white text-sm font-bold mr-3 flex-shrink-0">✗</span>
                            <span class="text-gray-700"><strong>Non orphelin:</strong> 0 points</span>
                        </li>
                    </ul>
                </div>
                
                <p class="text-sm text-gray-600 mt-4">
                    <strong>Basé sur:</strong> Situation familiale déclarée et documentée
                </p>
            </div>
        </div>

        <!-- 4. Genre -->
        <div class="bg-white border border-gray-300 rounded-lg overflow-hidden">
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 px-6 py-4">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center mr-3">
                        <span class="text-xl font-bold text-white">4</span>
                    </div>
                    <h3 class="text-xl font-semibold text-white">Genre</h3>
                </div>
            </div>
            <div class="p-6">
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-lg font-semibold text-gray-900">Points Totaux</span>
                        <span class="text-3xl font-bold text-purple-600">10</span>
                    </div>
                    <div class="bg-gray-200 h-3 rounded-full overflow-hidden">
                        <div class="bg-purple-500 h-3 rounded-full" style="width: 10%"></div>
                    </div>
                </div>
                
                <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-3">Critères de notation:</h4>
                    <ul class="space-y-2">
                        <li class="flex items-start">
                            <span class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-purple-500 text-white text-sm font-bold mr-3 flex-shrink-0">♀</span>
                            <span class="text-gray-700"><strong>Féminin:</strong> 10 points</span>
                        </li>
                        <li class="flex items-start">
                            <span class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-blue-500 text-white text-sm font-bold mr-3 flex-shrink-0">♂</span>
                            <span class="text-gray-700"><strong>Masculin:</strong> 5 points</span>
                        </li>
                    </ul>
                </div>
                
                <p class="text-sm text-gray-600 mt-4">
                    <strong>Basé sur:</strong> Genre déclaré pour favoriser l'égalité des genres
                </p>
            </div>
        </div>
    </div>

    <!-- Résumé du calcul -->
    <div class="bg-gradient-to-r from-primary-50 to-primary-100 border-2 border-primary-300 rounded-lg p-6 mt-8">
        <h3 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
            <i data-lucide="calculator" class="w-6 h-6 text-primary-600 mr-3"></i>
            Formule de Calcul
        </h3>
        
        <div class="bg-white rounded-lg p-4 mb-4">
            <p class="text-center text-lg font-mono text-gray-900">
                <strong>Score Final = Excellence Académique + Situation Handicap + Orphelinat + Genre</strong>
            </p>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-lg p-3 text-center">
                <p class="text-sm text-gray-600">Excellence</p>
                <p class="text-2xl font-bold text-blue-600">50</p>
            </div>
            <div class="bg-white rounded-lg p-3 text-center">
                <p class="text-sm text-gray-600">Handicap</p>
                <p class="text-2xl font-bold text-yellow-600">20</p>
            </div>
            <div class="bg-white rounded-lg p-3 text-center">
                <p class="text-sm text-gray-600">Orphelinat</p>
                <p class="text-2xl font-bold text-green-600">20</p>
            </div>
            <div class="bg-white rounded-lg p-3 text-center">
                <p class="text-sm text-gray-600">Genre</p>
                <p class="text-2xl font-bold text-purple-600">10</p>
            </div>
        </div>
        
        <div class="bg-primary-600 text-white rounded-lg p-4 mt-4 text-center">
            <p class="text-sm mb-1">Score Maximum</p>
            <p class="text-4xl font-bold">100 points</p>
        </div>
    </div>

    <!-- Notes importantes -->
    <div class="bg-blue-50 border-l-4 border-blue-400 rounded-lg p-6 mt-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
            <i data-lucide="info" class="w-5 h-5 text-blue-600 mr-2"></i>
            Notes Importantes
        </h3>
        <ul class="space-y-2 text-gray-700">
            <li class="flex items-start">
                <span class="text-blue-600 font-bold mr-3">•</span>
                <span>Le score PEUB est calculé automatiquement lors de la soumission du profil du bachelier.</span>
            </li>
            <li class="flex items-start">
                <span class="text-blue-600 font-bold mr-3">•</span>
                <span>Les bacheliers sont classés par ordre décroissant de score (du plus haut au plus bas).</span>
            </li>
            <li class="flex items-start">
                <span class="text-blue-600 font-bold mr-3">•</span>
                <span>Les 2 000 meilleurs bacheliers sont sélectionnés comme boursiers PEUB.</span>
            </li>
            <li class="flex items-start">
                <span class="text-blue-600 font-bold mr-3">•</span>
                <span>En cas d'égalité de score, la date d'inscription est utilisée comme critère de départage.</span>
            </li>
            <li class="flex items-start">
                <span class="text-blue-600 font-bold mr-3">•</span>
                <span>Tous les documents justificatifs doivent être fournis pour valider les critères spéciaux (handicap, orphelinat).</span>
            </li>
        </ul>
    </div>
</div>

@endsection
