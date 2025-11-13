<?php
declare(strict_types=1);

namespace App\Http\Livewire\Dynamics;

use Livewire\Component;
use App\Contracts\DynamicTabContract;

abstract class AbstractDynamic extends Component implements DynamicTabContract
{
    /**
     * @var array|false[]
     */
    public array $defaultRows = [
        'submitted' => false,
    ];

    /**
     * @param $id
     */
    public function __construct($id = null)
    {
        parent::__construct($id);

        $this->{$this->fieldName} = [];
    }

    /**
     * @param int $index
     * @param bool $withValidation
     * @return void
     */
    public function submitRow(int $index, bool $withValidation = true): void
    {
        if ($withValidation) {
            $this->validate($this->prepareValidationRules($index));
        }

        $this->{$this->fieldName}[$index]['submitted'] = true;
    }

    /**
     * @return void
     */
    public function addRow(): void
    {
        $this->validate($this->prepareValidationRules());

        $this->submitAllRows();

        $this->{$this->fieldName}[] = $this->defaultRows;
    }

    /**
     * @param int $index
     * @return void
     */
    public function removeRow(int $index): void
    {
        unset($this->{$this->fieldName}[$index]);
    }

    /**
     * @return void
     */
    protected function submitAllRows(): void
    {
        $this->validate($this->prepareValidationRules());

        foreach ($this->{$this->fieldName} as $key => $row) {
            $this->submitRow($key, false);
        }
    }

    /**
     * @param int|null $index
     * @return array
     */
    protected function prepareValidationRules(?int $index = NULL): array
    {
        $rules = [];

        foreach ($this->validationRules as $field => $rule) {
            $rules[$this->fieldName . '.' . ($index ?? '*') . '.' . $field] = $rule;
        }

        return $rules;
    }
}
