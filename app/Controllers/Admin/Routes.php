<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\RouteModel;
use App\Models\TerminalModel;

class Routes extends BaseController
{
    protected $routeModel;
    protected $terminalModel;

    public function __construct()
    {
        $this->routeModel = new RouteModel();
        $this->terminalModel = new TerminalModel();
    }

    public function index()
    {
        // Join with terminals table to get terminal name
        $routes = $this->routeModel->select('routes.*, terminals.name as terminal_name')
                                   ->join('terminals', 'terminals.id = routes.terminal_id')
                                   ->findAll();

        $data = [
            'title' => 'Manage Routes',
            'routes' => $routes
        ];

        return view('admin/routes/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Add New Route',
            'terminals' => $this->terminalModel->findAll()
        ];
        return view('admin/routes/create', $data);
    }

    public function store()
    {
        $rules = [
            'origin' => 'required|min_length[3]|max_length[100]',
            'destination' => 'required|min_length[3]|max_length[100]',
            'fare' => 'required|decimal|greater_than[0]',
            'terminal_id' => 'required|integer|is_not_unique[terminals.id]',
            'vehicle_type' => 'required|in_list[jeepney,van,minibus]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->routeModel->save([
            'origin' => $this->request->getPost('origin'),
            'destination' => $this->request->getPost('destination'),
            'fare' => $this->request->getPost('fare'),
            'terminal_id' => $this->request->getPost('terminal_id'),
            'vehicle_type' => $this->request->getPost('vehicle_type')
        ]);

        $this->logActivity('Create route', $this->request->getPost('origin') . ' → ' . $this->request->getPost('destination') . ' (' . $this->request->getPost('vehicle_type') . ', ₱' . $this->request->getPost('fare') . ').');

        $redirectUrl = (session()->get('role') === 'staff') ? '/fares' : '/admin/routes';
        return redirect()->to($redirectUrl)->with('success', 'Route added successfully.');
    }

    public function edit($id)
    {
        $route = $this->routeModel->find($id);

        if (!$route) {
            return redirect()->to('/admin/routes')->with('error', 'Route not found.');
        }

        $data = [
            'title' => 'Edit Route',
            'route' => $route,
            'terminals' => $this->terminalModel->findAll()
        ];

        return view('admin/routes/edit', $data);
    }

    public function update($id)
    {
        $rules = [
            'origin' => 'required|min_length[3]|max_length[100]',
            'destination' => 'required|min_length[3]|max_length[100]',
            'fare' => 'required|decimal|greater_than[0]',
            'terminal_id' => 'required|integer|is_not_unique[terminals.id]',
            'vehicle_type' => 'required|in_list[jeepney,van,minibus]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->routeModel->update($id, [
            'origin' => $this->request->getPost('origin'),
            'destination' => $this->request->getPost('destination'),
            'fare' => $this->request->getPost('fare'),
            'terminal_id' => $this->request->getPost('terminal_id'),
            'vehicle_type' => $this->request->getPost('vehicle_type')
        ]);

        $this->logActivity('Update route', $this->request->getPost('origin') . ' → ' . $this->request->getPost('destination') . ' (' . $this->request->getPost('vehicle_type') . ').');

        $redirectUrl = (session()->get('role') === 'staff') ? '/fares' : '/admin/routes';
        return redirect()->to($redirectUrl)->with('success', 'Route updated successfully.');
    }

    public function delete($id)
    {
        $route = $this->routeModel->find($id);
        if ($this->routeModel->delete($id)) {
            if ($route) {
                $this->logActivity('Delete route', $route['origin'] . ' → ' . $route['destination'] . '.');
            }
            $redirectUrl = (session()->get('role') === 'staff') ? '/fares' : '/admin/routes';
            return redirect()->to($redirectUrl)->with('success', 'Route deleted successfully.');
        }
        $redirectUrl = (session()->get('role') === 'staff') ? '/fares' : '/admin/routes';
        return redirect()->to($redirectUrl)->with('error', 'Failed to delete route.');
    }
}
