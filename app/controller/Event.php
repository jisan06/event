<?php

namespace App\Controller;

use App\Helper;
use App\Model\EventModel;

class Event
{
    /**
     * @var EventModel
     */
    private $eventDB;

    public function __construct()
    {
        $this->eventDB = new EventModel();
    }

    public function index()
    {
        $events = $this->eventDB->all();
        include Helper::view('event/index.php');
    }

    public function create()
    {
        include Helper::view('event/create.php');
    }
    public function store()
    {
        session_start();
        $errors = [];
        if (empty($_POST['name'])) {
            $errors[] = 'Event name is required.';
        } else {
            if ($this->eventDB->uniqueName($_POST['name'])) {
                $errors[] = 'Event name is already exist.';
            }
        }
        if (empty($_POST['location'])) {
            $errors[] = 'Location is required.';
        }
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header("Location: /events/create");
            exit;
        }

        $this->eventDB->create($_POST);
        $_SESSION['success'] = 'Event created successfully.';
        header("Location: /events");
        exit;
    }

    public function edit()
    {
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            http_response_code(400); // Bad Request
            echo "Invalid Event ID.";
            return;
        }
        $id = $_GET['id'];

        $event = $this->eventDB->find($id);
        include Helper::view('event/edit.php');
    }

    public function update($id)
    {
        session_start();

        $errors = [];

        if (empty($_POST['name'])) {
            $errors[] = 'Event name is required.';
        } else {
            // Check if the event name is unique (exclude the current event)
            if ($this->eventDB->uniqueName($_POST['name'], $id)) {
                $errors[] = 'Event name already exists.';
            }
        }
        if (empty($_POST['location'])) {
            $errors[] = 'Location is required.';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header("Location: /events/$id");
            exit;
        }

        if ($this->eventDB->update($id, $_POST)) {
            $_SESSION['success'] = 'Event updated successfully.';
        } else {
            $_SESSION['errors'] = ['Failed to update the event. Please try again.'];
        }

        header("Location: /events");
        exit;
    }

    public function delete($id)
    {
        $delete = $this->eventDB->delete($id);
        session_start();
        if ($delete) {
            $_SESSION['success'] = 'Event deleted successfully.';
        } else {
            $_SESSION['error'] = 'Error deleting event. Please try again.';
        }

        header("Location: /events");
        exit;
    }
}
