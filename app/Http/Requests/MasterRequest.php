<?php
// MasterRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class MasterRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

     protected function prepareForValidation(): void
    {
        $table = $this->segment(3);
        $id = $this->segment(4);

        $allowedTables = ['genres', 'studios', 'licensors', 'producers', 'sources'];

        if (!in_array($table, $allowedTables)) {
            abort(400, "Invalid master type: $table");
        }

        if ($id) {
            $exists = DB::table($table)->where('id', $id)->exists();

            if (!$exists) {
                abort(404, "Data not found for ID: $id");
            }
        }
    }

    public function rules(): array
    {
        $table = $this->segment(3);

        $id = $this->segment(4);

        $allowedTables = ['genres', 'studios', 'licensors', 'producers', 'sources'];
        if (!in_array($table, $allowedTables)) {
            abort(400, "Invalid master type: $table");
        }

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                $id
                ? Rule::unique($table, 'name')->ignore($id)
                : Rule::unique($table, 'name'),
            ],
        ];
    }
}