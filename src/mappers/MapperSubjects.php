<?php

/**
 *
 */

namespace App\Mappers;

use App\Entities\EntitySubject;

/**
 *
 */
class MapperSubjects extends Mapper
{
  /**
   *
   */
  public function getSubjects()
  {
    $sql = "SELECT id, en, it
            FROM subject_note";
    $stmt = $this->db->query($sql);
    $results = [];
    while($row = $stmt->fetch()) {
      $results[] = new EntitySubject($row);
    }
    return $results;
  }

  /**
   *
   */
  public function getSubjectById($id)
  {
    $sql = "SELECT id, en, it
            FROM subject_note
            WHERE id = :id_sub";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([
      "id_sub" => $id
    ]);
    return new EntitySubject($stmt->fetch());
  }
}
