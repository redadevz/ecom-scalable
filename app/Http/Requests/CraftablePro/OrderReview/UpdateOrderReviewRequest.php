<?php

namespace App\Http\Requests\CraftablePro\OrderReview;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateOrderReviewRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows("craftable-pro.order-reviews.edit");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'order_id' => ['sometimes'],
            'customer_id' => ['nullable'],
            'replied_by' => ['nullable'],
            'rating' => ['nullable', 'boolean'],
            'review' => ['nullable', 'string'],
            'review_time' => ['nullable'],
            'reply' => ['nullable', 'string'],
            'reply_time' => ['nullable'],
            'is_compensated' => ['sometimes', 'boolean'],
            'compensation_value' => ['nullable', 'string'],
            'comments' => ['nullable', 'string'],
            
        ];
    }
}
