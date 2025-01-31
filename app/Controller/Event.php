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
        $limit = isset($_GET['limit']) ? : 5;
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $order = $_GET['order'] ?? 'DESC';
        $order_by = $_GET['order_by'] ?? 'id';
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
        $errors = [];

        try {
            $_POST['name'] = trim($_POST['name']);
            $_POST['location'] = trim($_POST['location']);
            // Validation
            if (empty($_POST['name'])) {
                $errors[] = 'Event name is required.';
            } else {
                if ($this->eventDB->uniqueName($_POST['name'], $_POST['location'])) {
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
                header("Location: " . BASE_URL . "events/create");
                exit;
            }

            // Create event
            $this->eventDB->create($_POST);

            // Success message
            $_SESSION['success'] = 'Event created successfully.';
            header("Location: " . BASE_URL . "events");
            exit;
        } catch (\Exception $e) {
            // Log the exception (you can implement a Logger here)
            error_log($e->getMessage());

            // Redirect with an error message
            $_SESSION['errors'] = ['An unexpected error occurred. Please try again later.'];
            header("Location: " . BASE_URL . "events/create");
            exit;
        }
    }


    public function edit($id)
    {
        $event = $this->eventDB->find($id);
        include Helper::view('event/edit.php');
    }

    public function update($id)
    {
        $errors = [];

        try {
            $_POST['name'] = trim($_POST['name']);
            $_POST['location'] = trim($_POST['location']);
            // Validation
            if (empty($_POST['name'])) {
                $errors[] = 'Event name is required.';
            } else {
                // Check if the event name is unique (exclude the current event)
                if ($this->eventDB->uniqueName($_POST['name'], $_POST['location'], $id)) {
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
                header("Location: " . BASE_URL . "events/$id");
                exit;
            }

            // Attempt to update the event
            if ($this->eventDB->update($id, $_POST)) {
                $_SESSION['success'] = 'Event updated successfully.';
            } else {
                $_SESSION['errors'] = ['Failed to update the event. Please try again.'];
            }

            header("Location: " . BASE_URL . "events");
            exit;
        } catch (\Exception $e) {
            // Log the exception
            error_log($e->getMessage());

            // Redirect with an error message
            $_SESSION['errors'] = ['An unexpected error occurred. Please try again later.'];
            header("Location: " . BASE_URL . "events/$id");
            exit;
        }
    }

    public function delete($id)
    {
        $delete = $this->eventDB->delete($id);
        if ($delete) {
            $_SESSION['success'] = 'Event deleted successfully.';
        } else {
            $_SESSION['error'] = 'Error deleting event. Please try again.';
        }

        header("Location: " . BASE_URL . "events");
        exit;
    }

    public function register($event_id)
    {
        $event = $this->eventDB->find($event_id);
        include Helper::view('event/register.php');
    }

    public function register_event($event_id)
    {
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
                header("Location: " . BASE_URL . "events/register/" . $event_id);
                exit;
            }

            // Create event
            $this->eventDB->event_register($event_id, $_POST);

            // Success message
            $_SESSION['success'] = 'New Event Registration successfully.';
            header("Location: " . BASE_URL . "events");
            exit;
        } catch (\Exception $e) {
            // Log the exception (you can implement a Logger here)
            error_log($e->getMessage());

            // Redirect with an error message
            $_SESSION['errors'] = ['An unexpected error occurred. Please try again later.'];
            header("Location: " . BASE_URL . "events/register/" . $event_id);
            exit;
        }
    }

    public function download($event_id)
    {
        $event = $this->eventDB->find($event_id);
        $attendees = $this->eventDB->attendees_by_event($event_id);

        $file_name = "{$event['name']}.csv";
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $file_name . '"');

        $result = fopen('php://output', 'w');
        fputcsv($result, ['Name', 'Email', 'Registration Date']);
        foreach ($attendees as $attendee) {
            fputcsv($result, [$attendee['name'], $attendee['email'], $attendee['created_at']]);
        }

        fclose($result);
        exit;
    }
}
