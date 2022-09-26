<?php

namespace App\Http\Livewire\Tables;

use App\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Columns\ButtonGroupColumn;

class RoleTable extends BaseTable
{
    /**
     * @var string
     */
    protected $model = Role::class;

    /**
     * @var string
     */
    protected string $module = 'roles';

    /**
     * @return array
     */
    public function columns(): array
    {
        return [
            Column::make(__('fields.id'), "id")
                ->sortable()->searchable(),

            Column::make(__('fields.title'), "title")
                ->sortable()->searchable(),

            Column::make(__('fields.key'), "key")
                ->sortable()->searchable(),

            ButtonGroupColumn::make(__('admin.actions'))
                ->buttons($this->getActionButtons())
        ];
    }

    /**
     * @return Builder
     */
    public function builder(): Builder
    {
        return Role::query()->with('permissions');
    }
}
