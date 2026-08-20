{{-- Partenaires : logos institutionnels.
     Bloc editorial, volontairement non branche sur $featured_partners. --}}
@php
    $institutionalPartners = [
        ['image' => 'images/partenaires/logo_ansut.webp', 'name' => 'ANSUT'],
        ['image' => 'images/partenaires/logo_ci20.png', 'name' => 'CI20'],
        ['image' => 'images/partenaires/logo_mesrs.png', 'name' => 'MESRS'],
        ['image' => 'images/partenaires/logo_deco.jpg', 'name' => 'DECO'],
    ];
@endphp

<section id="partners" class="ds-bg-surface" style="padding-block: clamp(var(--space-6), 9vw, var(--space-10))">
    <div class="ds-container ds-stack-lg">

        <header style="text-align: center; max-width: 62ch; margin-inline: auto">
            <h2 style="font-size: clamp(var(--text-h2), 6.5vw, var(--text-display))">
                NOS PARTENAIRES DE
                <span style="display: block; color: var(--accent); font-weight: var(--font-regular)">
                    Confiance
                </span>
            </h2>
            <p class="ds-overline" style="margin-top: var(--space-1-5)">
                ENTREPRISES ET INSTITUTIONS QUI CROIENT EN VOTRE POTENTIEL
            </p>
        </header>

        <div style="display: grid; gap: var(--space-2); grid-template-columns: repeat(auto-fit, minmax(130px, 1fr))">
            @foreach ($institutionalPartners as $partner)
                <div class="ds-card-flat" style="padding: var(--space-2); min-height: 88px; display: grid; place-items: center">
                    <img src="{{ asset($partner['image']) }}"
                         alt="{{ $partner['name'] }}"
                         loading="lazy" decoding="async"
                         style="max-height: 40px; max-width: 100%; width: auto; object-fit: contain">
                </div>
            @endforeach
        </div>

    </div>
</section>
