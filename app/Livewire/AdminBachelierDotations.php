<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Bachelier;
use App\Models\DotationInventaire;
use App\Models\Dotation;

class AdminBachelierDotations extends Component
{
    public Bachelier $bachelier;
    public $attributions = [];
    public $dotationsDisponibles = [];
    public $selectedInventaireId = '';

    public function mount(Bachelier $bachelier)
    {
        $this->bachelier = $bachelier;
        $this->loadData();
    }

    public function loadData()
    {
        $this->attributions = $this->bachelier->dotationsAttributions()->with('inventaire')->get();
        $this->dotationsDisponibles = DotationInventaire::where('stock_disponible', '>', 0)
            ->active()
            ->get();
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

        if ($inventaire->stock_disponible <= 0) {
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

        Dotation::creerPourBachelier($this->bachelier->id, $inventaire->id, [
            'attribue_par' => auth()->id(),
        ]);

        $this->selectedInventaireId = '';
        $this->loadData();
        $this->dispatch('dotationAttributed');
    }

    public function render()
    {
        return view('livewire.admin-bachelier-dotations');
    }
}
