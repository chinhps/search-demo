<?php

namespace App\Http\Requests\v1\Measurement;

use App\Http\Requests\BaseRequest;

class MeasureRegisterRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
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
        return [
            "url_target"        => ["required", "url"],
            "keywords"          => ["required", "array", "max:5"],
            "keywords.*"        => ["required", "string"]
        ];
    }

    public function messages(): array
    {
        return [
            "url_target.required"   => "Bạn cần phải nhập URL!",
            "url_target.url"        => "URL chưa đúng định dạng! Kiểm tra lại!",
            "keywords.required"     => "Bạn cần phải nhập keywords",
            "keywords.max"          => "Bạn chỉ có thể nhập tối đa 5 keywords",
            "keywords.*.string"     => "Keyword của bạn phải là 1 chuỗi",
            "keywords.*.required"   => "Có dòng keyword không có chữ"
        ];
    }
}
