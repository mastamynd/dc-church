<?php

namespace Modules\Church\Http\Requests;

use App\Abstracts\Http\FormRequest;

class UpdatePastorRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        
        $this->request->set('created_from','core::ui');
        $this->request->set('locale', setting('default.locale', 'en-GB'));
        $this->request->set('landing_page','dashboard');
        $this->request->set('type','pastor');
        $this->request->set('company_id',company_id());
        $this->request->set('currency_code',setting('default.currency'));
        if(!$this->request->has('email')){
            $this->request->has('email','pastor@delivarancechurch.international');
        }
        
        return [''];
    }
}
