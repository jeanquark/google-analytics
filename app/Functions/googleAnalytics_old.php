<?php

// Load the Google API PHP Client Library.
// require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__.'/../../vendor/autoload.php';

$analytics = initializeAnalytics();
$profile = getFirstProfileId($analytics);
$results = getResults($analytics, $profile);
// printResults($results);

function initializeAnalytics()
{
    // Creates and returns the Analytics Reporting service object.

    // Use the developers console and download your service account
    // credentials in JSON format. Place them in this directory or
    // change the key file location if necessary.
    //$KEY_FILE_LOCATION = __DIR__ . '/service-account-credentials.json';
    $KEY_FILE_LOCATION = __DIR__ .'/../../.google/service-account-credentials.json';

    // Create and configure a new client object.
    $client = new Google_Client();
    $client->setApplicationName("Hello Analytics Reporting");
    $client->setAuthConfig($KEY_FILE_LOCATION);
    $client->setScopes(['https://www.googleapis.com/auth/analytics.readonly']);
    $analytics = new Google_Service_Analytics($client);

    return $analytics;
}

function getFirstProfileId($analytics) {
    // Get the user's first view (profile) ID.

    // Get the list of accounts for the authorized user.
    $accounts = $analytics->management_accounts->listManagementAccounts();
    //$a = $accounts->getItems();
    //$a1 = $a[0]->getName();

    if (count($accounts->getItems()) > 0) {
        $items = $accounts->getItems();
        $firstAccountId = $items[0]->getId();
        $firstAccountName = $items[0]->getName();

        // Get the list of properties for the authorized user.
        $properties = $analytics->management_webproperties
            ->listManagementWebproperties($firstAccountId);
        //$b = $properties->getItems();
        //$b1 = $b[0]->getName();

        if (count($properties->getItems()) > 0) {
            $items = $properties->getItems();
            $firstPropertyId = $items[0]->getId();
            $firstPropertyName = $items[0]->getName();

            // Get the list of views (profiles) for the authorized user.
            $profiles = $analytics->management_profiles
                ->listManagementProfiles($firstAccountId, $firstPropertyId);

            if (count($profiles->getItems()) > 0) {
                $items = $profiles->getItems();
                $profileInfo = [];
                //$a1 = $items[0]->getProfileInfo()->getProfileName();
                $profileName = $items[0]->getName();
                $profileId = $items[0]->getId();
                //$allData = $items;

                $profileInfo = [$profileId, $profileName, $firstPropertyName, $firstAccountName];
                return $profileInfo;


                // Return the first view (profile) ID.
                //return $items[0]->getId();

            } else {
                throw new Exception('No views (profiles) found for this user.');
            }
        } else {
            throw new Exception('No properties found for this user.');
        }
    } else {
        throw new Exception('No accounts found for this user.');
    }
}

function getResults($analytics, $profileInfo) {
    // Calls the Core Reporting API and queries for the number of sessions
    // for the last seven days.

    $data1 = [];

    /*$data1[0] = $analytics->data_ga->get(
        'ga:' . $profileInfo[0],
        '7daysAgo',
        'today',
        'ga:sessions, ga:bounces, ga:users, ga:newUsers, ga:bounceRate');

    $data1[1] = $analytics->data_ga->get(
        'ga:' . $profileInfo[0],
        '14daysAgo',
        '7daysAgo',
        'ga:sessions, ga:bounces, ga:users, ga:newUsers, ga:bounceRate');

    $data1[2] = $analytics->data_ga->get(
        'ga:' . $profileInfo[0],
        '21daysAgo',
        '14daysAgo',
        'ga:sessions, ga:bounces, ga:users, ga:newUsers, ga:bounceRate');

    $data1[3] = $analytics->data_ga->get(
        'ga:' . $profileInfo[0],
        '30daysAgo',
        '21daysAgo',
        'ga:sessions, ga:bounces, ga:users, ga:newUsers, ga:bounceRate');*/

    /*$data[4] = $analytics->data_ga->get(
        'ga:' . $profileId,
        '30daysAgo',
        'today',
        'ga:users',
        array('dimensions' => 'ga:country'));*/
    $data2 = [];
    $analyticsViewId    = 'ga:' . $profileInfo[0];
    //$startDate = ['28daysAgo', '21daysAgo', '14daysAgo', '7daysAgo'];
    $startDate = ['7daysAgo', '14daysAgo', '21daysAgo', '28daysAgo'];
    //$endDate = ['21daysAgo', '14daysAgo', '7daysAgo', 'today'];
    $endDate = ['today', '7daysAgo', '14daysAgo', '21daysAgo'];
    $metrics1 = 'ga:sessions, ga:users, ga:newUsers, ga:avgSessionDuration, ga:bounceRate';
    $metrics2 = 'ga:sessions';

    for ($i = 0, $size = count($startDate); $i < $size; $i++) {
        //$data1[$i] = $analytics->data_ga->get(
            //'ga:' . $profileInfo[0],
            //'$startDate[$i]',
            //'$endDate[$i]',
            //'$metrics1'
            //);
        $data1[$i] = $analytics->data_ga->get($analyticsViewId, $startDate[$i], $endDate[$i], $metrics1);
    }

    for ($i = 0, $size = count($startDate); $i < $size; $i++) {
        $data2[$i] = $analytics->data_ga->get($analyticsViewId, $startDate[$i], $endDate[$i], $metrics2, array(
            'dimensions'    => 'ga:country',
            'sort'          => '-ga:sessions',
            'max-results'   => '5'
        ));
    }

    /*$startDate          = ['30daysAgo', '21daysAgo'];
    $endDate            = ['21daysAgo', '14daysAgo'];
    $metrics            = 'ga:sessions';

    for ($i = 0; $i < 2; $i++) {

        $data2[$i] = $analytics->data_ga->get($analyticsViewId, $startDate[$i], $endDate[$i], $metrics, array(
            'dimensions'    => 'ga:country',
            'sort'          => '-ga:sessions',
            'max-results'   => '5'
        ));
    }*/

    
    /*$startDate          = '30daysAgo';
    $endDate            = 'today';
    $metrics            = 'ga:sessions,ga:pageviews';

    $data2[0] = $analytics->data_ga->get($analyticsViewId, $startDate, $endDate, $metrics, array(
        'dimensions'    => 'ga:country',
        'sort'          => '-ga:sessions',
        'max-results'   => '5'
    ));*/

    $data3 = $profileInfo;

    $data = [];

    $data = [$data1, $data2, $data3];

    return $data;
}

/*function printResults($results) {
    // Parses the response from the Core Reporting API and prints
    // the profile name and total sessions.
    if (count($results[0]->getRows()) > 0) {
        //dd($results[4]);
        // Get the profile name.
        $profileName = $results[0]->getProfileInfo()->getProfileName();

        // Get the entry for the first entry in the first row.
        //$rows = $results[0]->getRows();
        //$sessions = $rows[0][0];

        $last_week = $results[0]['totalsForAllResults'];
        $two_weeks_ago = $results[1]['totalsForAllResults'];
        $three_weeks_ago = $results[2]['totalsForAllResults'];
        $four_weeks_ago = $results[3]['totalsForAllResults'];
        $by_country = $results[4]['rows'];
        //dd($by_country);
        //dd($results);
        //return $results;

    } else {
        print "No results found.\n";
    }
}*/