<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * @var Request
     */
    public $request;

    /**
     * @var mixed
     */
    public $model;

    /**
     * Get the pill html for the filter
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPillHtml(Request $request): \Illuminate\Http\JsonResponse
    {
        if (
            $request->has('model') &&
            $request->has('field') &&
            ! empty($field = $request->model::$structure[$request->field])
        ) {
            if ($field['type'] == 'text') {
                return response()->json([
                    'field' => $request->field,
                    'html' => view('components.filters.text-pill', [
                        'label' => $field['label'],
                        'key' => $request->field,
                    ])->render(),
                ]);
            } elseif ($field['type'] == 'dropdown') {
                $slot = "";
                $selected = 'selected';
                foreach ($field['filterable_options'] as $key => $value) {
                    $slot .= "<option value='$key' $selected>$value</option>";
                    $selected = '';
                }
                return response()->json([
                    'field' => $request->field,
                    'html' => view('components.filters.dropdown-pill', [
                        'label' => $field['label'],
                        'key' => $request->field,
                        'slot' => $slot,
                    ])->render(),
                ]);
            }
        }

        return response()->json('error');
    }

    /**
     * Filter the query
     * @param $model
     * @param $query
     * @param $request
     * @return mixed
     */
    public function filter($model, $query, $request): mixed
    {
        $this->request = $request;
        $this->model = $model;

        foreach ($this->model::$structure as $key => $value) {
            if ($this->request->has($key)) {
                $query->where($key, 'like', "%{$this->request->get($key)}%");
            }
        }

        if ($this->request->has('query')) {
            $query = $query->where(function ($subquery) {
                $first = true;
                foreach ($this->model::$structure as $key => $value) {
                    if ($first) {
                        $subquery->where($key, 'like', "%{$this->request->get('query')}%");
                        $first = false;
                    } else {
                        $subquery->orWhere($key, 'like', "%{$this->request->get('query')}%");
                    }
                }
            });
        }

        if ($this->request->has('order') && $this->request->has('order_by')) {
            $query->orderBy($this->request->get('order_by'), $this->request->get('order'));
        }

        return $query;
    }

    /**
     * Creates table configurations if they don't exist
     * @param string $table
     * @param mixed $model
     * @return void
     */
    public function checkTableConfigurations(string $table, mixed $model): void
    {
        $configs = auth()->user()->table_configs;

        if (! array_key_exists('tables', $configs)) {
            $configs['tables'] = [];
        }

        if (! array_key_exists($table, $configs['tables'])) {
            $configs['tables'][$table] = [];
            foreach ($model::$structure as $key => $value) {
                $configs['tables'][$table]['show'][] = $key;
            }
            $configs['tables'][$table]['hide'] = [];
            auth()->user()->configurations = json_encode($configs);
            auth()->user()->save();
        }
    }

    /**
     * Update the table configurations
     * @param Request $request
     * @return void
     */
    public function updateConfigs(Request $request): void
    {
        if ($request->has('table') && $request->has('columns')) {
            $configs = auth()->user()->table_configs;
            $configs['tables'][$request->table]['show'] = array_slice($request->columns, 0, array_search('hidden-columns', $request->columns));
            $configs['tables'][$request->table]['hide'] = array_slice($request->columns, array_search('hidden-columns', $request->columns) + 1);
            auth()->user()->configurations = json_encode($configs);
            auth()->user()->save();
        }
    }
}
