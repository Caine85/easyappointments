/* ----------------------------------------------------------------------------
 * Easy!Appointments - Online Appointment Scheduler
 *
 * @package     EasyAppointments
 * @author      A.Tselegidis <alextselegidis@gmail.com>
 * @copyright   Copyright (c) Alex Tselegidis
 * @license     https://opensource.org/licenses/GPL-3.0 - GPLv3
 * @link        https://easyappointments.org
 * @since       v1.5.0
 * ---------------------------------------------------------------------------- */

/**
 * Locations HTTP client.
 *
 * This module implements the locations related HTTP requests.
 */
App.Http.Locations = (function () {
    /**
     * Save (create or update) a location.
     *
     * @param {Object} location
     *
     * @return {Object}
     */
    function save(location) {
        return location.id ? update(location) : store(location);
    }

    /**
     * Create a location.
     *
     * @param {Object} location
     *
     * @return {Object}
     */
    function store(location) {
        const url = App.Utils.Url.siteUrl('locations/store');

        const data = {
            csrf_token: vars('csrf_token'),
            location: location,
        };

        return $.post(url, data);
    }

    /**
     * Update a location.
     *
     * @param {Object} location
     *
     * @return {Object}
     */
    function update(location) {
        const url = App.Utils.Url.siteUrl('locations/update');

        const data = {
            csrf_token: vars('csrf_token'),
            location: location,
        };

        return $.post(url, data);
    }

    /**
     * Delete a location.
     *
     * @param {Number} locationId
     *
     * @return {Object}
     */
    function destroy(locationId) {
        const url = App.Utils.Url.siteUrl('locations/destroy');

        const data = {
            csrf_token: vars('csrf_token'),
            location_id: locationId,
        };

        return $.post(url, data);
    }

    /**
     * Search locations by keyword.
     *
     * @param {String} keyword
     * @param {Number} [limit]
     * @param {Number} [offset]
     *
     * @return {Object}
     */
    function search(keyword, limit = null, offset = null) {
        const url = App.Utils.Url.siteUrl('locations/search');

        const data = {
            csrf_token: vars('csrf_token'),
            keyword,
            limit,
            offset,
        };

        return $.post(url, data);
    }

    /**
     * Find a location.
     *
     * @param {Number} locationId
     *
     * @return {Object}
     */
    function find(locationId) {
        const url = App.Utils.Url.siteUrl('locations/find');

        const data = {
            csrf_token: vars('csrf_token'),
            location_id: locationId,
        };

        return $.post(url, data);
    }

    return {
        save,
        store,
        update,
        destroy,
        search,
        find,
    };
})();
