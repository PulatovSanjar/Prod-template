<?php

namespace App\Livewire\Tables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Columns\BooleanColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\ButtonGroupColumn;

class UserTable extends BaseTable
{
    /**
     * @var string
     */
    protected $model = User::class;

    /**
     * @var string
     */
    protected string $module = 'users';

    /**
     * @return array
     */
    public function columns(): array
    {
        return [
            Column::make(__('fields.id'), "id")
                ->sortable()->searchable(),

            Column::make(__('fields.name'), "name")
                ->sortable()->searchable(),

            Column::make(__('fields.email'), "email")
                ->sortable()->searchable(),

            BooleanColumn::make(__('fields.email_verified'), "email_verified_at")
                ->sortable(),

            ButtonGroupColumn::make(__('admin.actions'))
                ->buttons($this->getActionButtons())
        ];
    }

    /**
     * @return Builder
     */
    public function builder(): Builder
    {
        return User::query()->with('roles');
    }
}
