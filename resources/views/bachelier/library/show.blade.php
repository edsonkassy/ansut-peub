@extends('layouts.bachelier')

{{-- Active le design system PEUB sur cette vue uniquement. --}}
@section('html-attrs', 'data-ds')

@section('title', $resource->title . ' - Bibliothèque PEUB')

@php
    $typesLibelles = [
        'pdf' => 'PDF',
        'video' => 'Vidéo',
        'audio' => 'Audio',
        'document' => 'Document',
        'presentation' => 'Présentation',
    ];

    $typesIcones = [
        'pdf' => ['M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7z', 'M14 2v4a2 2 0 0 0 2 2h4', 'M16 13H8', 'M16 17H8', 'M10 9H8'],
        'video' => ['M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20', 'm10 8 6 4-6 4z'],
        'audio' => ['M3 14h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z', 'M18 14h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2z', 'M3 16a9 9 0 1 1 18 0'],
        'document' => ['M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7z', 'M14 2v4a2 2 0 0 0 2 2h4', 'M16 13H8', 'M16 17H8'],
        'presentation' => ['M2 3h20', 'M21 3v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V3', 'm7 21 5-5 5 5'],
        'defaut' => ['M12 7v14', 'M3 18a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h5a4 4 0 0 1 4 4 4 4 0 0 1 4-4h5a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1h-6a3 3 0 0 0-3 3 3 3 0 0 0-3-3z'],
    ];

    $niveauxLibelles = [
        'debutant' => 'Débutant',
        'intermediaire' => 'Intermédiaire',
        'avance' => 'Avancé',
    ];

    $type = strtolower($resource->type ?? '');
    $chemins = $typesIcones[$type] ?? $typesIcones['defaut'];
    $typeLibelle = $typesLibelles[$type] ?? $resource->type;

    $fileUrl = $resource->file_path ? Storage::url($resource->file_path) : null;
    $externalUrl = $resource->external_url ?? null;

    // Extraction de l identifiant YouTube pour l apercu integre. Logique conservee
    // telle quelle : elle fonctionne et ne touche a aucune donnee.
    $isYouTube = $externalUrl && preg_match('/(youtube\.com|youtu\.be)/i', $externalUrl);
    $youTubeEmbed = null;
    if ($isYouTube) {
        $host = parse_url($externalUrl, PHP_URL_HOST);
        $path = parse_url($externalUrl, PHP_URL_PATH);
        $requete = parse_url($externalUrl, PHP_URL_QUERY);
        $videoId = null;

        if ($host && stripos($host, 'youtu.be') !== false) {
            $videoId = ltrim($path ?? '', '/');
        } elseif ($host && stripos($host, 'youtube.com') !== false) {
            if (strpos($path ?? '', '/watch') === 0) {
                parse_str($requete ?? '', $params);
                $videoId = $params['v'] ?? null;
            } elseif (strpos($path ?? '', '/embed/') === 0) {
                $videoId = trim(substr($path, strlen('/embed/')));
            }
        }

        if ($videoId) {
            $youTubeEmbed = 'https://www.youtube.com/embed/' . $videoId;
        }
    }

    // Les formulaires de commentaire partagent tous le champ « content ». Sans ce
    // reperage, un retour en erreur reinjecterait la meme saisie dans tous les
    // champs de la page. old('parent_id') identifie celui qui a ete soumis :
    // null pour le formulaire principal, l identifiant du commentaire pour une reponse.
    $formulaireEnErreur = old('parent_id');
    $erreurPublication = session('error');
@endphp

@section('content')
<div class="ds-container ds-stack" style="padding-block: var(--space-4)">

    {{-- En-tete de page. Le fil d Ariane reprenait le titre entier en majuscules,
         juste au-dessus du meme titre en h1 : il ne garde plus que la categorie,
         et devient un vrai lien de retour vers le catalogue filtre. --}}
    <header>
        <p class="ds-overline">
            <a href="{{ $resource->category ? route('bachelier.library.index', ['category' => $resource->category->id]) : route('bachelier.library.index') }}"
               style="display:inline-flex; align-items:center; gap:var(--space-0-5); min-height:44px; color:inherit">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                    <path d="m15 18-6-6 6-6"/>
                </svg>
                {{ mb_strtoupper($resource->category?->name ?? 'BIBLIOTHÈQUE') }}
            </a>
        </p>

        <h1 style="margin-top: var(--space-1)">{{ $resource->title }}</h1>

        <div style="margin-top: var(--space-1-5); display:flex; flex-wrap:wrap; gap:var(--space-0-5)">
            <span class="ds-badge ds-badge-neutral">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                    @foreach ($chemins as $d)<path d="{{ $d }}"/>@endforeach
                </svg>
                {{ $typeLibelle }}
            </span>
            @if ($resource->level)
                <span class="ds-badge ds-badge-neutral">{{ $niveauxLibelles[$resource->level] ?? $resource->level }}</span>
            @endif
            @if ($resource->is_featured)
                <span class="ds-badge ds-badge-accent">À la une</span>
            @endif
        </div>

        <p class="ds-text-secondary" style="margin-top: var(--space-1-5); font-size: var(--text-caption)">
            Par {{ $resource->author ?: ($resource->user?->name ?? 'PEUB') }}
            {{-- published_at est nullable et le controleur accepte explicitement les
                 fiches sans date : la vue appelait diffForHumans() dessus sans garde. --}}
            @if ($resource->published_at)
                &middot; publiée {{ $resource->published_at->locale('fr')->diffForHumans() }}
            @endif
            @if ($resource->duration)
                &middot; {{ $resource->duration }}
            @endif
        </p>
    </header>

    <div style="display:grid; gap:var(--space-3); grid-template-columns:minmax(0, 1fr)" class="library-grille">

        {{-- Colonne principale --}}
        <div class="ds-stack" style="min-width:0">

            <section class="ds-card" style="padding: var(--space-3)">
                @if ($resource->description)
                    <p style="white-space:pre-wrap">{{ $resource->description }}</p>
                @endif

                @if ($resource->tags && count($resource->tags) > 0)
                    <div style="margin-top: var(--space-2); display:flex; flex-wrap:wrap; gap:var(--space-0-5)">
                        @foreach ($resource->tags as $tag)
                            <span class="ds-badge ds-badge-neutral">{{ $tag }}</span>
                        @endforeach
                    </div>
                @endif

                {{-- Actions. Telechargement et lien externe d abord, ce sont les deux
                     raisons d ouvrir cette fiche ; favori et mention j aime ensuite. --}}
                <div style="margin-top: var(--space-3); display:flex; flex-wrap:wrap; gap:var(--space-1); padding-top:var(--space-2); border-top:1px solid var(--border-default)">
                    @if ($fileUrl)
                        <a href="{{ route('bachelier.library.download', $resource) }}" class="ds-btn ds-btn-primary ds-btn-md">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="m7 10 5 5 5-5"/><path d="M12 15V3"/>
                            </svg>
                            Télécharger {{ $resource->file_size ? '(' . $resource->file_size_formatted . ')' : '' }}
                        </a>
                    @endif

                    @if ($externalUrl)
                        <a href="{{ $externalUrl }}" target="_blank" rel="noopener noreferrer" class="ds-btn ds-btn-secondary ds-btn-md">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                                <path d="M15 3h6v6"/><path d="M10 14 21 3"/><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
                            </svg>
                            Ouvrir le lien
                            <span class="sr-only">, s'ouvre dans un nouvel onglet</span>
                        </a>
                    @endif

                    @auth
                    <button type="button" id="favorite-btn" class="ds-btn ds-btn-secondary ds-btn-md"
                            data-resource-id="{{ $resource->id }}"
                            aria-pressed="{{ $isFavorited ? 'true' : 'false' }}">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="{{ $isFavorited ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                            <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7z"/>
                        </svg>
                        <span id="favorite-text">{{ $isFavorited ? 'En favori' : 'Ajouter aux favoris' }}</span>
                    </button>

                    <button type="button" id="like-btn" class="ds-btn ds-btn-secondary ds-btn-md"
                            data-resource-id="{{ $resource->id }}"
                            aria-pressed="{{ $isLiked ? 'true' : 'false' }}">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="{{ $isLiked ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                            <path d="M7 10v12"/><path d="M15 5.88 14 10h5.83a2 2 0 0 1 1.92 2.56l-2.33 8A2 2 0 0 1 17.5 22H4a2 2 0 0 1-2-2v-8a2 2 0 0 1 2-2h2.76a2 2 0 0 0 1.79-1.11L12 2a3.13 3.13 0 0 1 3 3.88z"/>
                        </svg>
                        <span class="sr-only">J'aime cette ressource</span>
                        <span id="like-count" class="numbers" aria-hidden="true">{{ $resource->likes_count }}</span>
                    </button>
                    @endauth
                </div>
            </section>

            {{-- Apercu integre selon le type --}}
            @if ($type === 'pdf' && $fileUrl)
                <section class="ds-card" style="padding:0; overflow:hidden">
                    <h2 class="sr-only">Aperçu du document</h2>
                    <iframe src="{{ $fileUrl }}#navpanes=0&pagemode=none&toolbar=1"
                            title="Aperçu du document {{ $resource->title }}"
                            style="display:block; width:100%; height:min(70dvh, 720px); border:0"></iframe>
                </section>
            @elseif ($type === 'video' && $isYouTube && $youTubeEmbed)
                <section class="ds-card" style="padding:0; overflow:hidden">
                    <h2 class="sr-only">Lecture de la vidéo</h2>
                    <div style="position:relative; width:100%; padding-bottom:56.25%">
                        <iframe src="{{ $youTubeEmbed }}"
                                title="Lecture de la vidéo {{ $resource->title }}"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                allowfullscreen
                                style="position:absolute; inset:0; width:100%; height:100%; border:0"></iframe>
                    </div>
                </section>
            @elseif ($type === 'video' && ($fileUrl || $externalUrl))
                <section class="ds-card" style="padding: var(--space-2)">
                    <h2 class="sr-only">Lecture de la vidéo</h2>
                    <video controls preload="metadata"
                           style="display:block; width:100%; max-height:70dvh; background:var(--surface-inverse)">
                        <source src="{{ $fileUrl ?? $externalUrl }}" type="video/mp4">
                        Votre navigateur ne permet pas la lecture de cette vidéo.
                    </video>
                </section>
            @elseif ($type === 'audio' && ($fileUrl || $externalUrl))
                {{-- Les fiches audio n avaient aucun lecteur : le type etait annonce
                     dans une pastille, sans jamais proposer d ecouter. --}}
                <section class="ds-card" style="padding: var(--space-2)">
                    <h2 class="sr-only">Écoute de l'enregistrement</h2>
                    <audio controls preload="metadata" style="display:block; width:100%">
                        <source src="{{ $fileUrl ?? $externalUrl }}">
                        Votre navigateur ne permet pas la lecture de cet enregistrement.
                    </audio>
                </section>
            @elseif ($resource->thumbnail)
                <section class="ds-card" style="padding: var(--space-2)">
                    <h2 class="sr-only">Aperçu</h2>
                    <img src="{{ Storage::url($resource->thumbnail) }}"
                         alt="Aperçu de {{ $resource->title }}"
                         loading="lazy" decoding="async"
                         style="display:block; width:100%; max-height:420px; object-fit:cover">
                </section>
            @endif

            {{-- Commentaires --}}
            <section class="ds-stack-sm">
                <h2 style="font-size: var(--text-h3)">
                    {{ $comments->total() > 1 ? 'Commentaires' : 'Commentaire' }}
                    <span class="ds-text-secondary numbers" style="font-weight:var(--font-regular)">({{ $comments->total() }})</span>
                </h2>

                {{-- Refus de moderation renvoye par storeComment. La notification
                     passagere du layout disparait en sept secondes ; l alerte reste a
                     l ecran, a cote du champ, avec la saisie conservee par old(). --}}
                @if ($erreurPublication)
                    <div class="ds-alert ds-alert-error" role="alert">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false" style="flex-shrink:0; margin-top:2px">
                            <path d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20"/><path d="M12 8v4"/><path d="M12 16h.01"/>
                        </svg>
                        <div>
                            <p style="font-weight:var(--font-semibold)">Votre commentaire n'a pas été publié</p>
                            <p style="margin-top: var(--space-0-5)">{{ $erreurPublication }}</p>
                            <p style="margin-top: var(--space-0-5)">Votre texte est conservé ci-dessous. Reformulez le passage concerné, puis publiez à nouveau.</p>
                        </div>
                    </div>
                @endif

                @auth
                {{-- Soumission classique, sans JavaScript : les erreurs de validation
                     et le refus de moderation reviennent alors par le canal Laravel,
                     avec la saisie conservee, la ou l ancien appel fetch les affichait
                     dans une boite qui disparaissait au rechargement. --}}
                <form action="{{ route('bachelier.library.comments.store', $resource) }}" method="POST" class="ds-card" style="padding: var(--space-2)">
                    @csrf
                    <label class="ds-label" for="content">Votre commentaire</label>
                    <textarea name="content" id="content" rows="4" required maxlength="1000"
                              class="ds-field ds-textarea @if(!$formulaireEnErreur) @error('content') ds-field-error @enderror @endif"
                              style="min-height:100px"
                              placeholder="Une question, un retour d'expérience sur cette ressource...">{{ $formulaireEnErreur ? '' : old('content') }}</textarea>
                    @if (!$formulaireEnErreur)
                        @error('content')<p class="ds-error-text">{{ $message }}</p>@enderror
                    @endif
                    <p class="ds-hint">1 000 caractères au maximum. Votre commentaire est visible par toute la communauté.</p>
                    <div style="margin-top: var(--space-1-5); display:flex; justify-content:flex-end">
                        <button type="submit" class="ds-btn ds-btn-primary ds-btn-md">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                                <path d="m22 2-7 20-4-9-9-4z"/><path d="M22 2 11 13"/>
                            </svg>
                            Publier
                        </button>
                    </div>
                </form>
                @endauth

                @forelse ($comments as $comment)
                    <article class="ds-card" id="comment-{{ $comment->id }}" style="padding: var(--space-2)">
                        <div style="display:flex; gap:var(--space-1-5); align-items:center">
                            <span class="library-avatar" aria-hidden="true">{{ mb_substr($comment->user?->name ?? '?', 0, 1) }}</span>
                            <div style="min-width:0">
                                <p style="font-weight:var(--font-semibold); font-size:var(--text-caption)">{{ $comment->user?->name ?? 'Membre retiré' }}</p>
                                <p class="ds-text-secondary" style="font-size:var(--text-label)">
                                    <time datetime="{{ $comment->created_at?->toDateString() }}">{{ $comment->created_at?->locale('fr')->diffForHumans() }}</time>
                                </p>
                            </div>
                        </div>

                        <p style="margin-top: var(--space-1-5); white-space:pre-wrap">{{ $comment->content }}</p>

                        @auth
                        <div x-data="{ ouvert: {{ (string) $formulaireEnErreur === (string) $comment->id ? 'true' : 'false' }} }"
                             style="margin-top: var(--space-1-5)">
                            <div style="display:flex; flex-wrap:wrap; gap:var(--space-0-5)">
                                <button type="button" class="library-reaction"
                                        data-comment-id="{{ $comment->id }}"
                                        aria-pressed="{{ $comment->isLikedBy(auth()->user()) ? 'true' : 'false' }}">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                                        <path d="M7 10v12"/><path d="M15 5.88 14 10h5.83a2 2 0 0 1 1.92 2.56l-2.33 8A2 2 0 0 1 17.5 22H4a2 2 0 0 1-2-2v-8a2 2 0 0 1 2-2h2.76a2 2 0 0 0 1.79-1.11L12 2a3.13 3.13 0 0 1 3 3.88z"/>
                                    </svg>
                                    <span class="sr-only">J'aime ce commentaire</span>
                                    <span id="comment-like-count-{{ $comment->id }}" class="numbers" aria-hidden="true">{{ $comment->likes_count }}</span>
                                </button>

                                <button type="button" @click="ouvert = !ouvert"
                                        :aria-expanded="ouvert ? 'true' : 'false'"
                                        aria-controls="reponse-{{ $comment->id }}"
                                        class="ds-btn ds-btn-ghost ds-btn-md">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                                        <path d="m15 10 5 5-5 5"/><path d="M4 4v7a4 4 0 0 0 4 4h12"/>
                                    </svg>
                                    Répondre
                                </button>
                            </div>

                            <form id="reponse-{{ $comment->id }}" x-show="ouvert"
                                  action="{{ route('bachelier.library.comments.store', $resource) }}" method="POST"
                                  style="margin-top: var(--space-1-5){{ (string) $formulaireEnErreur === (string) $comment->id ? '' : '; display:none' }}">
                                @csrf
                                <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                <label class="sr-only" for="contenu-reponse-{{ $comment->id }}">Votre réponse</label>
                                <textarea name="content" id="contenu-reponse-{{ $comment->id }}" rows="3" required maxlength="1000"
                                          class="ds-field ds-textarea @if((string) $formulaireEnErreur === (string) $comment->id) @error('content') ds-field-error @enderror @endif"
                                          style="min-height:90px"
                                          placeholder="Votre réponse...">{{ (string) $formulaireEnErreur === (string) $comment->id ? old('content') : '' }}</textarea>
                                @if ((string) $formulaireEnErreur === (string) $comment->id)
                                    @error('content')<p class="ds-error-text">{{ $message }}</p>@enderror
                                @endif
                                <div style="margin-top: var(--space-1); display:flex; gap:var(--space-1); flex-wrap:wrap">
                                    <button type="submit" class="ds-btn ds-btn-primary ds-btn-md">Publier ma réponse</button>
                                    <button type="button" @click="ouvert = false" class="ds-btn ds-btn-secondary ds-btn-md">Annuler</button>
                                </div>
                            </form>
                        </div>
                        @endauth

                        @if ($comment->replies->count() > 0)
                        <div style="margin-top: var(--space-2); padding-left: var(--space-2); border-left:2px solid var(--border-default); display:grid; gap:var(--space-1-5)">
                            @foreach ($comment->replies as $reply)
                            <div class="ds-panel" style="padding: var(--space-1-5)">
                                <div style="display:flex; gap:var(--space-1); align-items:center">
                                    <span class="library-avatar library-avatar-petit" aria-hidden="true">{{ mb_substr($reply->user?->name ?? '?', 0, 1) }}</span>
                                    <div style="min-width:0">
                                        <p style="font-size:var(--text-caption); font-weight:var(--font-semibold)">{{ $reply->user?->name ?? 'Membre retiré' }}</p>
                                        <p class="ds-text-secondary" style="font-size:var(--text-label)">{{ $reply->created_at?->locale('fr')->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <p style="margin-top: var(--space-1); font-size:var(--text-caption); white-space:pre-wrap">{{ $reply->content }}</p>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </article>
                @empty
                    <div class="ds-card-flat" style="padding: var(--space-6); text-align:center">
                        <span class="ds-text-secondary" style="display:inline-flex">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                                <path d="M7.9 20A9 9 0 1 0 4 16.1L2 22z"/>
                            </svg>
                        </span>
                        <h3 style="margin-top: var(--space-2)">Aucun commentaire</h3>
                        <p class="ds-text-secondary" style="margin-top: var(--space-1)">
                            @auth
                                Cette ressource vous a servi, ou pas du tout ? Dites-le : votre retour aide les suivants à choisir.
                            @else
                                Personne n'a encore commenté cette ressource.
                            @endauth
                        </p>
                    </div>
                @endforelse

                @if ($comments->hasPages())
                <div>
                    {{ $comments->links() }}
                </div>
                @endif
            </section>
        </div>

        {{-- Colonne laterale --}}
        <aside class="ds-stack" style="min-width:0">
            <section class="ds-card" style="padding: var(--space-2)">
                <h2 class="ds-overline">Cette ressource en chiffres</h2>
                <dl style="margin-top: var(--space-1-5); display:grid; gap:var(--space-1)">
                    <div style="display:flex; justify-content:space-between; gap:var(--space-1); font-size:var(--text-caption)">
                        <dt class="ds-text-secondary">Vues</dt>
                        <dd class="numbers" style="font-weight:var(--font-medium)">{{ number_format($resource->views_count, 0, ',', ' ') }}</dd>
                    </div>
                    <div style="display:flex; justify-content:space-between; gap:var(--space-1); font-size:var(--text-caption)">
                        <dt class="ds-text-secondary">Téléchargements</dt>
                        <dd class="numbers" style="font-weight:var(--font-medium)">{{ number_format($resource->downloads_count, 0, ',', ' ') }}</dd>
                    </div>
                    <div style="display:flex; justify-content:space-between; gap:var(--space-1); font-size:var(--text-caption)">
                        <dt class="ds-text-secondary">Mises en favori</dt>
                        <dd class="numbers" style="font-weight:var(--font-medium)">{{ number_format($resource->favorites_count, 0, ',', ' ') }}</dd>
                    </div>
                    {{-- NOTE DE REVUE, hors perimetre de ce lot.
                         LibraryResource::likes() est un hasMany non filtre, alors que
                         toggleCommentLike enregistre aussi library_resource_id sur les
                         mentions j aime de commentaires. Ce compteur additionne donc les
                         j aime de la ressource ET ceux de ses commentaires. Le meme
                         defaut fausse isLikedBy(), qui allume le bouton « J'aime » de la
                         ressource des qu on a aime un de ses commentaires. La correction
                         est un whereNull('likeable_id') dans le modele. --}}
                    <div style="display:flex; justify-content:space-between; gap:var(--space-1); font-size:var(--text-caption)">
                        <dt class="ds-text-secondary">Mentions j'aime</dt>
                        <dd class="numbers" style="font-weight:var(--font-medium)">{{ number_format($resource->likes_count, 0, ',', ' ') }}</dd>
                    </div>
                </dl>
            </section>

            @if ($relatedResources->count() > 0)
            <section class="ds-card" style="padding: var(--space-2)">
                <h2 class="ds-overline">Dans la même catégorie</h2>
                <ul style="margin-top: var(--space-1-5); list-style:none; padding:0; display:grid; gap:var(--space-1-5)">
                    @foreach ($relatedResources as $related)
                        @php $cheminsSimilaire = $typesIcones[$related->type] ?? $typesIcones['defaut']; @endphp
                        <li>
                            <a href="{{ route('bachelier.library.show', $related) }}"
                               style="display:flex; gap:var(--space-1); align-items:flex-start; min-height:44px; color:inherit; text-decoration:none">
                                <span style="display:grid; place-items:center; width:32px; height:32px; flex-shrink:0; border-radius:var(--radius-chip); background:var(--accent-surface); color:var(--accent)">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                                        @foreach ($cheminsSimilaire as $d)<path d="{{ $d }}"/>@endforeach
                                    </svg>
                                </span>
                                <span style="min-width:0">
                                    <span class="line-clamp-2" style="display:block; font-size:var(--text-caption); font-weight:var(--font-medium)">{{ $related->title }}</span>
                                    <span class="ds-text-secondary" style="display:block; font-size:var(--text-label)">
                                        {{ $typesLibelles[$related->type] ?? $related->type }}
                                        &middot; {{ $related->views_count }} {{ $related->views_count > 1 ? 'vues' : 'vue' }}
                                    </span>
                                </span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </section>
            @endif
        </aside>
    </div>

</div>

@push('styles')
<style>
    /* CONTRASTE AA, mesure a 360px et non suppose. --accent sur --accent-surface
       mesure 4,31:1 en mode clair, sous le seuil de 4,5:1. L appariement vient de
       theme.css, hors perimetre de ce lot ; la correction de fond est un --accent
       plus sombre ou une --accent-surface plus dense. En attendant, tout TEXTE pose
       sur cette teinte passe en --text-primary : mesure 11,0:1 en clair et 13,5:1
       en sombre. Les icones, elles, gardent --accent : a 4,31:1 elles depassent
       largement le seuil de 3:1 des elements non textuels.
       Selecteur en html[data-ds] .x, soit (0,2,1), pour battre la classe du design
       system, (0,1,0). Aucune regle propre au mode sombre. */
    html[data-ds] .ds-badge-accent { color: var(--text-primary); }

    @media (min-width: 1024px) {
        html[data-ds] .library-grille {
            grid-template-columns: minmax(0, 2fr) minmax(0, 1fr);
            align-items: start;
        }
    }

    html[data-ds] .library-avatar {
        display: grid;
        place-items: center;
        width: 40px;
        height: 40px;
        flex-shrink: 0;
        border-radius: var(--radius-pill);
        background: var(--surface-secondary);
        color: var(--text-primary);
        font-weight: var(--font-semibold);
        text-transform: uppercase;
    }
    html[data-ds] .library-avatar-petit {
        width: 28px;
        height: 28px;
        font-size: var(--text-label);
    }

    /* Mention j aime sur un commentaire : l etat passe par aria-pressed, et non
       par une classe de palette ajoutee au vol comme le faisait l ancien script. */
    html[data-ds] .library-reaction {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: var(--space-0-5);
        min-width: 44px;
        height: 44px;
        padding: 0 var(--space-1-5);
        border: 1px solid var(--border-default);
        border-radius: var(--radius-pill);
        background: var(--surface-raised);
        color: var(--text-secondary);
        font-size: var(--text-label);
        font-weight: var(--font-semibold);
        cursor: pointer;
    }
    html[data-ds] .library-reaction:hover { background: var(--surface-hover); }
    html[data-ds] .library-reaction[aria-pressed="true"] {
        background: var(--accent-surface);
        border-color: var(--accent-border);
        color: var(--text-primary);
    }

    /* Les deux boutons d etat de l en-tete suivent la meme convention. */
    html[data-ds] #favorite-btn[aria-pressed="true"],
    html[data-ds] #like-btn[aria-pressed="true"] {
        background: var(--accent-surface);
        border-color: var(--accent-border);
        color: var(--text-primary);
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const jeton = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    function appeler(url) {
        return fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': jeton,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        }).then(function (reponse) { return reponse.json(); });
    }

    // ---- Mise en favori de la ressource ----
    const boutonFavori = document.getElementById('favorite-btn');
    if (boutonFavori) {
        boutonFavori.addEventListener('click', function () {
            appeler('/bachelier/library/' + boutonFavori.dataset.resourceId + '/favorite')
                .then(function (donnees) {
                    if (donnees.error) { return; }
                    boutonFavori.setAttribute('aria-pressed', donnees.isFavorited ? 'true' : 'false');
                    boutonFavori.querySelector('svg').setAttribute('fill', donnees.isFavorited ? 'currentColor' : 'none');
                    document.getElementById('favorite-text').textContent =
                        donnees.isFavorited ? 'En favori' : 'Ajouter aux favoris';
                })
                .catch(function (erreur) { console.error('Erreur:', erreur); });
        });
    }

    // ---- Mention j aime sur la ressource ----
    const boutonJaime = document.getElementById('like-btn');
    if (boutonJaime) {
        boutonJaime.addEventListener('click', function () {
            appeler('/bachelier/library/' + boutonJaime.dataset.resourceId + '/like')
                .then(function (donnees) {
                    if (donnees.error) { return; }
                    boutonJaime.setAttribute('aria-pressed', donnees.isLiked ? 'true' : 'false');
                    boutonJaime.querySelector('svg').setAttribute('fill', donnees.isLiked ? 'currentColor' : 'none');
                    // textContent, jamais innerHTML : la reponse du serveur n entre
                    // dans la page que comme du texte.
                    document.getElementById('like-count').textContent = donnees.count;
                })
                .catch(function (erreur) { console.error('Erreur:', erreur); });
        });
    }

    // ---- Mention j aime sur un commentaire ----
    document.querySelectorAll('.library-reaction').forEach(function (bouton) {
        bouton.addEventListener('click', function () {
            const identifiant = bouton.dataset.commentId;
            appeler('/bachelier/library/comments/' + identifiant + '/like')
                .then(function (donnees) {
                    bouton.setAttribute('aria-pressed', donnees.isLiked ? 'true' : 'false');
                    bouton.querySelector('svg').setAttribute('fill', donnees.isLiked ? 'currentColor' : 'none');
                    document.getElementById('comment-like-count-' + identifiant).textContent = donnees.count;
                })
                .catch(function (erreur) { console.error('Erreur:', erreur); });
        });
    });
});
</script>
@endpush
@endsection
