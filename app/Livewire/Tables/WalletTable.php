<?php
declare(strict_types=1);

namespace App\Livewire\Tables;

use App\Models\Wallet;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Columns\ButtonGroupColumn;

class WalletTable extends BaseTable
{
    /**
     * @var string
     */
    protected $model = Wallet::class;

    /**
     * @var string
     */
    protected string $module = 'wallet';

    /**
     * @return array
     */
    public function columns(): array
    {
        return [
            Column::make(__('fields.id'), 'id')
                ->sortable()->searchable(),

            Column::make(__('fields.name'), 'name')
                ->sortable()->searchable(),

            Column::make(__('fields.balance'), 'balance')
                ->sortable()->searchable(),

            ButtonGroupColumn::make(__('admin.actions'))
                ->buttons($this->getActionButtons()),
        ];
    }
}
