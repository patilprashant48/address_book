<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest {
  public function authorize(): bool { return true; }
  public function rules(): array {
    return [
      'name'=>'required|string|max:255',
      'email'=>'nullable|email',
      'phone'=>'nullable|string|max:50',
      'company'=>'nullable|string|max:255',
      'groups'=>'array',
      'groups.*'=>'string'
    ];
  }
}