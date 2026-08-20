{{-- Hero : scene de marque en degrade de role, sans image de fond.
     L image plein ecran precedente pesait 2,2 Mo et degradait le scroll sur Chrome Android. --}}
<section class="ds-surface-brand ds-bloom" style="padding-block: clamp(var(--space-8), 14vw, var(--space-12))">
    <div class="ds-container" style="text-align: center">

        <h1 style="font-size: clamp(var(--text-h1), 9vw, var(--text-display))">
            L'EXCELLENCE
            <span style="display: block; margin-top: var(--space-0-5); color: var(--accent-highlight); font-weight: var(--font-regular)">
                Commence Ici
            </span>
        </h1>

        <p style="margin-top: var(--space-3); margin-inline: auto; max-width: 24ch; font-size: var(--text-h3); font-weight: var(--font-semibold); opacity: .92">
            DEVIENS ACTEUR DU FUTUR NUMÉRIQUE IVOIRIEN
        </p>

        <div style="margin-top: var(--space-5); margin-inline: auto; max-width: 520px; display: grid; gap: var(--space-1-5); grid-template-columns: repeat(auto-fit, minmax(220px, 1fr))">
            <a href="{{ route('auth.register') }}" class="ds-btn ds-btn-primary ds-btn-lg">
                S'inscrire maintenant
            </a>
            <a href="{{ route('faq') }}" class="ds-btn ds-btn-secondary ds-btn-lg">
                C'est quoi le PEUB ?
            </a>
        </div>

    </div>
</section>
