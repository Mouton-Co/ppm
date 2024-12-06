<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Project;
use App\Models\Part;
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
     * @var array
     */
    public $structure;

    /**
     * @var string
     */
    public $route;

    /**
     * Get the pill html for the filter
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPillHtml(Request $request): \Illuminate\Http\JsonResponse
    {
        $structure = json_decode($request->structure, true);

        if (
            $request->has('model') &&
            $request->has('field') &&
            ! empty($field = $structure[$request->field])
        ) {
            if ($field['type'] == 'text') {
                $component = $this->renderTextPill($request, $field);
            } elseif ($field['type'] == 'dropdown') {
                $component = $this->renderDropdownPill($request, $field);
            } elseif ($field['type'] == 'boolean') {
                $component = $this->renderBooleanPill($request, $field);
            } elseif ($field['type'] == 'date') {
                $component = $this->renderDatePill($request, $field);
            }

            return response()->json($component);
        }

        return response()->json('error');
    }

    /**
     * Get the pill html for the filter
     * @param Request $request
     * @param array $field
     * @return array
     */
    protected function renderDropdownPill($request, $field): array
    {
        $slot = "";
        $selected = 'selected';

        if (! empty($field['relationship'])) {
            $relationshipModel = $field['relationship_model'];
            $relationshipField = explode('.', $field['relationship'])[1];
            $options = $relationshipModel::orderBy($relationshipField)->get();
            foreach ($options as $option) {
                $slot .= "<option value='{$option->$relationshipField}' $selected>{$option->$relationshipField}</option>";
                $selected = '';
            }
        } else {
            if ($field['filterable_options'] == 'custom') {
                $customKey = \Str::camel("get_custom_{$request->field}_attribute");
                $options =  $request->model::$customKey();
            } else {
                $options = $field['filterable_options'];
            }
    
            foreach ($options as $key => $value) {
                $slot .= "<option value='$key' $selected>$value</option>";
                $selected = '';
            }
        }


        return [
            'field' => $request->field,
            'html' => view('components.filters.dropdown-pill', [
                'label' => $field['label'],
                'key' => $request->field,
                'slot' => $slot,
            ])->render(),
        ];
    }

    /**
     * Get the pill html for the filter
     * @param Request $request
     * @param array $field
     * @return array
     */
    protected function renderBooleanPill($request, $field): array
    {
        return [
            'field' => $request->field,
            'html' => view('components.filters.dropdown-pill', [
                'label' => $field['label'],
                'key' => $request->field,
                'slot' => "<option value='0'>No</option><option value='1'>Yes</option>",
            ])->render(),
        ];
    }

    /**
     * Get the pill html for the filter
     * @param Request $request
     * @param array $field
     * @return array
     */
    protected function renderTextPill($request, $field): array
    {
        return [
            'field' => $request->field,
            'html' => view('components.filters.text-pill', [
                'label' => $field['label'],
                'key' => $request->field,
            ])->render(),
        ];
    }

    /**
     * Get the pill html for the filter
     * @param Request $request
     * @param array $field
     * @return array
     */
    public function renderDatePill($request, $field): array
    {
        return [
            'field' => $request->field,
            'html' => view('components.filters.date-pill', [
                'label' => $field['label'],
                'key' => $request->field,
            ])->render(),
        ];
    }

    /**
     * Filter the query
     * @param $model
     * @param $query
     * @param $request
     * @return mixed
     */
    public function filter($model, $query, $request, $structure = null): mixed
    {
        $this->request = $request;
        $this->model = $model;
        $this->structure = $structure ?? $model::$structure;

        // show archived
        if ($this->request->has('archived') && $this->request->get('archived') == 'true'){
            $query = $query->onlyTrashed();
        }

        // filter params
        foreach ($this->structure as $key => $value) {
            if ($this->request->has($key)) {
            
                /**
                 * if model is Project and field is status and value is 'all except closed'
                 * continue and do manually in the controller
                 */
                if ($this->model == Project::class && $key == 'status' && $this->request->get('status') == 'All except closed') {
                    continue;
                }

                /**
                 * if model is PurchaseOrder and field is status and value is 'all except ordered'
                 * continue and do manually in the controller
                 */
                if ($this->model == Order::class && $key == 'status' && $this->request->get('status') == 'All except ordered') {
                    continue;
                }

                /**
                 * if model is Part and field is due_days and
                 * continue and do manually in the controller
                 */
                if (
                    $this->model == Part::class && $key == 'due_days' ||
                    $this->model == Part::class && $key == 'machine_number' ||
                    $this->model == Part::class && $key == 'unit_number'
                ) {
                    continue;
                }

                if (! empty($this->structure[$key]['relationship'])) {
                    $query->whereRelation(
                        explode('.', $this->structure[$key]['relationship'])[0],
                        explode('.', $this->structure[$key]['relationship'])[1],
                        'like',
                        "%{$this->request->get($key)}%"
                    );
                } else {
                    $query->where($key, 'like', "%{$this->request->get($key)}%");
                }
            }
        }

        // query
        if ($this->request->has('query')) {
            $query = $query->where(function ($subquery) {
                foreach ($this->structure as $key => $value) {
                    if (!empty($value['filterable']) && $value['filterable'] && array_key_exists($key, $this->model::first()->getAttributes())) {
                        if (! empty($this->structure[$key]['relationship'])) {
                            $subquery->orWhereRelation(
                                explode('.', $this->structure[$key]['relationship'])[0],
                                explode('.', $this->structure[$key]['relationship'])[1],
                                'like',
                                "%{$this->request->get('query')}%"
                            );
                        } else {
                            $subquery->orWhere($key, 'like', "%{$this->request->get('query')}%");
                        }
                    }
                }
            });
        }

        // order by
        if (
            $this->request->has('order') &&
            $this->request->has('order_by') &&
            array_key_exists($this->request->get('order_by'), $this->structure) &&
            $this->structure[$this->request->get('order_by')]['sortable'] &&
            array_key_exists($this->request->get('order_by'), $this->model::first()?->getAttributes() ?? [])
        ) {
            $order = $this->request->get('order') == 'asc' ? 'asc' : 'desc';
            $orderBy = $this->request->get('order_by');
            
            if (! empty($this->structure[$orderBy]['relationship'])) {
                $model = $this->structure[$orderBy]['relationship_model'];
                $table = app($model)->getTable();
                list($relationship, $field) = explode('.', $this->structure[$orderBy]['relationship']);
                $query->orderBy(
                    $model::select($field)
                        ->whereColumn("{$relationship}_id", "{$table}.id")
                        ->orderBy($field, $order)
                        ->limit(1),
                    $order
                );
            } else {
                $query->orderBy($orderBy, $order);
            }
        }

        return $query;
    }

    /**
     * Creates table configurations if they don't exist
     * @param string $table
     * @param mixed $model
     * @return void
     */
    public function checkTableConfigurations(string $table, mixed $model, $structure = null): void
    {
        $structure = $structure ?? $model::$structure;
        $configs = auth()->user()->table_configs;

        if (! array_key_exists('tables', $configs)) {
            $configs['tables'] = [];
        }

        if (! array_key_exists($table, $configs['tables'])) {
            $configs['tables'][$table] = [];
            foreach ($structure as $key => $value) {
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

    /**
     * Restore the specified resource from storage.
     */
    public function restore(Request $request, string $id)
    {
        if ($request->user()->cannot('restore', Project::class)) {
            abort(403);
        }

        $datum = $this->model::withTrashed()->find($id);

        if (empty($datum)) {
            return redirect()->route("{$this->route}.index");
        }
        
        $datum->restore();

        return redirect()->route("{$this->route}.index", ['archived' => "true"])->with([
            'success' => "Item has been restored",
        ]);
    }

    /**
     * Trash the specified resource from storage.
     */
    public function trash(Request $request, string $id)
    {
        if ($request->user()->cannot('forceDelete', Project::class)) {
            abort(403);
        }

        $datum = $this->model::withTrashed()->find($id);

        if (empty($datum)) {
            return redirect()->route("{$this->route}.index");
        }
        
        $datum->forceDelete();

        return redirect()->route("{$this->route}.index", ['archived' => "true"])->with([
            'success' => "Item has been permanently deleted",
        ]);
    }
}
