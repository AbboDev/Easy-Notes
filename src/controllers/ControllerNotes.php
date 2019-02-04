<?php

/**
 *
 */
namespace App\Controllers;

use App\Entities\EntityNote;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use App\Mappers\MapperNotes;
use App\Mappers\MapperSubjects;

/**
 *
 */
class ControllerNotes extends Controller
{
  /**
   *
   */
  public function show(Request $request, Response $response)
  {
    $notes_mapper = new MapperNotes($this->db);
    $notes = $notes_mapper->getNotes();
    $subjects_mapper = new MapperSubjects($this->db);
    $subjects = $subjects_mapper->getSubjects();

    return($this->view->render($response, 'notes.twig', [
      'notes' => $notes, 'subjects' => $subjects, 'session' => $_SESSION
    ]));
  }

  /**
   *
   */
  public function get_filter(Request $request, Response $response)
  {
    $data = $request->getParams();
    $options = array(
      'order' => filter_var($data['order'], FILTER_SANITIZE_STRING),
      'search' => filter_var($data['search'], FILTER_SANITIZE_STRING),
      'sub1' => filter_var($data['sub1'], FILTER_SANITIZE_STRING),
      'sub2' => filter_var($data['sub2'], FILTER_SANITIZE_STRING),
      'sub3' => filter_var($data['sub3'], FILTER_SANITIZE_STRING),
      'insert' => filter_var($data['insert'], FILTER_SANITIZE_STRING),
      'write' => filter_var($data['write'], FILTER_SANITIZE_STRING),
      'from' => filter_var($data['from'], FILTER_SANITIZE_STRING),
      'to' => filter_var($data['to'], FILTER_SANITIZE_STRING)
    );

    $notes_mapper = new MapperNotes($this->db);
    $notes = $notes_mapper->getNotesByParams($options);

    $result = $this->encode_notes($notes);
    $response = $response->withAddedHeader('Content-type', 'application/json; charset=utf-8');
    return $response->getBody()->write($result);
  }

  /**
   *
   */
  public function get_branch(Request $request, Response $response)
  {
    $params = $request->getParams();
    $result = null;
    if (isset($params['parent'])) {
      $parent = filter_var($params['parent'], FILTER_SANITIZE_STRING);
    }

    $note_mapper = new MapperNotes($this->db);
    if (isset($parent)) {
      $notes = $note_mapper->getNotesByParent($parent);
      $result = $this->encode_notes($notes);
    }

    $response = $response->withAddedHeader('Content-type', 'application/json; charset=utf-8');
    return $response->getBody()->write($result);
  }

  /**
   *
   */
  public function get_note(Request $request, Response $response, $args)
  {
    $params = $request->getParams();
    $result = null;
    if (isset($args['id'])) {
      $id = filter_var($args['id'], FILTER_SANITIZE_STRING);
    } else if (isset($params['id'])) {
      $id = filter_var($params['id'], FILTER_SANITIZE_STRING);
    } else if (isset($params['parent'])) {
      $this->get_branch($request, $response);
    }

    $note_mapper = new MapperNotes($this->db);
    if (isset($id)) {
      $note = $note_mapper->getNoteById($id);
      $result = $this->encode_notes([$note]);
    }

    $response = $response->withAddedHeader('Content-type', 'application/json; charset=utf-8');
    return $response->getBody()->write($result);
  }

  /**
   *
   */
  public function get_all(Request $request, Response $response)
  {
    $notes_mapper = new MapperNotes($this->db);
    if (isset($_SERVER['PHP_AUTH_USER'])) {
      $notes = $notes_mapper->getAllNotes($_SERVER['PHP_AUTH_USER']);
    } else {
      $notes = $notes_mapper->getAllNotes();
    }

    $result = $this->encode_notes($notes);
    $response = $response->withAddedHeader('Content-type', 'application/json; charset=utf-8');
    return $response->getBody()->write($result);
  }

  /**
   *
   */
  private function encode_notes($notes)
  {
    $send_notes = [];
    if (is_array($notes)) {
      foreach ($notes as $note) {
        if (isset($note)) {
          $send_notes[] = json_encode(array(
            "id" => $note->getID(),
            "title" => $note->getTitle(),
            "description" => $note->getDescription(),
            "write_date" => $note->getWriteDate(),
            "insert_date" => $note->getInsertDate(),
            "subject" => $note->getSubject(),
            "user" => $note->getUser()
          ));
        }
      }
    }
    $send_notes = json_encode($send_notes);
    return $send_notes;
  }

  /**
   *
   */
  public function add_new(Request $request, Response $response, $args)
  {
    $note = "";

    $subjects_mapper = new MapperSubjects($this->db);
    $subjects = $subjects_mapper->getSubjects();

    if (isset($args['id'])) {
      $parent_id = $args['id'];
      $note_mapper = new MapperNotes($this->db);
      $note = $note_mapper->getNoteById($parent_id);
    }
    return($this->view->render($response, 'addNote.twig', [
      'subjects' => $subjects, 'session' => $_SESSION, 'parent' => $note
    ]));
  }

  /**
   *
   */
  public function add_new_by_subject(Request $request, Response $response, $args)
  {
    $subject = "";
    if (isset($args['subject'])) {
      $subject = $args['subject'];
    }

    $subjects_mapper = new MapperSubjects($this->db);
    $subjects = $subjects_mapper->getSubjects();

    return($this->view->render($response, 'addNote.twig', [
      'subjects' => $subjects, 'session' => $_SESSION, 'sub' => $subject
    ]));
  }

  /**
   * Insert a new Note
   */
  public function send_new(Request $request, Response $response)
  {
    $data = $request->getParsedBody();
    $params = array(
      'title' => filter_var($data['title'], FILTER_SANITIZE_STRING),
      'subject' => filter_var($data['subject'], FILTER_SANITIZE_STRING),
      'description' => filter_var($data['note'], FILTER_SANITIZE_STRING),
      'write_date' => filter_var($data['date'], FILTER_SANITIZE_STRING),
      'insert_date' => date('Y-m-d', time()),
      'user' => $_SESSION['user'],
      'parent' => filter_var($data['parent'], FILTER_SANITIZE_STRING)
    );

    $note = new EntityNote($params);
    $note_mapper = new MapperNotes($this->db);
    $note_mapper->save($note);

    $response = $response->withRedirect($this->router->pathFor('notes'));
    return $response;
  }

  /**
   * Show the selected Note
   */
  public function show_one(Request $request, Response $response, $args)
  {
    $id = $args['id'];

    $notes_mapper = new MapperNotes($this->db);
    $note = $notes_mapper->getNoteById($id);

    return($this->view->render($response, 'showNote.twig', [
      'note' => $note, 'session' => $_SESSION
    ]));
  }

  /**
   * Delete a Note from
   */
  public function delete(Request $request, Response $response, $args)
  {
    $id = $args['id'];

    $notes_mapper = new MapperNotes($this->db);
    $notes_mapper->delete($id);

    $response = $response->withRedirect($this->router->pathFor('notes'));
    return $response;
  }

  /**
   * Show the addNote page with fields values set with the selected Note
   */
  public function modify(Request $request, Response $response, $args)
  {
    $id = $args['id'];

    $subjects_mapper = new MapperSubjects($this->db);
    $subjects = $subjects_mapper->getSubjects();
    $note_mapper = new MapperNotes($this->db);
    $note = $note_mapper->getNoteById($id);

    return($this->view->render($response, 'addNote.twig', [
      'subjects' => $subjects, 'session' => $_SESSION, 'note' => $note
    ]));
  }

  /**
   * Modify a Note
   */
  public function update(Request $request, Response $response, $args)
  {
    $id = $args['id'];
    $data = $request->getParsedBody();
    $params = array(
      'title' => filter_var($data['title'], FILTER_SANITIZE_STRING),
      'subject'=> filter_var($data['subject'], FILTER_SANITIZE_STRING),
      'description' => filter_var($data['note'], FILTER_SANITIZE_STRING),
      'write_date' => filter_var($data['date'], FILTER_SANITIZE_STRING),
      'insert_date' => date('Y-m-d', time())
    );

    $note_mapper = new MapperNotes($this->db);
    $note_mapper->modify($params, $id);

    $response = $response->withRedirect($this->router->pathFor('notes'));
    return $response;
  }

  /**
   * Check the insert title was already inserted
   */
  public function check(Request $request, Response $response)
  {
    $data = $request->getParsedBody();
    $title = filter_var($data['title'], FILTER_SANITIZE_STRING);

    $notes_mapper = new MapperNotes($this->db);
    $notes = $notes_mapper->getNotesByTitle($title);

    if (isset($notes)) {
      $response = $response->getBody()->write(1);
      return $response;
    }
    $response = $response->getBody()->write(0);
    return $response;
  }
}
