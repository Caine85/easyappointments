<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Locations controller.
 *
 * Handles the locations related operations.
 *
 * @package Controllers
 */
class Locations extends EA_Controller
{
    public array $allowed_location_fields = [
        'id',
        'name',
        'address',
        'city',
        'zip_code',
        'phone_number'
    ];

    /**
     * Locations constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->model('locations_model');
        $this->load->model('roles_model');

        $this->load->library('accounts');
    }

    /**
     * Render the backend locations page.
     */
    public function index(): void
    {
        session(['dest_url' => site_url('locations')]);

        $user_id = session('user_id');

        if (cannot('view', PRIV_USERS)) {
            if ($user_id) {
                abort(403, 'Forbidden');
            }

            redirect('login');

            return;
        }

        $role_slug = session('role_slug');

        script_vars([
            'user_id' => $user_id,
            'role_slug' => $role_slug,
        ]);

        html_vars([
            'page_title' => lang('locations'),
            'active_menu' => 'locations',
            'user_display_name' => $this->accounts->get_user_display_name($user_id),
            'privileges' => $this->roles_model->get_permissions_by_slug($role_slug),
        ]);

        $this->load->view('pages/locations');
    }

    /**
     * Filter locations by the provided keyword.
     */
    public function search(): void
    {
        try {
            if (cannot('view', PRIV_USERS)) {
                abort(403, 'Forbidden');
            }

            $keyword = request('keyword', '');
            $limit = request('limit', 1000);
            $offset = (int) request('offset', '0');

            $locations = $this->locations_model->search($keyword, $limit, $offset);

            json_response($locations);
        } catch (Throwable $e) {
            json_exception($e);
        }
    }

    /**
     * Store a new location.
     */
    public function store(): void
    {
        try {
            if (cannot('add', PRIV_USERS)) {
                abort(403, 'Forbidden');
            }

            $location = request('location');

            $this->locations_model->only($location, $this->allowed_location_fields);

            $location_id = $this->locations_model->save($location);

            json_response([
                'success' => true,
                'id' => $location_id,
            ]);
        } catch (Throwable $e) {
            json_exception($e);
        }
    }

    /**
     * Find a location.
     */
    public function find(): void
    {
        try {
            if (cannot('view', PRIV_USERS)) { // Reuse view or edit permission? 'find' helps populate edit form.
                abort(403, 'Forbidden');
            }

            $location_id = request('location_id');

            $location = $this->locations_model->find($location_id);

            json_response($location);
        } catch (Throwable $e) {
            json_exception($e);
        }
    }

    /**
     * Update a location.
     */
    public function update(): void
    {
        try {
            if (cannot('edit', PRIV_USERS)) {
                abort(403, 'Forbidden');
            }

            $location = request('location');

            $this->locations_model->only($location, $this->allowed_location_fields);

            $location_id = $this->locations_model->save($location);

            json_response([
                'success' => true,
                'id' => $location_id,
            ]);
        } catch (Throwable $e) {
            json_exception($e);
        }
    }

    /**
     * Remove a location.
     */
    public function destroy(): void
    {
        try {
            if (cannot('delete', PRIV_USERS)) {
                abort(403, 'Forbidden');
            }

            $location_id = request('location_id');

            $this->locations_model->delete($location_id);

            json_response([
                'success' => true,
            ]);
        } catch (Throwable $e) {
            json_exception($e);
        }
    }
}
