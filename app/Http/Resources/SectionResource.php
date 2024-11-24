<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        if($this->type == 'exam') {

            return [
                'id'   => $this->id,
                'name' => $this->name,
                'time' => $this->time,
                'mark' => $this->exam_mark,
                'type' => $this->type,

                $this->questions->map(function ($question) {

                    return [
    
                        'question_name' => $question->name,
    
                        $question->chooses->map(function ($choose) {
                            
                            return [
                                'choose_id' => $choose->id,
                                'choose_name' => $choose->name
                            ];
    
                        }),
    
                    ];
    
                }),

            ];

        } else {

            return [
                'id'   => $this->id,
                'name' => $this->name,
                'link' => $this->link,
                'type' => $this->type
            ];

        }

    }
}
