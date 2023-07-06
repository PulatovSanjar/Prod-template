<?php

namespace App\Http\Livewire\Dynamics;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;

class ImageDynamic extends AbstractDynamic
{
    use WithFileUploads;

    public $modelImages;

    /**
     * @var array
     */
    public array $defaultRows = [
        'image'     => null,
        'position'  => 0,
        'status'    => 0,
        'submitted' => false,
        'existedId' => null
    ];

    /**
     * @var array|string[]
     */
    protected array $validationRules = [
        'image'    => 'required|image|mimes:png,jpg,jpeg|max:3072',
        'position' => 'required|integer|min:0',
        'status'   => 'required|boolean',
        'existedId' => 'nullable'
    ];

    /**
     * @var string
     */
    public string $fieldName = 'images';

    public function mount()
    {
        $images = [];

        foreach ($this->modelImages ?? [] as $item) {
            $images[] = [
                'image'        => Storage::disk('public')->url($item->path),
                'position'     => $item->position,
                'status'       => $item->status,
                'submitted'    => true,
                'existedId'    => $item->id
            ];
        }

        $this->fill([
            $this->fieldName => $images
        ]);
    }

    /**
     * @return void
     */
    public function addRow(): void
    {
        $this->submitAllRows();

        $this->{$this->fieldName}[] = $this->defaultRows;
    }

    /**
     * @return void
     */
    protected function submitAllRows(): void
    {

        foreach ($this->{$this->fieldName} as $key => $row) {
            $this->submitRow($key, false);
        }
    }

    /**
     * @return Factory|View|Application
     */
    public function render()
    {
        return view('livewire.image-dynamic');
    }
}
