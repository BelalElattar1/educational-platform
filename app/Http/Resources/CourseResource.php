<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        if($this->additional){

            return [
            
                'id'          => $this->id,
                'title'       => $this->title,
                'description' => $this->description,
                'image'       => $this->image,
                'price'       => $this->price,
                'Year_name'   => $this->year->name,

                $this->categories->map(function ($category) {

                    return [
    
                        'category_id' => $category->id,
                        'category_name' => $category->name,
                        'category_title' => $category->title,
    
                        $category->sections->map(function ($section) {
                            
                            return [
                                'section_id'   => $section->id,
                                'section_name' => $section->name,
                                'section_type' => $section->type
                            ];
    
                        }),
    
                    ];
    
                }),

            ];

        } else {

            return [
            
                'id'          => $this->id,
                'title'       => $this->title,
                'description' => $this->description,
                'image'       => $this->image,
                'price'       => $this->price,
                'Year_name'   => $this->year->name
            ];

        }

    }

}
