<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Locations model.
 *
 * Handles all the database operations of the location resource.
 *
 * @package Models
 */
class Locations_model extends EA_Model
{
    /**
     * @var array
     */
    protected array $casts = [
        'id' => 'integer',
    ];

    /**
     * @var array
     */
    protected array $api_resource = [
        'id' => 'id',
        'name' => 'name',
        'address' => 'address',
        'city' => 'city',
        'zip' => 'zip_code',
        'phone' => 'phone_number',
    ];

    /**
     * Get all locations.
     *
     * @return array
     */
    public function get_all(): array
    {
        return $this->db->get('locations')->result_array();
    }
    
    // Add save, validate, delete, find methods as needed in future, 
    // for now basic retrieval is enough for Booking logic.
    /**
     * Validate the location data.
     *
     * @param array $location
     *
     * @return bool
     * @throws Exception
     */
    public function validate(array $location): bool
    {
        if (empty($location['name'])) {
            throw new Exception(lang('fields_are_required'));
        }

        return true;
    }

    /**
     * Save the location data.
     *
     * @param array $location
     *
     * @return int
     * @throws Exception
     */
    public function save(array $location): int
    {
        $this->validate($location);

        if (!empty($location['id'])) {
            $this->update($location);
            return $location['id'];
        }

        return $this->insert($location);
    }

    /**
     * Insert a new location into the database.
     *
     * @param array $location Associative array with the location data.
     *
     * @return int Returns the location ID.
     *
     * @throws RuntimeException
     */
    protected function insert(array $location): int
    {
        $location['created_at'] = date('Y-m-d H:i:s');
        $location['updated_at'] = date('Y-m-d H:i:s');

        if (!$this->db->insert('locations', $location)) {
            throw new RuntimeException('Could not insert location.');
        }

        return $this->db->insert_id();
    }

    /**
     * Update an existing location.
     *
     * @param array $location Associative array with the location data.
     *
     * @return int Returns the location ID.
     *
     * @throws RuntimeException
     */
    protected function update(array $location): int
    {
        $location['updated_at'] = date('Y-m-d H:i:s');

        if (!$this->db->update('locations', $location, ['id' => $location['id']])) {
            throw new RuntimeException('Could not update location.');
        }

        return $location['id'];
    }

    /**
     * Remove an existing location from the database.
     *
     * @param int $location_id Location ID.
     *
     * @throws RuntimeException
     */
    public function delete(int $location_id): void
    {
        $this->db->delete('locations', ['id' => $location_id]);
    }

    /**
     * Get a specific location from the database.
     *
     * @param int $location_id The ID of the record to be returned.
     *
     * @return array Returns an array with the location data.
     *
     * @throws InvalidArgumentException
     */
    public function find(int $location_id): array
    {
        $location = $this->db->where(['id' => $location_id])->get('locations')->row_array();

        if (!$location) {
            throw new InvalidArgumentException('The provided location ID was not found in the database: ' . $location_id);
        }

        $this->cast($location);

        return $location;
    }

    /**
     * Search for locations.
     *
     * @param string $keyword
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function search(string $keyword, int $limit = 10, int $offset = 0): array
    {
        return $this->db
            ->like('name', $keyword)
            ->or_like('address', $keyword)
            ->or_like('city', $keyword)
            ->limit($limit, $offset)
            ->get('locations')
            ->result_array();
    }
}
