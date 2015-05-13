<?php

/////////////////////////////////////////////////////////////////////////////
// General information
/////////////////////////////////////////////////////////////////////////////

$app['basename'] = 'egress_firewall';
$app['version'] = '2.0.29';
$app['release'] = '1';
$app['vendor'] = 'ClearFoundation';
$app['packager'] = 'ClearFoundation';
$app['license'] = 'GPLv3';
$app['license_core'] = 'LGPLv3';
$app['description'] = lang('egress_firewall_app_description');

/////////////////////////////////////////////////////////////////////////////
// App name and categories
/////////////////////////////////////////////////////////////////////////////

$app['name'] = lang('egress_firewall_app_name');
$app['category'] = lang('base_category_network');
$app['subcategory'] = lang('base_subcategory_firewall');

/////////////////////////////////////////////////////////////////////////////
// Controllers
/////////////////////////////////////////////////////////////////////////////

$app['controllers']['egress_firewall']['title'] = lang('egress_firewall_app_name');
$app['controllers']['domain']['title'] = lang('egress_firewall_destination_domains');
$app['controllers']['mode']['title'] = lang('base_mode');
$app['controllers']['port']['title'] = lang('egress_firewall_destination_ports');

/////////////////////////////////////////////////////////////////////////////
// Packaging
/////////////////////////////////////////////////////////////////////////////

$app['requires'] = array(
    'app-network',
);

$app['core_requires'] = array(
    'app-firewall >= 1:1.5.19',
    'app-network-core',
);

$app['delete_dependency'] = array(
    'app-egress-firewall-core'
);
