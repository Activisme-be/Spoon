<?php

namespace App\Http\Requests\Alerts;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class SystemNotificationRequest
 *
 * @package App\Http\Requests\Alerts
 */
class SystemNotificationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return ['title' => ['required', 'string', 'max:255'], 'message' => ['required', 'string']];
    }
}
