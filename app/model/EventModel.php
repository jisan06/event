<?php

namespace App\Model;

use App\Utils\Database;

class EventModel
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function create($data): bool
    {
        $date = empty($data['date']) ? null : $data['date'];
        $query = "INSERT INTO events (name, date, location, description) VALUES (?, ?, ?, ?)";
        $event = $this->db->prepare($query);
        $event->bind_param('ssss', $data['name'], $date, $data['location'], $data['description']);

        return $event->execute();
    }

    public function update($id, $data): bool
    {
        $data['date'] = empty($data['date']) ? null : $data['date'];
        $query = "UPDATE events SET name = ?, date = ?, location = ?, description = ? WHERE id = ?";
        $event = $this->db->prepare($query);
        $event->bind_param('ssssi', $data['name'], $data['date'], $data['location'], $data['description'], $id);
        return $event->execute();
    }

    public function delete($id): bool
    {
        $query = "DELETE FROM events WHERE id = ?";
        $event = $this->db->prepare($query);
        $event->bind_param('i', $id);
        return $event->execute();
    }

    public function all(): array
    {
        $query = "SELECT * FROM events";
        $result = $this->db->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function find($id)
    {
        $query = "SELECT * FROM events WHERE id = ?";
        $event = $this->db->prepare($query);
        $event->bind_param('i', $id);
        $event->execute();
        return $event->get_result()->fetch_assoc();
    }

    // Check if event name is exist or not
    public function uniqueName($name, $id = null): bool
    {
        $query = "SELECT id FROM events WHERE name = ?";
        if ($id) {
            $query .= " AND id != ?";
        }

        $event = $this->db->prepare($query);
        if ($id) {
            $event->bind_param('si', $name, $id);
        } else {
            $event->bind_param('s', $name);
        }

        $event->execute();
        $result = $event->get_result();

        // Return true if the name already exists (excluding the current event)
        return $result->num_rows > 0;
    }
}
