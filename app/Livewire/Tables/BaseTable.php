<?php
declare(strict_types=1);

namespace App\Livewire\Tables;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;

abstract class BaseTable extends DataTableComponent
{
    protected string $module;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    /**
     * Установите модуль в наследнике, например, в mount()/configure():
     * $this->module = 'users';
     */
    protected function getActionButtons(
        bool $includeShow = false,
        bool $includeEdit = true,
        bool $includeDelete = true
    ): array {
        $buttons = [];

        if ($includeShow) {
            $buttons[] = LinkColumn::make('show')
                ->title(fn ($row) => __('buttons.show'))
                ->location(fn ($row) => route('admin.' . $this->module . '.show', $row))
                ->attributes(fn ($row) => ['class' => 'btn btn-xs btn-success']);
        }

        if ($includeEdit) {
            $buttons[] = LinkColumn::make('edit')
                ->title(fn ($row) => __('buttons.edit'))
                ->location(fn ($row) => route('admin.' . $this->module . '.edit', $row))
                ->attributes(fn ($row) => ['class' => 'btn btn-xs btn-warning']);
        }

        if ($includeDelete) {
            $buttons[] = LinkColumn::make('delete')
                ->title(fn ($row) => __('buttons.delete'))
                ->location(fn ($row) => route('admin.' . $this->module . '.destroy', $row))
                ->attributes(fn ($row) => ['class' => 'btn btn-xs btn-danger', 'delete-button' => true]);
        }

        return $buttons;
    }
}
