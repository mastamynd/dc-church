<?php

namespace Modules\Demarcations\Http\Requests;

use App\Abstracts\Http\FormRequest;

class Bank extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $company_id = $this->request->get('company_id', company_id());
        $id = null;

        if ($this->getMethod() == 'PATCH' && $this->route('bank')) {
            $id = $this->route('bank')->id;
        }

        return [
            'name' => 'required|string|max:191',
            'code' => 'required|string|max:50|unique:banks,code,' . $id . ',id,company_id,' . $company_id . ',deleted_at,NULL',
            'enabled' => 'integer|boolean',
        ];
    }
}
