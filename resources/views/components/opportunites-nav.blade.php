{{-- Navigation par onglets des pages d opportunites.
     Couleurs par role uniquement : ce composant est rendu aussi bien sur les vues migrees
     (data-ds) que sur celles qui ne le sont pas encore. Les roles de theme.css etant
     declares sur :root, il bascule en mode sombre dans les deux cas. --}}
@php
    $onglets = [
        [
            'route' => 'bachelier.opportunites',
            'libelle' => 'Toutes les opportunités',
            'actif' => request()->routeIs('bachelier.opportunites') && !request()->has('filter'),
            'icone' => ['M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16', 'M4 7h16a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2z'],
        ],
        [
            'route' => 'bachelier.favoris',
            'libelle' => 'Mes favoris',
            'actif' => request()->routeIs('bachelier.favoris'),
            'icone' => ['M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7z'],
        ],
        [
            'route' => 'bachelier.candidatures',
            'libelle' => 'Mes candidatures',
            'actif' => request()->routeIs('bachelier.candidatures'),
            'icone' => ['M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7z', 'M14 2v4a2 2 0 0 0 2 2h4', 'M16 13H8', 'M16 17H8'],
        ],
    ];
@endphp

<nav aria-label="Navigation des opportunités"
     style="display:flex; gap:var(--space-1); overflow-x:auto; padding-bottom:var(--space-0-5); scrollbar-width:none">
    @foreach ($onglets as $onglet)
        <a href="{{ route($onglet['route']) }}"
           @if ($onglet['actif']) aria-current="page" @endif
           style="display:inline-flex; align-items:center; gap:var(--space-0-5); min-height:44px; padding:0 var(--space-2); border-radius:var(--radius-pill); white-space:nowrap; font-size:var(--text-caption); font-weight:var(--font-medium); text-decoration:none; {{ $onglet['actif'] ? 'background:var(--accent); color:var(--text-on-accent);' : 'background:var(--surface-secondary); color:var(--text-primary);' }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false" style="flex-shrink:0">
                @foreach ($onglet['icone'] as $d)<path d="{{ $d }}"/>@endforeach
            </svg>
            {{ $onglet['libelle'] }}
        </a>
    @endforeach
</nav>
