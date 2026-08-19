<!DOCTYPE html>
<html lang="fr" data-ds>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Design system PEUB</title>
    @vite(['resources/css/app.css'])
</head>
<body>

<header class="ds-surface-brand">
    <div class="ds-container" style="padding-block: var(--space-8)">
        <p class="ds-overline" style="color: inherit; opacity: .7">Reference interne</p>
        <h1 class="ds-display" style="margin-top: var(--space-1)">Design system PEUB</h1>
        <p style="margin-top: var(--space-2); max-width: 62ch; opacity: .85">
            Trois couches : primitives, roles clair, roles sombre.
            Un composant n ecrit jamais une couleur, seulement un role.
            Le mode sombre est gratuit.
        </p>
        <button id="theme-toggle" class="ds-btn ds-btn-highlight ds-btn-md" style="margin-top: var(--space-3)">
            <span id="theme-label">Passer en mode sombre</span>
        </button>
    </div>
</header>

<main class="ds-container ds-stack-xl" style="padding-block: var(--space-8)">

    {{-- Roles de surface --}}
    <section class="ds-stack">
        <h2>01 — Surfaces</h2>
        <p class="ds-text-secondary">Basculez le theme : ces blocs changent sans une ligne de code en plus.</p>
        <div style="display:grid; gap: var(--space-2); grid-template-columns: repeat(auto-fit, minmax(200px, 1fr))">
            @foreach ([
                'surface' => 'Fond de page',
                'surface-raised' => 'Carte, bloc eleve',
                'surface-secondary' => 'Bloc secondaire',
                'surface-hover' => 'Survol',
            ] as $role => $usage)
                <div class="ds-card-flat" style="padding: var(--space-2); background: var(--{{ $role }})">
                    <p style="font-weight: var(--font-semibold)">{{ $usage }}</p>
                    <code class="ds-text-secondary" style="font-size: var(--text-caption)">--{{ $role }}</code>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Typographie --}}
    <section class="ds-stack">
        <h2>02 — Typographie</h2>
        <div class="ds-card" style="padding: var(--space-4)">
            <div class="ds-stack-sm">
                <p class="ds-display">Excellence</p>
                <h1>Titre de page</h1>
                <h2>Titre de section</h2>
                <h3>Sous-titre</h3>
                <p class="ds-lead">
                    Chapeau. Le programme connecte les bacheliers ivoiriens aux bourses,
                    stages et formations de ses partenaires.
                </p>
                <p style="max-width: 62ch">
                    Corps de texte en Inter. Deux niveaux de texte seulement : primaire fort
                    pour le contenu, secondaire pour tout le reste.
                </p>
                <p class="ds-text-secondary">Texte secondaire, legendes, metadonnees.</p>
                <p class="ds-overline">Sur-titre, libelle de champ</p>
            </div>
        </div>
    </section>

    {{-- Chiffres --}}
    <section class="ds-stack">
        <h2>03 — Chiffres</h2>
        <div style="display:grid; gap: var(--space-2); grid-template-columns: repeat(auto-fit, minmax(200px, 1fr))">
            @foreach ([['2 047', 'Bacheliers inscrits'], ['312', 'Bourses attribuees'], ['48', 'Partenaires']] as [$val, $lib])
                <div class="ds-card" style="padding: var(--space-3)">
                    <p class="ds-stat ds-text-accent">{{ $val }}</p>
                    <p class="ds-overline" style="margin-top: var(--space-1)">{{ $lib }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Boutons --}}
    <section class="ds-stack">
        <h2>04 — Boutons</h2>
        <div class="ds-card" style="padding: var(--space-4)">
            <div style="display:flex; flex-wrap:wrap; gap: var(--space-1-5); align-items:center">
                <button class="ds-btn ds-btn-primary ds-btn-lg">Deposer ma candidature</button>
                <button class="ds-btn ds-btn-secondary ds-btn-md">Enregistrer</button>
                <button class="ds-btn ds-btn-highlight ds-btn-md">Voir les bourses</button>
                <button class="ds-btn ds-btn-ghost ds-btn-md">Annuler</button>
                <button class="ds-btn ds-btn-danger ds-btn-md">Supprimer</button>
                <button class="ds-btn ds-btn-primary ds-btn-sm">Petit</button>
                <button class="ds-btn ds-btn-primary ds-btn-md" disabled>Desactive</button>
            </div>
        </div>
    </section>

    {{-- Cartes --}}
    <section class="ds-stack">
        <h2>05 — Cartes</h2>
        <div style="display:grid; gap: var(--space-2); grid-template-columns: repeat(auto-fit, minmax(260px, 1fr))">
            <article class="ds-card-interactive" style="padding: var(--space-3)">
                <span class="ds-badge ds-badge-accent">Bourse</span>
                <h3 style="margin-top: var(--space-1-5)">Genie logiciel</h3>
                <p class="ds-text-secondary" style="margin-top: var(--space-0-5); font-size: var(--text-caption)">
                    Financement complet, 3 ans, INP-HB.
                </p>
                <p class="ds-overline" style="margin-top: var(--space-2)">Cloture le 30 septembre</p>
            </article>

            <article class="ds-card-interactive" style="padding: var(--space-3)">
                <span class="ds-badge ds-badge-warning">Bientot clos</span>
                <h3 style="margin-top: var(--space-1-5)">Data analyst</h3>
                <p class="ds-text-secondary" style="margin-top: var(--space-0-5); font-size: var(--text-caption)">
                    Stage 6 mois, Abidjan Plateau.
                </p>
                <p class="ds-overline" style="margin-top: var(--space-2)">12 places</p>
            </article>

            <div class="ds-panel" style="padding: var(--space-3)">
                <p class="ds-overline">Note</p>
                <p style="margin-top: var(--space-1); font-size: var(--text-caption)">
                    Panneau secondaire, information contextuelle sans hierarchie forte.
                </p>
            </div>
        </div>
    </section>

    {{-- Champs --}}
    <section class="ds-stack">
        <h2>06 — Champs</h2>
        <div class="ds-card" style="padding: var(--space-4)">
            <div style="display:grid; gap: var(--space-3); grid-template-columns: repeat(auto-fit, minmax(260px, 1fr))">
                <div>
                    <label class="ds-label" for="f-nom">Nom</label>
                    <input id="f-nom" class="ds-field" placeholder="Kouassi">
                    <p class="ds-hint">Tel qu il figure sur la piece d identite.</p>
                </div>
                <div>
                    <label class="ds-label" for="f-note">Note BAC</label>
                    <input id="f-note" class="ds-field ds-field-error" value="512">
                    <p class="ds-error-text">La note doit etre sur 400 points maximum.</p>
                </div>
                <div style="grid-column: 1 / -1">
                    <label class="ds-label" for="f-mot">Lettre de motivation</label>
                    <textarea id="f-mot" class="ds-field ds-textarea" placeholder="Expliquez votre projet..."></textarea>
                </div>
            </div>
        </div>
    </section>

    {{-- Etats --}}
    <section class="ds-stack">
        <h2>07 — Etats</h2>
        <div class="ds-card ds-stack" style="padding: var(--space-4)">
            <div style="display:flex; flex-wrap:wrap; gap: var(--space-1)">
                <span class="ds-badge ds-badge-accent">Bourse</span>
                <span class="ds-badge ds-badge-success"><i class="ds-dot ds-dot-success"></i>Validee</span>
                <span class="ds-badge ds-badge-warning"><i class="ds-dot ds-dot-warning"></i>En attente</span>
                <span class="ds-badge ds-badge-error"><i class="ds-dot ds-dot-error"></i>Rejetee</span>
                <span class="ds-badge ds-badge-info">En examen</span>
                <span class="ds-badge ds-badge-neutral">Brouillon</span>
                <span class="ds-badge ds-badge-solid">A la une</span>
            </div>

            <div class="ds-stack-sm">
                <div class="ds-alert ds-alert-success">Votre candidature a ete transmise au jury.</div>
                <div class="ds-alert ds-alert-warning">Il vous reste 3 jours pour completer votre dossier.</div>
                <div class="ds-alert ds-alert-error">Le scan de la collante BAC est illisible.</div>
                <div class="ds-alert ds-alert-info">Les resultats seront publies le 15 octobre.</div>
            </div>

            <div style="max-width: 420px">
                <div style="display:flex; justify-content:space-between; margin-bottom: var(--space-1)">
                    <span class="ds-overline">Dossier</span>
                    <span class="ds-overline ds-text-accent">3 / 4</span>
                </div>
                <div class="ds-progress"><div class="ds-progress-bar" style="width:75%"></div></div>
            </div>

            <div style="max-width: 420px" class="ds-stack-sm">
                <div class="ds-skeleton" style="height:16px; width:70%"></div>
                <div class="ds-skeleton" style="height:16px; width:100%"></div>
                <div class="ds-skeleton" style="height:16px; width:45%"></div>
            </div>
        </div>
    </section>

</main>

<footer style="background: var(--surface-secondary); padding-block: var(--space-4)">
    <div class="ds-container">
        <p class="ds-overline">PEUB — Reference de conception</p>
    </div>
</footer>

<script>
    const root   = document.documentElement;
    const toggle = document.getElementById('theme-toggle');
    const label  = document.getElementById('theme-label');

    function apply(mode) {
        if (mode === 'dark') {
            root.setAttribute('data-theme', 'dark');
            label.textContent = 'Passer en mode clair';
        } else {
            root.removeAttribute('data-theme');
            label.textContent = 'Passer en mode sombre';
        }
    }

    toggle.addEventListener('click', function () {
        apply(root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark');
    });
</script>

</body>
</html>
