@extends('layouts.guest')

@section('title', 'FAQ - Questions Fréquentes PEUB')

@section('content')
<section class="relative py-20 sm:py-24 bg-white">
    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl sm:text-5xl font-bold text-gray-900 mb-4">
            QUESTIONS <span class="font-script text-5xl text-orange-500 font-normal">Fréquentes</span>
        </h1>
        <p class="text-lg text-gray-600 mb-8 max-w-3xl mx-auto">
            Tout ce que vous devez savoir sur le Programme d'Excellence Universelle pour les Bacheliers (PEUB)
        </p>
        <div class="flex items-center justify-center space-x-3 text-sm text-gray-500">
            <span class="font-medium">Une initiative de</span>
            <img src="{{ asset('images/logo_ansut_original.png') }}" alt="ANSUT" class="h-10 w-auto">
        </div>
    </div>
</section>

<section class="py-16 bg-gradient-to-b from-white to-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="space-y-4">

            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <button class="faq-btn w-full px-6 py-5 text-left flex items-center justify-between hover:bg-gray-50">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-lg bg-cyan-50 flex items-center justify-center mr-4">
                            <i data-lucide="help-circle" class="w-5 h-5 text-cyan-600"></i>
                        </div>
                        <span class="text-lg font-semibold text-gray-900">Qu'est-ce que PEUB ?</span>
                    </div>
                    <i data-lucide="chevron-down" class="faq-chevron w-5 h-5 text-gray-400"></i>
                </button>
                <div class="faq-content hidden px-6 pb-6">
                    <div class="bg-gray-50 rounded-lg p-6 border border-gray-100">
                        <p class="text-gray-700 mb-4 leading-relaxed"><strong class="text-cyan-700">PEUB</strong> est une initiative de l'<strong class="text-cyan-700">ANSUT</strong> visant à sélectionner chaque année les <strong class="text-orange-600">2 000 meilleurs bacheliers</strong> selon des critères académiques et sociaux pour leur fournir un accompagnement numérique complet.</p>
                        <div class="border-l-4 border-orange-500 pl-4 bg-orange-50 p-3 rounded-r-lg">
                            <p class="text-gray-800 font-medium">🎯 <strong class="text-orange-600">Notre mission :</strong> Transformer l'excellence académique en opportunités concrètes.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <button class="faq-btn w-full px-6 py-5 text-left flex items-center justify-between hover:bg-gray-50">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-lg bg-orange-50 flex items-center justify-center mr-4">
                            <i data-lucide="users" class="w-5 h-5 text-orange-600"></i>
                        </div>
                        <span class="text-lg font-semibold text-gray-900">Qui peut candidater à PEUB ?</span>
                    </div>
                    <i data-lucide="chevron-down" class="faq-chevron w-5 h-5 text-gray-400"></i>
                </button>
                <div class="faq-content hidden px-6 pb-6">
                    <div class="bg-gray-50 rounded-lg p-6 border border-gray-100">
                        <ul class="space-y-3 text-gray-700">
                            <li class="flex items-start"><span class="text-green-600 mr-2 mt-1">✓</span><span>Avoir obtenu le baccalauréat en 2025</span></li>
                            <li class="flex items-start"><span class="text-green-600 mr-2 mt-1">✓</span><span>Être admis avec une mention</span></li>
                            <li class="flex items-start"><span class="text-green-600 mr-2 mt-1">✓</span><span>Être résident en Côte d'Ivoire</span></li>
                            <li class="flex items-start"><span class="text-green-600 mr-2 mt-1">✓</span><span>Posséder une pièce d'identité valide</span></li>
                            <li class="flex items-start"><span class="text-green-600 mr-2 mt-1">✓</span><span>Rédiger une lettre de motivation conforme</span></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <button class="faq-btn w-full px-6 py-5 text-left flex items-center justify-between hover:bg-gray-50">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-lg bg-cyan-50 flex items-center justify-center mr-4">
                            <i data-lucide="gift" class="w-5 h-5 text-cyan-600"></i>
                        </div>
                        <span class="text-lg font-semibold text-gray-900">Quels sont les avantages pour les boursiers PEUB ?</span>
                    </div>
                    <i data-lucide="chevron-down" class="faq-chevron w-5 h-5 text-gray-400"></i>
                </button>
                <div class="faq-content hidden px-6 pb-6">
                    <div class="bg-gray-50 rounded-lg p-6 border border-gray-100">
                        <ul class="space-y-3 text-gray-700">
                            <li class="flex items-start"><span class="text-cyan-600 mr-2 mt-1">💻</span><span>Ordinateur portable performant</span></li>
                            <li class="flex items-start"><span class="text-cyan-600 mr-2 mt-1">📶</span><span>Connexion internet gratuite : Data 3G/4G mensuelle</span></li>
                            <li class="flex items-start"><span class="text-cyan-600 mr-2 mt-1">🤖</span><span>Abonnement IA Premium : ChatGPT Plus ou Claude</span></li>
                            <li class="flex items-start"><span class="text-orange-600 mr-2 mt-1">📚</span><span>Accès e-learning et bibliothèque virtuelle</span></li>
                            <li class="flex items-start"><span class="text-orange-600 mr-2 mt-1">🏆</span><span>Offres de stages, bourses d'études, projets universitaires</span></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <button class="faq-btn w-full px-6 py-5 text-left flex items-center justify-between hover:bg-gray-50">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-lg bg-orange-50 flex items-center justify-center mr-4">
                            <i data-lucide="brain" class="w-5 h-5 text-orange-600"></i>
                        </div>
                        <span class="text-lg font-semibold text-gray-900">Comment fonctionne le processus de sélection ?</span>
                    </div>
                    <i data-lucide="chevron-down" class="faq-chevron w-5 h-5 text-gray-400"></i>
                </button>
                <div class="faq-content hidden px-6 pb-6">
                    <div class="bg-gray-50 rounded-lg p-6 border border-gray-100">
                        <div class="space-y-4">
                            <div class="flex items-start"><div class="w-8 h-8 rounded-full bg-cyan-600 flex items-center justify-center mr-4 flex-shrink-0"><span class="text-sm font-bold text-white">1</span></div><div><h4 class="font-semibold text-gray-900">Candidature en ligne</h4><p class="text-gray-600 text-sm">Formulaire avec scoring automatique</p></div></div>
                            <div class="flex items-start"><div class="w-8 h-8 rounded-full bg-orange-500 flex items-center justify-center mr-4 flex-shrink-0"><span class="text-sm font-bold text-white">2</span></div><div><h4 class="font-semibold text-gray-900">Traitement automatisé</h4><p class="text-gray-600 text-sm">Scoring par série, région, profil socio-économique</p></div></div>
                            <div class="flex items-start"><div class="w-8 h-8 rounded-full bg-cyan-600 flex items-center justify-center mr-4 flex-shrink-0"><span class="text-sm font-bold text-white">3</span></div><div><h4 class="font-semibold text-gray-900">Validation finale</h4><p class="text-gray-600 text-sm">Validation par un comité indépendant</p></div></div>
                            <div class="flex items-start"><div class="w-8 h-8 rounded-full bg-orange-500 flex items-center justify-center mr-4 flex-shrink-0"><span class="text-sm font-bold text-white">4</span></div><div><h4 class="font-semibold text-gray-900">Annonce des résultats</h4><p class="text-gray-600 text-sm">Annonce publique des 2 000 boursiers</p></div></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <button class="faq-btn w-full px-6 py-5 text-left flex items-center justify-between hover:bg-gray-50">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-lg bg-cyan-50 flex items-center justify-center mr-4">
                            <i data-lucide="building" class="w-5 h-5 text-cyan-600"></i>
                        </div>
                        <span class="text-lg font-semibold text-gray-900">Quel est le rôle de l'ANSUT dans PEUB ?</span>
                    </div>
                    <i data-lucide="chevron-down" class="faq-chevron w-5 h-5 text-gray-400"></i>
                </button>
                <div class="faq-content hidden px-6 pb-6">
                    <div class="bg-gray-50 rounded-lg p-6 border border-gray-100">
                        <p class="text-gray-700 leading-relaxed">L'<strong class="text-cyan-700">ANSUT</strong> garantit la transparence du processus, établit les partenariats stratégiques, développe la plateforme technologique et accompagne les boursiers tout au long de leur parcours.</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <button class="faq-btn w-full px-6 py-5 text-left flex items-center justify-between hover:bg-gray-50">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-lg bg-orange-50 flex items-center justify-center mr-4">
                            <i data-lucide="calendar" class="w-5 h-5 text-orange-600"></i>
                        </div>
                        <span class="text-lg font-semibold text-gray-900">Quel est le calendrier de candidature ?</span>
                    </div>
                    <i data-lucide="chevron-down" class="faq-chevron w-5 h-5 text-gray-400"></i>
                </button>
                <div class="faq-content hidden px-6 pb-6">
                    <div class="bg-gray-50 rounded-lg p-6 border border-gray-100">
                        <ul class="space-y-3 text-gray-700">
                            <li class="flex items-start"><span class="font-semibold text-cyan-600 mr-2 flex-shrink-0">3-13 Juin :</span><span>Épreuves orales du BAC</span></li>
                            <li class="flex items-start"><span class="font-semibold text-cyan-600 mr-2 flex-shrink-0">16-20 Juin :</span><span>Épreuves écrites du BAC</span></li>
                            <li class="flex items-start"><span class="font-semibold text-orange-600 mr-2 flex-shrink-0">7 Juillet :</span><span>Proclamation des résultats BAC</span></li>
                            <li class="flex items-start"><span class="font-semibold text-orange-600 mr-2 flex-shrink-0">15 Juil - 15 Août :</span><span>Candidatures PEUB ouvertes</span></li>
                            <li class="flex items-start"><span class="font-semibold text-cyan-600 mr-2 flex-shrink-0">Septembre :</span><span>Annonce des 2 000 boursiers</span></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <button class="faq-btn w-full px-6 py-5 text-left flex items-center justify-between hover:bg-gray-50">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-lg bg-cyan-50 flex items-center justify-center mr-4">
                            <i data-lucide="message-circle" class="w-5 h-5 text-cyan-600"></i>
                        </div>
                        <span class="text-lg font-semibold text-gray-900">Comment contacter l'équipe PEUB ?</span>
                    </div>
                    <i data-lucide="chevron-down" class="faq-chevron w-5 h-5 text-gray-400"></i>
                </button>
                <div class="faq-content hidden px-6 pb-6">
                    <div class="bg-gray-50 rounded-lg p-6 border border-gray-100">
                        <ul class="space-y-3 text-gray-700">
                            <li class="flex items-start"><span class="mr-2">📞</span><span>+225 07 16 00 12 91</span></li>
                            <li class="flex items-start"><span class="mr-2">✉️</span><span>support@ansut.ci</span></li>
                            <li class="flex items-start"><span class="mr-2">📍</span><span>Abidjan, Côte d'Ivoire</span></li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<div style="background:linear-gradient(to right,#0891b2,#0e7490,#ea580c);padding:4rem 1rem;text-align:center;color:white;">
    <h2 style="font-size:2rem;font-weight:bold;margin-bottom:1rem;">Prêt à rejoindre l'excellence ?</h2>
    <p style="font-size:1.1rem;margin-bottom:2rem;opacity:0.9;">Ne manquez pas cette opportunité unique.</p>
    <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
        <a href="{{ route('auth.register') }}" style="background:white;color:#0e7490;padding:1rem 2rem;border-radius:0.5rem;font-weight:600;text-decoration:none;">S'inscrire maintenant</a>
        <a href="{{ route('landing') }}" style="border:2px solid white;color:white;padding:1rem 2rem;border-radius:0.5rem;font-weight:600;text-decoration:none;">En savoir plus</a>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof lucide !== 'undefined') lucide.createIcons();
    document.querySelectorAll('.faq-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var content = btn.nextElementSibling;
            var chevron = btn.querySelector('.faq-chevron');
            var isOpen = !content.classList.contains('hidden');
            document.querySelectorAll('.faq-content').forEach(function(el) { el.classList.add('hidden'); });
            document.querySelectorAll('.faq-chevron').forEach(function(el) { el.style.transform = ''; });
            if (!isOpen) {
                content.classList.remove('hidden');
                chevron.style.transform = 'rotate(180deg)';
            }
        });
    });
});
</script>
@endpush
