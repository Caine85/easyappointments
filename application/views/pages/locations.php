<?php extend('layouts/backend_layout'); ?>

<?php section('content'); ?>

<div class="container-fluid backend-page" id="locations-page">
    <div class="row" id="locations">
        <div id="filter-locations" class="filter-records col col-12 col-md-5">
            <form class="mb-4">
                <div class="input-group">
                    <input type="text" class="key form-control" aria-label="keyword">

                    <button class="filter btn btn-outline-secondary" type="submit"
                            data-tippy-content="<?= lang('filter') ?>">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>

            <h4 class="text-black-50 mb-3 fw-light">
                <?= lang('locations') ?>
            </h4>

            <?php slot('after_page_title'); ?>

            <div class="results">
                <!-- JS -->
            </div>
        </div>

        <div class="record-details column col-12 col-md-5">
            <div class="btn-toolbar mb-4">
                <div class="add-edit-delete-group btn-group">
                    <button id="add-location" class="btn btn-primary">
                        <i class="fas fa-plus-square me-2"></i>
                        <?= lang('add') ?>
                    </button>
                    <button id="edit-location" class="btn btn-outline-secondary" disabled="disabled">
                        <i class="fas fa-edit me-2"></i>
                        <?= lang('edit') ?>
                    </button>
                    <button id="delete-location" class="btn btn-outline-secondary" disabled="disabled">
                        <i class="fas fa-trash-alt me-2"></i>
                        <?= lang('delete') ?>
                    </button>
                </div>

                <div class="save-cancel-group" style="display:none;">
                    <button id="save-location" class="btn btn-primary">
                        <i class="fas fa-check-square me-2"></i>
                        <?= lang('save') ?>
                    </button>
                    <button id="cancel-location" class="btn btn-secondary">
                        <?= lang('cancel') ?>
                    </button>
                </div>

                <?php slot('after_page_actions'); ?>
            </div>

            <h4 class="text-black-50 mb-3 fw-light">
                <?= lang('details') ?>
            </h4>

            <div class="form-message alert" style="display:none;"></div>

            <input type="hidden" id="id">

            <div class="mb-3">
                <label class="form-label" for="name">
                    <?= lang('name') ?>
                    <span class="text-danger" hidden>*</span>
                </label>
                <input id="name" class="form-control required" maxlength="255" disabled>
            </div>

            <div class="mb-3">
                <label class="form-label" for="address">
                    <?= lang('address') ?>
                </label>
                <input id="address" class="form-control" maxlength="255" disabled>
            </div>

            <div class="mb-3">
                <label class="form-label" for="city">
                    <?= lang('city') ?>
                </label>
                <input id="city" class="form-control" maxlength="255" disabled>
            </div>

            <div class="mb-3">
                <label class="form-label" for="zip-code">
                    <?= lang('zip_code') ?>
                </label>
                <input id="zip-code" class="form-control" maxlength="100" disabled>
            </div>

            <div class="mb-3">
                <label class="form-label" for="phone-number">
                    <?= lang('phone_number') ?>
                </label>
                <input id="phone-number" class="form-control" maxlength="100" disabled>
            </div>

            <div class="mb-3">
                <label class="form-label" for="providers">
                    <?= lang('providers') ?>
                </label>
                <div id="location-providers" class="card card-body bg-white border">
                    <!-- JS -->
                </div>
            </div>

            <?php slot('after_primary_fields'); ?>
        </div>
    </div>
</div>

<?php end_section('content'); ?>

<?php section('scripts'); ?>

<script src="<?= asset_url('assets/js/http/locations_http_client.js') ?>"></script>
<script src="<?= asset_url('assets/js/pages/locations.js') ?>"></script>

<?php end_section('scripts'); ?>
