<?php
namespace App\Entities;

class EntityNote {
  protected $id;
  protected $title;
  protected $description;
  protected $strdescription;
  protected $write_date;
  protected $insert_date;
  protected $subject;
  protected $user;
  protected $parent;

  /**
   * Accept an array of data matching properties of this class
   * and create the class
   *
   * @param array $data The data to use to create
   */
  public function __construct(array $data) {
    $this->id = $data['id'];
    $this->title = $data['title'];
    $this->write_date = $data['write_date'];
    $this->insert_date = $data['insert_date'];
    $this->subject = $data['subject'];
    $this->user = $data['user'];
    $this->parent = $data['parent'];
    $this->strdescription = $data['description'];
    //the description will be split in an array
    //divided for each new line
    $desc = str_replace("\r", "", $data['description']);
    $desc = explode("\n", $desc);
    $this->description = $desc;
  }
/*Get methods******************************************************************/
  public function getId() {
    return $this->id;
  }
  public function getTitle() {
    return $this->title;
  }
  public function getDescription() {
    return $this->description;
  }
  public function getStringDescription() {
    return $this->strdescription;
  }
  public function getWriteDate() {
    return $this->write_date;
  }
  public function getInsertDate() {
    return $this->insert_date;
  }
  public function getSubject() {
    return $this->subject;
  }
  public function getUser() {
    return $this->user;
  }
  public function getParent() {
    return $this->parent;
  }
}
