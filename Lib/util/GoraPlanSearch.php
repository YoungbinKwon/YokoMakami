<?php

class GoraPlanSearch {

    private $url_base;
    private $appl_id;

    public function __construct()
    {
        $this->url_base = 'https://app.rakuten.co.jp/services/api/Gora/GoraPlanSearch/20150706?format=json';
        $this->appl_id = GORAAPPID;
    }

    public function getPlan($data)
    {
        $ch = curl_init();
        $url = $this->url_base . "&applicationId=" . $this->appl_id;

        foreach ($data as $key => $value) {
            $url .= "&" . $key . "=" . $value;
        }

        $params = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true
        );

        curl_setopt_array($ch, $params);
        $result = curl_exec($ch);
        curl_close($ch);
        $decoded = json_decode($result, true);
        
        return $decoded;
    }

    public function setParam($data)
    {
        $params = ['areaCode'=> 13,'playDate' => date("Y-m-d",strtotime("+1 week"))];
 
        foreach ($data as $key => $value) {
            switch ($key) {
                case "date":
                    $param['playDate'] = $this->setDate($value);
                default:
                    $params[$key] = $value['code'];
            }
        }

        return $params;
    }

    private function setDate($date)
    {
        switch ($date) {
            case "today":
                $param = date("Y-m-d");
            case "tomorrow":
                $param = date("Y-m-d",strtotime("+1 day"));
            case "2day":
                $param = date("Y-m-d",strtotime("+1 day"));
            case "nextweek":
                $param = date("Y-m-d",strtotime("+1 week"));
            case "twoweek":
                $param = date("Y-m-d",strtotime("+2 week"));
            case "nextmonth":
                $param = date("Y-m-d",strtotime("+1 month"));
            case "twomonth":
                $param = date("Y-m-d",strtotime("+2 month"));
            default:
                $param = date("Y-m-d",strtotime("+1 week"));
        }

        return $param;
    }

}
