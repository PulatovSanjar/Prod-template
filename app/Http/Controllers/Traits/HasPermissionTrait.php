<?php
declare(strict_types=1);

namespace App\Http\Controllers\Traits;

use Illuminate\Support\Facades\Gate;

trait HasPermissionTrait
{
    /**
     * @var array|string[]
     */
    private array $defaultAccess = [
        'index'   => 'access',
        'create'  => 'create',
        'edit'    => 'edit',
        'destroy' => 'delete',
    ];

    /**
     * Execute an action on the controller.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function callAction($method, $parameters)
    {
        $access = property_exists($this, 'access') ? $this->access : $this->defaultAccess;
        $module = $this->module ?? 'dashboard';

        if (isset($access[$method])) {

            Gate::authorize($module . '_' . $access[$method]);

        }

        return $this->{$method}(...array_values($parameters));
    }
}
