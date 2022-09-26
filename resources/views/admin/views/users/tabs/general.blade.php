@include('admin.helpers.elements.text', [
    'label'     => __('fields.name'),
    'name'      => 'name',
    'value'     => $model->name ?? NULL
])

@include('admin.helpers.elements.text', [
    'label'     => __('fields.email'),
    'name'      => 'email',
    'value'     => $model->email ?? NULL
])

@if(empty($model))

    @include('admin.helpers.elements.text', [
        'label' => __('fields.password'),
        'name'  => 'password',
        'type'  => 'password',
        'value' => NULL
    ])

    @include('admin.helpers.elements.text', [
        'label' => __('fields.password_confirmation'),
        'name'  => 'password_confirmation',
        'type'  => 'password',
        'value' => NULL
])

@else

    @include('admin.helpers.elements.select', [
        'label'     => __('fields.email_verified'),
        'name'      => 'email_verified_at',
        'current'   => !is_null($model->email_verified_at),
        'options'   => [false => __('fields.no_verified'), true => __('fields.verified')]
])

@endif

@include('admin.helpers.elements.select', [
    'label'     => __('fields.roles'),
    'name'      => 'roles[]',
    'current'   => !empty($model) ? $model->roles->pluck('id', 'id')->toArray() : [],
    'options'   => $roles,
    'multiple'  => true
])
