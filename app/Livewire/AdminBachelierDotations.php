<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Bachelier;
use App\Models\DotationInventaire;
use App\Models\Dotation;
use App\Services\AnsutStockClient;
use App\Services\AnsutStockException;
use Illuminate\Support\Facades\DB;

class AdminBachelierDotations extends Component
{
    public Bachelier $bachelier;
    public $attributions = [];
    public $dotationsDisponibles = [];
    public $selectedInventaireId = '';
    /** Point de retrait ANSUT Stock (code du site) : obligatoire pour un ordinateur portable. */
    public $selectedPointRetrait = '';
    public $pointsRetrait = [];

    public function mount(Bachelier $bachelier)
    {
        $this->bachelier = $bachelier;
        $this->loadData();
    }

    public function loadData()
    {
        $this->attributions = $this->bachelier->dotationsAttributions()->with('inventaire')->get();
        // Ordinateurs : toujours proposés, la disponibilité est vérifiée par ANSUT Stock au point de retrait choisi.
        $this->dotationsDisponibles = DotationInventaire::active()
            ->where(fn ($q) => $q->where('stock_disponible', '>', 0)->orWhere('type_dotation', 'ordinateur_portable'))
            ->get();
        $client = app(AnsutStockClient::class);
        if ($client->configured()) {
            try {
                $this->pointsRetrait = $client->pointsDeRetrait();
            } catch (\Throwable $e) {
                $this->pointsRetrait = [];
                $this->addError('pointsRetrait', 'Points de retrait indisponibles : ANSUT Stock ne répond pas.');
            }
        }
    }

    public function attribute()
    {
        $this->resetErrorBag();

        if (!$this->selectedInventaireId) {
            $this->addError('selectedInventaireId', 'Veuillez sélectionner une dotation.');
            return;
        }

        $inventaire = DotationInventaire::find($this->selectedInventaireId);

        if (!$inventaire) {
            $this->addError('general', 'Article non trouvé.');
            return;
        }

        if (!$this->bachelier->boursier_peub) {
            $this->addError('general', 'Ce bachelier n\'est pas un boursier PEUB et ne peut pas recevoir de dotation.');
            return;
        }

        $ordinateur = $inventaire->type_dotation === 'ordinateur_portable';
        if ($ordinateur && ! $this->selectedPointRetrait) {
            $this->addError('selectedPointRetrait', 'Choisissez le point de retrait de l\'ordinateur.');
            return;
        }

        if (! $ordinateur && $inventaire->stock_disponible <= 0) {
            $this->addError('general', 'Cet article n\'est plus disponible en stock.');
            return;
        }

        if ($this->bachelier->dotationsAttributions()->where('inventaire_id', $inventaire->id)->exists()) {
            $this->addError('general', 'Ce bachelier a déjà reçu cet article de dotation.');
            return;
        }

        if ($this->bachelier->hasActiveDotationOfType($inventaire->type_dotation)) {
            $this->addError('general', "Ce bachelier a déjà une dotation de type '{$inventaire->type_dotation}' active.");
            return;
        }

        if ($ordinateur) {
            // La dotation n'existe que si ANSUT Stock a réservé un appareil : sinon tout est annulé.
            try {
                DB::transaction(function () use ($inventaire) {
                    $dotation = Dotation::creerPourBachelier($this->bachelier->id, $inventaire->id, [
                        'attribue_par' => auth()->id(),
                        'stock_externe' => true,
                    ]);
                    $reponse = app(AnsutStockClient::class)->attribuer($dotation, $this->selectedPointRetrait);
                    $dotation->donnees_specifiques = ['ansut_stock' => [
                        'numero' => $reponse['number'] ?? null,
                        'point_retrait' => $this->selectedPointRetrait,
                        'statut' => $reponse['status'] ?? null,
                    ]];
                    $dotation->save();
                });
            } catch (AnsutStockException $e) {
                $this->addError('general', $e->getMessage());
                return;
            }
        } else {
            Dotation::creerPourBachelier($this->bachelier->id, $inventaire->id, [
                'attribue_par' => auth()->id(),
            ]);
        }

        $this->selectedInventaireId = '';
        $this->selectedPointRetrait = '';
        $this->loadData();
        $this->dispatch('dotationAttributed');
    }

    public function render()
    {
        return view('livewire.admin-bachelier-dotations');
    }
}
