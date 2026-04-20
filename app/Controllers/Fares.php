<?php

namespace App\Controllers;

use App\Models\RouteModel;
use App\Models\AnnouncementModel;

class Fares extends BaseController
{
    public function index()
    {
        $routeModel = new RouteModel();

        // Fetch routes grouped by vehicle type
        $van_routes = $routeModel->where('vehicle_type', 'van')->findAll();
        $jeepney_routes = $routeModel->where('vehicle_type', 'jeepney')->findAll();
        $minibus_routes = $routeModel->where('vehicle_type', 'minibus')->findAll();

        $announcements = [];
        try {
            $announcementModel = new AnnouncementModel();
            $announcements = $announcementModel->where('is_active', 1)->orderBy('sort_order', 'ASC')->findAll();
        } catch (\Throwable $e) {}

        $data = [
            'title' => 'Route Fares',
            'body_class' => 'public-page',
            'van_routes' => $van_routes,
            'jeepney_routes' => $jeepney_routes,
            'minibus_routes' => $minibus_routes,
            'announcements' => $announcements
        ];

        // Use shared view for logged-in users, public view for guests
        if (session()->get('isLoggedIn')) {
            return view('shared/fares', $data);
        }
        return view('public/fares', $data);
    }
}
