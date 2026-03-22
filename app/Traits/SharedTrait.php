<?php

namespace App\Traits;

use App\Models\ClassRoom;
use App\Models\Folder;
use App\Models\Homework;
use App\Models\InstituteSubject;
use App\Models\Teacher;
use phpDocumentor\Reflection\Types\Collection;

trait SharedTrait
{
    public function findClassRoom(int $classId, int $SectionId)
    {
        $classroom = ClassRoom::where([
            'institute_class_id' => $classId,
            'institute_section_id' => $SectionId
        ])->first();

        return $classroom ?? false;

    }

}
