<?php
declare(strict_types=1);

namespace App\Http\Livewire\Dynamics;

use Illuminate\View\View;
use Livewire\WithFileUploads;
use Illuminate\Contracts\View\Factory; // важно: конкретный View
use Illuminate\Support\Facades\Storage;

class ImageDynamic extends AbstractDynamic
{
    use WithFileUploads;

    public array $modelImages = [];

    public array $defaultRows = [
        'image'     => null,
        'position'  => 0,
        'status'    => false,  // ← bool, а не 0/1
        'submitted' => false,
        'existedId' => null,
    ];

    protected array $validationRules = [
        'image'    => 'required|image|mimes:png,jpg,jpeg|max:3072',
        'position' => 'required|integer|min:0',
        'status'   => 'required|boolean',
        'existedId' => 'nullable',
    ];

    /** @var non-empty-string */
    public string $fieldName = 'images';

    public function mount(): void
    {
        parent::mount(); // гарантирует, что $this->{$fieldName} инициализирован как массив

        $localeImages = [];

        foreach ($this->modelImages as $item) {
            $localeImages[] = [
                'image'     => Storage::disk('public')->url($item->path),
                'position'  => (int) $item->position,
                'status'    => (bool) $item->status,
                'submitted' => true,
                'existedId' => (int) $item->id,
            ];
        }

        $this->fill([
            $this->fieldName => $localeImages,
        ]);
    }

    public function addRow(): void
    {
        $this->submitAllRows();
        $this->{$this->fieldName}[] = $this->defaultRows;
    }

    protected function submitAllRows(): void
    {
        foreach ($this->{$this->fieldName} as $key => $_row) {
            $this->submitRow($key, false);
        }
    }

    public function render(): Factory|\Illuminate\Contracts\View\View
    {
        /** @phpstan-ignore-next-line  */
        return view('livewire.image-dynamic');
    }
}
