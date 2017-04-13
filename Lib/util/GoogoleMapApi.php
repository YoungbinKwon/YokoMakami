<?php

class GoogleMapApi {

    private $url_base;

    public function __construct()
    {
        $this->url_base = 'https://maps.googleapis.com/maps/api/directions/json?';
    }

    public function getAllDistance($search_data)
    {
        $result = [];
        foreach ($search_data as $course_id => $position) {
            $result[] = $this->getDistance($position['from'], $position['to']);
        }
        return $result;
    }

    private function getDistance($from, $to)
    {
        $url = $this->url_base . "origin=" . $from . "&destination=" . $to;
        $googleMapsApiData = json_decode(@file_get_contents($url), true);
        $distance = $googleMapsApiData['routes'][0]['legs'][0]['duration']['value']
        return $distance;
    }
}
