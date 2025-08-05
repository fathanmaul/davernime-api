<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMasterRequest extends FormRequest
{
    protected $modelClass;

    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $controller = $this->route()->getController();
        $this->modelClass = $controller->modelClass;
        $table = (new $this->modelClass)->getTable();

        $paramName = \Illuminate\Support\Str::singular($table);
        $modelInstance = $this->route($paramName);
        $id = $modelInstance instanceof \Illuminate\Database\Eloquent\Model
            ? $modelInstance->getKey()
            : $modelInstance;

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique($table, 'name')->ignore($id)
            ]
        ];
        // tapi ketika melakukan update dengan name yang sama, tetap name already taken
    }
}
