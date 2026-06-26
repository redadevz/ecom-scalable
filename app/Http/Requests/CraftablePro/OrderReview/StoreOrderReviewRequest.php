<?php

namespace App\Http\Requests\CraftablePro\OrderReview;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreOrderReviewRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows("craftable-pro.order-reviews.create");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'order_id' => ['required'],
            'customer_id' => ['nullable'],
            'replied_by' => ['nullable'],
            'rating' => ['nullable', 'boolean'],
            'review' => ['nullable', 'string'],
            'review_time' => ['nullable'],
            'reply' => ['nullable', 'string'],
            'reply_time' => ['nullable'],
            'is_compensated' => ['required', 'boolean'],
            'compensation_value' => ['nullable', 'string'],
            'comments' => ['nullable', 'string'],
            
        ];
    }
}
