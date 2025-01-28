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
        $db = $this->db->prepare($query);
        $db->bind_param('ssss', $data['name'], $date, $data['location'], $data['description']);

        return $db->execute();
    }

    public function update($id, $data): bool
    {
        $data['date'] = empty($data['date']) ? null : $data['date'];
        $query = "UPDATE events SET name = ?, date = ?, location = ?, description = ?, total_seat = ? WHERE id = ?";
        $db = $this->db->prepare($query);
        $db->bind_param(
            'ssssii',
            $data['name'],
            $data['date'],
            $data['location'],
            $data['description'],
            $data['total_seat'],
            $id
        );
        return $db->execute();
    }

    public function delete($id): bool
    {
        $query = "DELETE FROM events WHERE id = ?";
        $db = $this->db->prepare($query);
        $db->bind_param('i', $id);
        return $db->execute();
    }

    public function all($limit, $order, $order_by, $offset, $filters = []): array
    {
        $allowed_columns = ['name', 'location', 'date'];
        if (!in_array($order_by, $allowed_columns)) {
            $order_by = 'id';
        }

        // Build the base query
        $query = "
        SELECT events.*, 
               COUNT(event_attends.id) AS registered 
                FROM events 
                LEFT JOIN event_attends 
                ON events.id = event_attends.event_id 
                WHERE 1
        ";

        // Add filters to the query
        if (!empty($filters['name'])) {
            $query .= " AND name LIKE ?";
        }
        if (!empty($filters['location'])) {
            $query .= " AND location LIKE ?";
        }
        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $query .= " AND date BETWEEN ? AND ?";
        } elseif (!empty($filters['start_date'])) {
            $query .= " AND date >= ?";
        } elseif (!empty($filters['end_date'])) {
            $query .= " AND date <= ?";
        }

        // Add sorting and pagination
        $query .= " GROUP BY events.id ORDER BY $order_by $order LIMIT ? OFFSET ?";

        // Prepare the statement
        $db = $this->db->prepare($query);

        // Bind parameters for filters and pagination
        $types = '';
        $params = [];

        if (!empty($filters['name'])) {
            $types .= 's';
            $params[] = '%' . $filters['name'] . '%';  // Wildcard search for name
        }
        if (!empty($filters['location'])) {
            $types .= 's';
            $params[] = '%' . $filters['location'] . '%';  // Wildcard search for location
        }
        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $types .= 'ss';
            $params[] = $filters['start_date'];
            $params[] = $filters['end_date'];
        } elseif (!empty($filters['start_date'])) {
            $types .= 's';
            $params[] = $filters['start_date'];
        } elseif (!empty($filters['end_date'])) {
            $types .= 's';
            $params[] = $filters['end_date'];
        }

        // Bind the limit and offset
        $types .= 'ii';
        $params[] = $limit;
        $params[] = $offset;

        // Bind the parameters to the statement
        $db->bind_param($types, ...$params);

        // Execute and fetch the results
        $db->execute();
        return $db->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function count()
    {
        $query = "SELECT COUNT(*) AS total FROM events";
        $result = $this->db->query($query);
        $row = $result->fetch_assoc();
        return (int) $row['total'];
    }

    public function find($id)
    {
        $query = "SELECT * FROM events WHERE id = ?";
        $db = $this->db->prepare($query);
        $db->bind_param('i', $id);
        $db->execute();
        return $db->get_result()->fetch_assoc();
    }

    // Check if event name is exist or not
    public function uniqueName($name, $id = null): bool
    {
        $query = "SELECT id FROM events WHERE name = ?";
        if ($id) {
            $query .= " AND id != ?";
        }

        $db = $this->db->prepare($query);
        if ($id) {
            $db->bind_param('si', $name, $id);
        } else {
            $db->bind_param('s', $name);
        }

        $db->execute();
        $result = $db->get_result();

        // Return true if the name already exists (excluding the current event)
        return $result->num_rows > 0;
    }

    // Check if already register the event
    public function unique_registration($event_id, $email): bool
    {
        $query = "SELECT id FROM event_attends WHERE event_id = ? AND email = ?";

        $db = $this->db->prepare($query);
        $db->bind_param('is', $event_id, $email);

        $db->execute();
        $result = $db->get_result();

        // Return true if the event and email already exists
        return $result->num_rows > 0;
    }

    public function count_registration($event_id): int
    {
        $query = "SELECT COUNT(*) AS count FROM event_attends WHERE event_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $event_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return (int)$result['count'];
    }

    public function event_register($event_id, $data): bool
    {
        $query = "INSERT INTO event_attends (event_id, name, email) VALUES (?, ?, ?)";
        $db = $this->db->prepare($query);
        $db->bind_param('iss', $event_id, $data['name'], $data['email']);

        return $db->execute();
    }
}
