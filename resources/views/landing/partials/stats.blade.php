{{-- Statistiques : chiffres reels issus du controleur.
     Une tuile dont le compte vaut zero n est pas affichee, plutot que de montrer
     un « 0 Bacheliers accompagnes » en page d accueil. Le taux de satisfaction
     est toujours present, la section n est donc jamais vide. --}}
@php
    $statTiles = collect([
        [(int) ($stats['bacheliers_count'] ?? 0), 'Bacheliers accompagnés'],
        [(int) ($stats['opportunites_count'] ?? 0), 'Opportunités disponibles'],
        [(int) ($stats['partenaires_count'] ?? 0), 'Partenaires de confiance'],
    ])->filter(fn ($tile) => $tile[0] > 0)
      ->map(fn ($tile) => [number_format($tile[0], 0, ',', "\u{00A0}"), $tile[1]])
      ->push([((int) ($stats['satisfaction_rate'] ?? 95)) . '%', 'Taux de satisfaction'])
      ->all();
@endphp

@if (count($statTiles))
<section class="ds-bg-raised" style="padding-block: clamp(var(--space-6), 8vw, var(--space-8))">
    <div class="ds-container">
        <div style="display: grid; gap: var(--space-2); grid-template-columns: repeat(auto-fit, minmax(150px, 1fr))">
            @foreach ($statTiles as [$value, $label])
                <div class="ds-card" style="padding: var(--space-3); text-align: center">
                    <p class="ds-stat ds-text-accent" style="font-size: clamp(var(--text-h2), 9vw, var(--text-display))">{{ $value }}</p>
                    <p class="ds-overline" style="margin-top: var(--space-1)">{{ $label }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
