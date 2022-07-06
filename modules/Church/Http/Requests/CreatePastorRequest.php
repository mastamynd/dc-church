<?php

namespace Modules\Church\Http\Requests;

use App\Abstracts\Http\FormRequest;

class CreatePastorRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $user = \auth()->user()->id;

        if(!($this->request->get('id', 0) > 0)){
            $this->request->set('created_by', $user);
        }
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
