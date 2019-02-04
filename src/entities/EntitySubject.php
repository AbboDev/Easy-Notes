<?php
namespace App\Entities;

class EntitySubject {
  protected $id;
  protected $en;
  protected $it;

  /**
   * Accept an array of data matching properties of this class
   * and create the class
   *
   * @param array $data The data to use to create
   */
  public function __construct(array $data) {
    $this->id = $data['id'];
    $this->en = $data['en'];
    $this->it = $data['it'];
  }
/*Get methods******************************************************************/
  public function getId() {
    return $this->id;
  }
  public function getIt() {
    return $this->it;
  }
  public function getEn() {
    return $this->en;
  }
}
