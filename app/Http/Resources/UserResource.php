<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
          'id' => $this->id,  
          'first_name' => $this->first_name,  
          'last_name' => $this->last_name,  
          'student_phone_number' => $this->student_phone_number,  
          'parent_phone_number' => $this->parent_phone_number,  
          'gender' => $this->gender,  
          'card_photo' => $this->card_photo,  
          'email' => $this->email,  
          'wallet' => $this->wallet,  
          'year_name' => $this->year->name,  
          'division_name' => $this->division->name,  
          'governorate_name' => $this->governorate->name,  
        ];
    }
}
