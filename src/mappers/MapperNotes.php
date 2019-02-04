<?php

/**
 *
 */

namespace App\Mappers;

use App\Entities\EntityNote;

/**
 *
 */
class MapperNotes extends Mapper
{
  /**
   *
   */
  public function getNotes($nick = null)
  {
    $user = $this->get_nick($nick);
    $sql = "SELECT n.id, n.title, n.description, n.write_date, n.insert_date,
              s.en AS subject, n.user_nick AS user, n.parent
            FROM note n, subject_note s
            WHERE s.id = n.subject
              AND n.parent IS NULL
              $user";
    $stmt = $this->db->query($sql);
    $results = [];
    while($row = $stmt->fetch()) {
      $results[] = new EntityNote($row);
    }
    return $results;
  }

  /**
   *
   */
  public function getAllNotes($nick = null)
  {
    $user = $this->get_nick($nick);
    $sql = "SELECT n.id, n.title, n.description, n.write_date, n.insert_date,
              s.en AS subject, n.user_nick AS user, n.parent
            FROM note n, subject_note s
            WHERE n.subject = s.id
              $user
            ORDER BY n.id";
    $stmt = $this->db->query($sql);
    $results = [];
    while($row = $stmt->fetch()) {
      $results[] = new EntityNote($row);
    }
    return $results;
  }

  /**
   * Return an Note by its ID
   *
   * @param int $id       The Note ID
   * @return EntityNote   The Note
   */
  public function getNoteById($id, $nick = null)
  {
    $user = $this->get_nick($nick);
    $sql = "SELECT n.id, n.title, n.description, n.write_date, n.insert_date,
              s.en AS subject, n.user_nick AS user, n.parent
            FROM note n, subject_note s
            WHERE s.id = n.subject
              AND n.id = :id_note
              $user";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([
      "id_note" => $id
    ]);
    $result = $stmt->fetch(\PDO::FETCH_ASSOC);
    if ($result) {
      return new EntityNote($result);
    }
    return null;
  }

  /**
   *
   */
  public function getNotesByTitle($title, $nick = null)
  {
    $user = $this->get_nick($nick);
    $sql = "SELECT n.id, n.title, n.description, n.write_date, n.insert_date,
          s.en AS subject, n.user_nick AS user, n.parent
        FROM note n, subject_note s
        WHERE s.id = n.subject
          AND n.title = '$title'
          $user";
    $stmt = $this->db->query($sql);
    $results = [];
    while($row = $stmt->fetch()) {
      $results[] = new EntityNote($row);
    }
    return $results;
  }

  /**
   *
   */
  public function getNotesByParent($parent, $nick = null)
  {
    $user = $this->get_nick($nick);
    $parent = "AND n.parent = \"".$parent."\"";
    $sql = "SELECT n.id, n.title, n.description, n.write_date, n.insert_date,
              s.en AS subject, n.user_nick AS user, n.parent
            FROM note n, subject_note s
            WHERE s.id = n.subject
              $parent
              $user";
    $stmt = $this->db->query($sql);
    $results = [];
    while($row = $stmt->fetch()) {
      $results[] = new EntityNote($row);
    }
    return $results;
  }

  /**
   *
   */
  public function getNotesByParams(array $params, $nick = null)
  {
    $user = $this->get_nick($nick);
    $where_sub = "n.subject = ";
    $where_w_from = "n.write_date >= \"".$params["from"]."\"";
    $where_w_to = "n.write_date <= \"".$params["to"]."\"";
    $where_i_from = "n.insert_date >= \"".$params["from"]."\"";
    $where_i_to = "n.insert_date <= \"".$params["to"]."\"";
    $where = "";

    if ($params['sub3'] != "0" || $params['sub3'] != 0) {
      $where .= (" AND ($where_sub".$params["sub1"]." OR $where_sub".$params["sub2"]." OR $where_sub".$params["sub3"].")");
    } else if ($params['sub2'] != "0" || $params['sub2'] != 0) {
      $where .= (" AND ($where_sub".$params["sub1"]." OR $where_sub".$params["sub2"].")");
    } else if ($params['sub1'] != "0" || $params['sub1'] != 0) {
      $where .= (" AND $where_sub".$params["sub1"]);
    }
    if ($params["write"] != "false") {
      if ($params['from'] != "") {
        $where .= " AND $where_w_from";
      }
      if ($params['to'] != "") {
        $where .= " AND $where_w_to";
      }
    }
    if ($params["insert"] != "false") {
      if ($params['from'] != "") {
        $where .= " AND $where_i_from";
      }
      if ($params['to'] != "") {
        $where .= " AND $where_i_to";
      }
    }
    if ($params["search"] != "") {
      $where .= " AND n.title LIKE '%".$params["search"]."%'";
    }
    $sql = "SELECT n.id, n.title, n.description, n.write_date, n.insert_date,
              s.en AS subject, n.user_nick AS user, n.parent
            FROM note n, subject_note s
            WHERE s.id = n.subject
              $user
              $where
            ORDER BY n.id ".$params['order'];
    $stmt = $this->db->query($sql);
    $results = [];
    while($row = $stmt->fetch()) {
      $results[] = new EntityNote($row);
    }
    return $results;
  }

  /**
   *
   */
  public function save(EntityNote $note)
  {
    if ($note->getParent() != null) {
      $sql = "INSERT INTO note
                (title, description, write_date, insert_date, user_nick, subject, parent) VALUES
                (:title, :description, :write_date, :insert_date, :user_nick, :subject, :parent);";
      $stmt = $this->db->prepare($sql);
      $result = $stmt->execute([
        "title" => $note->getTitle(),
        "description" => $note->getStringDescription(),
        "write_date" => $note->getWriteDate(),
        "insert_date" => $note->getInsertDate(),
        "subject" => $note->getSubject(),
        "user_nick" => $note->getUser(),
        "parent" => $note->getParent()
      ]);
    } else {
      $sql = "INSERT INTO note
                (title, description, write_date, insert_date, user_nick, subject) VALUES
                (:title, :description, :write_date, :insert_date, :user_nick, :subject);";
      $stmt = $this->db->prepare($sql);
      $result = $stmt->execute([
        "title" => $note->getTitle(),
        "description" => $note->getStringDescription(),
        "write_date" => $note->getWriteDate(),
        "insert_date" => $note->getInsertDate(),
        "subject" => $note->getSubject(),
        "user_nick" => $note->getUser()
      ]);
    }
    if(!$result) {
      throw new \Exception("Impossible to insert the Note");
    }
  }

  /**
   *
   */
  public function delete($id)
  {
    $sql = "DELETE
            FROM note
            WHERE note.id = :id";
    $stmt = $this->db->prepare($sql);
    $result = $stmt->execute([
      "id" => $id
    ]);
    if(!$result) {
      throw new \Exception("Impossible to remove the Note");
    }
  }

  /**
   *
   */
  public function modify(array $mod, $id)
  {
    $sql = "UPDATE note
            SET title = :title,
              description = :description,
              write_date = :write_date,
              insert_date = :insert_date/*,
              subject = :subject*/
            WHERE note.id = :id";
    $stmt = $this->db->prepare($sql);
    $result = $stmt->execute([
      "id" => $id,
      "title" => $mod['title'],
      "description" => $mod['description'],
      "write_date" => $mod['write_date'],
      "insert_date" => $mod['insert_date']/*,
      "subject" => $mod['subject']*/
    ]);
    if(!$result) {
      throw new \Exception("Impossible to modify the Note");
    }
  }

  /**
   *
   */
  private function get_nick($nick)
  {
    if (isset($nick)) {
      $user = " AND n.user_nick = \"".$nick."\"";
    } else if (isset($_SESSION['user'])) {
      $user = " AND n.user_nick = \"".$_SESSION['user']."\"";
    } else if (isset($_SERVER['PHP_AUTH_USER'])) {
      $user = " AND n.user_nick = \"".$_SERVER['PHP_AUTH_USER']."\"";
    } else {
      $user = "";
    }
    return $user;
  }
}
