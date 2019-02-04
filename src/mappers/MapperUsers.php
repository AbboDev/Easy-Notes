<?php

/**
 *
 */
namespace App\Mappers;

use App\Entities\EntityUser;

/**
 *
 */
class MapperUsers extends Mapper
{
  /**
   *
   */
  public function getUsers()
  {
    $sql = "SELECT u.nickname, u.first_name, u.last_name, u.email, u.password,
              u.send_notification AS spam, u.color
            FROM log_user u";
    $stmt = $this->db->query($sql);

    $results = [];
    while($row = $stmt->fetch()) {
      $results[] = new EntityUser($row);
    }
    return $results;
  }

  /**
   * Return an User by its nickname or email
   *
   * @param string $nickname      The User nickname
   * @param string $password      The User password
   * @return EntityUser           The Note
   */
  public function getUserByCredential($nickname, $password = null)
  {
    $where_pwd = "";
    if ($password != null) {
      $where_pwd = "AND u.password = :pwd";
    }
    $sql = "SELECT u.nickname, u.first_name, u.last_name, u.email, u.password,
              u.send_notification AS spam, u.color
            FROM log_user u
            WHERE (u.email = :nick OR u.nickname = :nick)
            $where_pwd";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([
      "nick" => $nickname, "pwd" => $password
    ]);
    $result = $stmt->fetch(\PDO::FETCH_ASSOC);

    if ($result) {
      return new EntityUser($result);
    }
    return null;
  }

  /**
   *
   */
  public function save(EntityUser $user)
  {
      $sql = "INSERT INTO log_user
                (nickname, first_name, last_name, email, password, send_notification, color) VALUES
                (:nickname, :fname, :lname, :email, :pwd, :spam, :color);";

      $stmt = $this->db->prepare($sql);
      $result = $stmt->execute([
        "nickname" => $user->getNickname(),
        "fname" => $user->getFirstName(),
        "lname" => $user->getLastName(),
        "email" => $user->getEmail(),
        "pwd" => $user->getPassword(),
        "spam" => $user->getSpam(),
        "color" => "cyano"
      ]);
      if(!$result) {
        throw new \Exception("Impossible to insert the user");
      }
  }

  /**
   *
   */
  public function modify(array $mod)
  {
    $sql = "UPDATE log_user
            SET first_name = :name,
              last_name = :surname,
              email = :email,
              send_notification = :spam,
              color = :color
            WHERE log_user.nickname = :nickname";
    $stmt = $this->db->prepare($sql);
    $result = $stmt->execute([
      "name" => $mod['name'],
      "surname" => $mod['surname'],
      "email" => $mod['email'],
      "spam" => $mod['spam'],
      "color" => $mod['color'],
      "nickname" => $_SESSION['user']
    ]);
    if(!$result) {
      throw new \Exception("Impossible to modify the Note");
    }
  }

  /**
   *
   */
  public function modify_password(array $mod)
  {
    $sql = "UPDATE log_user
            SET first_name = :name,
              last_name = :surname,
              email = :email,
              password = :pwd,
              send_notification = :spam,
              color = :color
            WHERE log_user.nickname = :nickname";
    $stmt = $this->db->prepare($sql);
    $result = $stmt->execute([
      "name" => $mod['name'],
      "surname" => $mod['surname'],
      "email" => $mod['email'],
      "pwd" => $mod['pwd'],
      "spam" => $mod['spam'],
      "color" => $mod['color'],
      "nickname" => $_SESSION['user']
    ]);
    if(!$result) {
      throw new \Exception("Impossible to modify the Note");
    }
  }
}
