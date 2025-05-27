<?php

if (!function_exists('currentOrganization')) {
    function currentOrganization() {
        return app('currentOrganization');
    }
}
