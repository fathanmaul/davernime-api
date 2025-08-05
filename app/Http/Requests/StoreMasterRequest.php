<?php
// MasterRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class StoreMasterRequest extends FormRequest
{

    protected $modelClass;

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        $controller = $this->route()->getController();

        $this->modelClass = $controller->modelClass;

        $table = (new $this->modelClass)->getTable();
        
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique($table, 'name')
            ],
        ];
    }
}