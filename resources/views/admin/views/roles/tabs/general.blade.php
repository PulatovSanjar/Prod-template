@include('admin.helpers.elements.text', [
    'label'     => __('fields.title'),
    'name'      => 'title',
    'value'     => $model->title ?? NULL
])

@include('admin.helpers.elements.text', [
    'label'     => __('fields.key'),
    'name'      => 'key',
    'value'     => $model->key ?? NULL
])

@include('admin.helpers.elements.select', [
    'label'     => __('fields.permissions'),
    'name'      => 'permissions[]',
    'current'   => !empty($model) ? $model->permissions->pluck('id', 'id')->toArray() : [],
    'options'   => $permissions,
    'multiple'  => true
])
