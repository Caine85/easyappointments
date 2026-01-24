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
 * Locations page.
 *
 * This module implements the functionality of the locations page.
 */
App.Pages.Locations = (function () {
    const $locations = $('#locations');
    const $id = $('#id');
    const $name = $('#name');
    const $address = $('#address');
    const $city = $('#city');
    const $zipCode = $('#zip-code');
    const $phoneNumber = $('#phone-number');
    const $filterLocations = $('#filter-locations');
    let filterResults = {};
    let filterLimit = 20;

    /**
     * Add page event listeners.
     */
    function addEventListeners() {
        /**
         * Event: Filter Locations Form "Submit"
         *
         * @param {jQuery.Event} event
         */
        $locations.on('submit', '#filter-locations form', (event) => {
            event.preventDefault();
            const key = $filterLocations.find('.key').val();
            $filterLocations.find('.selected').removeClass('selected');
            App.Pages.Locations.resetForm();
            App.Pages.Locations.filter(key);
        });

        /**
         * Event: Filter Location Row "Click"
         *
         * Display the selected location data to the user.
         */
        $locations.on('click', '.location-row', (event) => {
            if ($filterLocations.find('.filter').prop('disabled')) {
                $filterLocations.find('.results').css('color', '#AAA');
                return; // exit because we are on edit mode
            }

            const locationId = $(event.currentTarget).attr('data-id');
            const location = filterResults.find((filterResult) => Number(filterResult.id) === Number(locationId));

            App.Pages.Locations.display(location);
            $filterLocations.find('.selected').removeClass('selected');
            $(event.currentTarget).addClass('selected');
            $('#edit-location, #delete-location').prop('disabled', false);
        });

        /**
         * Event: Add New Location Button "Click"
         */
        $locations.on('click', '#add-location', () => {
            App.Pages.Locations.resetForm();
            $locations.find('.add-edit-delete-group').hide();
            $locations.find('.save-cancel-group').show();
            $locations.find('.record-details').find('input, select, textarea').prop('disabled', false);
            $locations.find('.record-details .form-label span').prop('hidden', false);
            $filterLocations.find('button').prop('disabled', true);
            $filterLocations.find('.results').css('color', '#AAA');

            // Default values
            $name.val('New Location');
        });

        /**
         * Event: Cancel Location Button "Click"
         *
         * Cancel add or edit of a location record.
         */
        $locations.on('click', '#cancel-location', () => {
            const id = $id.val();

            App.Pages.Locations.resetForm();

            if (id !== '') {
                App.Pages.Locations.select(id, true);
            }
        });

        /**
         * Event: Save Location Button "Click"
         */
        $locations.on('click', '#save-location', () => {
            const location = {
                name: $name.val(),
                address: $address.val(),
                city: $city.val(),
                zip_code: $zipCode.val(),
                phone_number: $phoneNumber.val(),
            };

            if ($id.val() !== '') {
                location.id = $id.val();
            }

            if (!App.Pages.Locations.validate()) {
                return;
            }

            App.Pages.Locations.save(location);
        });

        /**
         * Event: Edit Location Button "Click"
         */
        $locations.on('click', '#edit-location', () => {
            $locations.find('.add-edit-delete-group').hide();
            $locations.find('.save-cancel-group').show();
            $locations.find('.record-details').find('input, select, textarea').prop('disabled', false);
            $locations.find('.record-details .form-label span').prop('hidden', false);
            $filterLocations.find('button').prop('disabled', true);
            $filterLocations.find('.results').css('color', '#AAA');
        });

        /**
         * Event: Delete Location Button "Click"
         */
        $locations.on('click', '#delete-location', () => {
            const locationId = $id.val();
            const buttons = [
                {
                    text: lang('cancel'),
                    click: (event, messageModal) => {
                        messageModal.hide();
                    },
                },
                {
                    text: lang('delete'),
                    click: (event, messageModal) => {
                        App.Pages.Locations.remove(locationId);
                        messageModal.hide();
                    },
                },
            ];

            App.Utils.Message.show(lang('delete_location'), lang('delete_record_prompt'), buttons);
        });
    }

    /**
     * Save location record to database.
     *
     * @param {Object} location Contains the location record data. If an 'id' value is provided
     * then the update operation is going to be executed.
     */
    function save(location) {
        App.Http.Locations.save(location).then((response) => {
            App.Layouts.Backend.displayNotification(lang('location_saved'));
            App.Pages.Locations.resetForm();
            $filterLocations.find('.key').val('');
            App.Pages.Locations.filter('', response.id, true);
        });
    }

    /**
     * Delete a location record from database.
     *
     * @param {Number} id Record ID to be deleted.
     */
    function remove(id) {
        App.Http.Locations.destroy(id).then(() => {
            App.Layouts.Backend.displayNotification(lang('location_deleted'));
            App.Pages.Locations.resetForm();
            App.Pages.Locations.filter($filterLocations.find('.key').val());
        });
    }

    /**
     * Validates a location record.
     *
     * @return {Boolean} Returns the validation result.
     */
    function validate() {
        $locations.find('.is-invalid').removeClass('is-invalid');
        $locations.find('.form-message').removeClass('alert-danger').hide();

        try {
            // Validate required fields.
            let missingRequired = false;

            $locations.find('.required').each((index, requiredField) => {
                if (!$(requiredField).val()) {
                    $(requiredField).addClass('is-invalid');
                    missingRequired = true;
                }
            });

            if (missingRequired) {
                throw new Error(lang('fields_are_required'));
            }

            return true;
        } catch (error) {
            $locations.find('.form-message').addClass('alert-danger').text(error.message).show();
            return false;
        }
    }

    /**
     * Resets the location tab form back to its initial state.
     */
    function resetForm() {
        $filterLocations.find('.selected').removeClass('selected');
        $filterLocations.find('button').prop('disabled', false);
        $filterLocations.find('.results').css('color', '');

        $locations.find('.record-details').find('input, select, textarea').val('').prop('disabled', true);
        $locations.find('.record-details .form-label span').prop('hidden', true);

        $locations.find('.add-edit-delete-group').show();
        $locations.find('.save-cancel-group').hide();
        $('#edit-location, #delete-location').prop('disabled', true);

        $locations.find('.record-details .is-invalid').removeClass('is-invalid');
        $locations.find('.record-details .form-message').hide();
    }

    /**
     * Display a location record into the location form.
     *
     * @param {Object} location Contains the location record data.
     */
    function display(location) {
        $id.val(location.id);
        $name.val(location.name);
        $address.val(location.address);
        $city.val(location.city);
        $zipCode.val(location.zip_code);
        $phoneNumber.val(location.phone_number);
    }

    /**
     * Filters location records depending on a string keyword.
     *
     * @param {String} keyword This is used to filter the location records of the database.
     * @param {Number} selectId Optional, if set then after the filter operation the record with this
     * ID will be selected (but not displayed).
     * @param {Boolean} show Optional (false), if true then the selected record will be displayed on the form.
     */
    function filter(keyword, selectId = null, show = false) {
        App.Http.Locations.search(keyword, filterLimit).then((response) => {
            filterResults = response;

            $filterLocations.find('.results').empty();

            response.forEach((location) => {
                $filterLocations.find('.results').append(App.Pages.Locations.getFilterHtml(location)).append($('<hr/>'));
            });

            if (response.length === 0) {
                $filterLocations.find('.results').append(
                    $('<em/>', {
                        'text': lang('no_records_found'),
                    }),
                );
            } else if (response.length === filterLimit) {
                $('<button/>', {
                    'type': 'button',
                    'class': 'btn btn-outline-secondary w-100 load-more text-center',
                    'text': lang('load_more'),
                    'click': () => {
                        filterLimit += 20;
                        App.Pages.Locations.filter(keyword, selectId, show);
                    },
                }).appendTo('#filter-locations .results');
            }

            if (selectId) {
                App.Pages.Locations.select(selectId, show);
            }
        });
    }

    /**
     * Get Filter HTML
     *
     * Get a location row HTML code that is going to be displayed on the filter results list.
     *
     * @param {Object} location Contains the location record data.
     *
     * @return {String} The HTML code that represents the record on the filter results list.
     */
    function getFilterHtml(location) {
        const name = location.name;
        let info = '';
        if (location.address) info += location.address;
        if (location.city) info += (info ? ', ' : '') + location.city;

        return $('<div/>', {
            'class': 'location-row entry',
            'data-id': location.id,
            'html': [
                $('<strong/>', {
                    'text': name,
                }),
                $('<br/>'),
                $('<small/>', {
                    'class': 'text-muted',
                    'text': info,
                }),
                $('<br/>'),
            ],
        });
    }

    /**
     * Select a specific record from the current filter results. If the location id does not exist
     * in the list then no record will be selected.
     *
     * @param {Number} id The record id to be selected from the filter results.
     * @param {Boolean} show Optional (false), if true then the method will display the record on the form.
     */
    function select(id, show = false) {
        $filterLocations.find('.selected').removeClass('selected');

        $filterLocations.find('.location-row[data-id="' + id + '"]').addClass('selected');

        if (show) {
            const location = filterResults.find((filterResult) => Number(filterResult.id) === Number(id));

            App.Pages.Locations.display(location);

            $('#edit-location, #delete-location').prop('disabled', false);
        }
    }

    /**
     * Initialize the module.
     */
    function initialize() {
        App.Pages.Locations.resetForm();
        App.Pages.Locations.filter('');
        App.Pages.Locations.addEventListeners();
    }

    document.addEventListener('DOMContentLoaded', initialize);

    return {
        filter,
        save,
        remove,
        validate,
        getFilterHtml,
        resetForm,
        display,
        select,
        addEventListeners,
    };
})();
