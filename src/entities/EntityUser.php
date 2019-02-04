<?php
namespace App\Entities;

class EntityUser {
  protected $nickname;
  protected $first_name;
  protected $last_name;
  protected $email;
  protected $password;
  protected $spam;
  protected $color;

  /**
   * Accept an array of data matching properties of this class
   * and create the class
   *
   * @param array $data The data to use to create
   */
  public function __construct(array $data) {
    $this->nickname = $data['nickname'];
    $this->first_name = $data['first_name'];
    $this->last_name = $data['last_name'];
    $this->email = $data['email'];
    $this->password = $data['password'];
    $this->spam = $data['spam'];
    $this->color = $data['color'];
  }
/*Get methods******************************************************************/
  public function getNickname() {
    return $this->nickname;
  }
  public function getFirstName() {
    return $this->first_name;
  }
  public function getLastName() {
    return $this->last_name;
  }
  public function getEmail() {
    return $this->email;
  }
  public function getPassword() {
    return $this->password;
  }
  public function getSpam() {
    return $this->spam;
  }
  public function getColor() {
    return $this->color;
  }
}
