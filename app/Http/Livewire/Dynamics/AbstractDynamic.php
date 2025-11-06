<?php
declare(strict_types=1);

namespace App\Http\Livewire\Dynamics;

use Livewire\Component;
use App\Contracts\DynamicTabContract;

abstract class AbstractDynamic extends Component implements DynamicTabContract
{
    /**
     * Имя массива-коллекции строк, с которым работают методы компонента.
     * Наследники могут переопределить значение (например, 'images').
     *
     * @var non-empty-string
     */
    public string $fieldName = 'items';

    /**
     * Базовый шаблон строки.
     * Наследники могут расширить/переопределить это свойство,
     * но форма должна быть совместима по ключам.
     *
     * @var array{submitted: bool}
     */
    public array $defaultRows = [
        'submitted' => false,
    ];

    /**
     * Базовые правила валидации для дочерних элементов.
     * Ключи будут превратены в "{fieldName}.*.{key}".
     *
     * @var array<string, string|array>
     */
    protected array $validationRules = [];

    /**
     * Livewire-инициализация вместо __construct().
     */
    public function mount(): void
    {
        // гарантируем, что поле-коллекция существует и это массив
        if (!isset($this->{$this->fieldName}) || !is_array($this->{$this->fieldName})) {
            $this->{$this->fieldName} = [];
        }
    }

    public function submitRow(int $index, bool $withValidation = true): void
    {
        if ($withValidation) {
            $this->validate($this->prepareValidationRules($index));
        }

        // помечаем строку как "отправленную"
        $this->{$this->fieldName}[$index]['submitted'] = true;
    }

    public function addRow(): void
    {
        $this->validate($this->prepareValidationRules());
        $this->submitAllRows();

        $this->{$this->fieldName}[] = $this->defaultRows;
    }

    public function removeRow(int $index): void
    {
        unset($this->{$this->fieldName}[$index]);
        // по желанию можно пересобрать индексы:
        // $this->{$this->fieldName} = array_values($this->{$this->fieldName});
    }

    protected function submitAllRows(): void
    {
        $this->validate($this->prepareValidationRules());

        foreach ($this->{$this->fieldName} as $key => $_row) {
            $this->submitRow($key, false);
        }
    }

    /**
     * Сконструировать правила валидации для конкретной строки ($index) или для всех ('*').
     *
     * @param int|null $index
     * @return array<string, string|array>
     */
    protected function prepareValidationRules(?int $index = null): array
    {
        $rules = [];

        foreach ($this->validationRules as $field => $rule) {
            $rules[sprintf('%s.%s.%s', $this->fieldName, $index ?? '*', $field)] = $rule;
        }

        return $rules;
    }
}
