<?php

namespace App\Controllers\Api;

use CodeIgniter\Controller;
use App\Models\QueueModel;

/**
 * Lightweight JSON API for queue sync polling.
 * Returns only active queue items with passenger counts.
 * No authentication required (read-only public data).
 */
class QueueStatus extends Controller
{
    public function index()
    {
        $queueModel = new QueueModel();

        $queue = $queueModel->select('queue.id, queue.position, queue.status, queue.current_passengers, vehicles.capacity, vehicles.plate_number, routes.origin, routes.destination')
            ->join('vehicles', 'vehicles.id = queue.vehicle_id')
            ->join('routes', 'routes.id = queue.route_id')
            ->whereIn('queue.status', ['waiting', 'boarding'])
            ->orderBy('queue.position', 'ASC')
            ->findAll();

        // Build a simple lookup by queue ID
        $items = [];
        foreach ($queue as $item) {
            $items[] = [
                'id'                 => (int)$item['id'],
                'position'           => (int)$item['position'],
                'status'             => $item['status'],
                'current_passengers' => (int)$item['current_passengers'],
                'capacity'           => (int)$item['capacity'],
                'plate_number'       => $item['plate_number'],
                'origin'             => $item['origin'],
                'destination'        => $item['destination'],
            ];
        }

        return $this->response
            ->setHeader('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->setJSON([
                'success' => true,
                'queue'   => $items,
                'ts'      => time()
            ]);
    }
}
