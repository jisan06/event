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
        $limit = 10;
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $order = $_GET['order'] ?? 'ASC';
        $order_by = $_GET['order_by'] ?? 'name';
        $offset = ($page - 1) * $limit;

        $filters = [
            'name' => $_GET['name'] ?? '',
            'location' => $_GET['location'] ?? '',
            'start_date' => $_GET['start_date'] ?? '',
            'end_date' => $_GET['end_date'] ?? ''
        ];

        $events = $this->eventDB->all($limit, $order, $order_by, $offset, $filters);
        $total = $this->eventDB->count($filters); // You need to update the count method to support filters
        $total_pages = ceil($total / $limit);

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

        try {
            // Validation
            if (empty($_POST['name'])) {
                $errors[] = 'Event name is required.';
            } else {
                if ($this->eventDB->uniqueName($_POST['name'])) {
                    $errors[] = 'Event name already exists.';
                }
            }
            if (empty($_POST['location'])) {
                $errors[] = 'Location is required.';
            }

            // If there are validation errors
            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
                header("Location: /events/create");
                exit;
            }

            // Create event
            $this->eventDB->create($_POST);

            // Success message
            $_SESSION['success'] = 'Event created successfully.';
            header("Location: /events");
            exit;
        } catch (\Exception $e) {
            // Log the exception (you can implement a Logger here)
            error_log($e->getMessage());

            // Redirect with an error message
            $_SESSION['errors'] = ['An unexpected error occurred. Please try again later.'];
            header("Location: /events/create");
            exit;
        }
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

        try {
            // Validation
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
            if (empty($_POST['total_seat'])) {
                $errors[] = 'Please write total available seat';
            }

            // If there are validation errors
            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
                header("Location: /events/$id");
                exit;
            }

            // Attempt to update the event
            if ($this->eventDB->update($id, $_POST)) {
                $_SESSION['success'] = 'Event updated successfully.';
            } else {
                $_SESSION['errors'] = ['Failed to update the event. Please try again.'];
            }

            header("Location: /events");
            exit;
        } catch (\Exception $e) {
            // Log the exception
            error_log($e->getMessage());

            // Redirect with an error message
            $_SESSION['errors'] = ['An unexpected error occurred. Please try again later.'];
            header("Location: /events/$id");
            exit;
        }
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

    public function register($event_id)
    {
        $event = $this->eventDB->find($event_id);
        include Helper::view('event/register.php');
    }

    public function register_event($event_id)
    {
        session_start();
        $errors = [];

        try {
            // Validation
            if (empty($_POST['name'])) {
                $errors[] = 'User name is required.';
            } else {
                if ($this->eventDB->unique_registration($event_id, $_POST['email'])) {
                    $errors[] = 'This user already registered.';
                }
            }
            if (empty($_POST['email'])) {
                $errors[] = 'User email is required.';
            }

            $event = $this->eventDB->find($event_id);
            $count_registration = $this->eventDB->count_registration($event_id);
            if ($count_registration >= $event['total_seat']) {
                $errors[] = 'This event has reached its maximum capacity.';
            }

            // If there are validation errors
            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
                header("Location: /events/register/" . $event_id);
                exit;
            }

            // Create event
            $this->eventDB->event_register($event_id, $_POST);

            // Success message
            $_SESSION['success'] = 'New Event Registration successfully.';
            header("Location: /events");
            exit;
        } catch (\Exception $e) {
            // Log the exception (you can implement a Logger here)
            error_log($e->getMessage());

            // Redirect with an error message
            $_SESSION['errors'] = ['An unexpected error occurred. Please try again later.'];
            header("Location: /events/register/" . $event_id);
            exit;
        }
    }
}
