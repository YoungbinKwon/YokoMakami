<?php

class NaturalLanguageClassifier {

    private $url;
    private $username;
    private $password;

    public function __construct()
    {
        $this->url_base = 'https://gateway.watsonplatform.net/natural-language-classifier/api/v1/classifiers/90e7acx197-nlc-4755/classify/';
        $this->username = NLCUSERNAME;
        $this->password = NLCPASSWORD;
    }

    public function divideWordsByIgo($phrase)
    {
        $igo = new Igo('./Lib/ipadic', 'UTF-8');
        $analized_data = $igo->parse($phrase);
        $i = 0;
        $words[0] = "";

        foreach ($analized_data as $each_analized_data) {
            $exploded_feature = explode(',', $each_analized_data->feature);
            $word = $each_analized_data->surface;

            $part_of_speech = $exploded_feature[0];
            if ($word == "時" || $word == "人") {
                $words[$i] .= $word;
            } elseif (($part_of_speech == "名詞") && ($word != 'ゴルフ' && $word != '場' && $word != '県')) {
                if ($words[$i] != '') {
                    $i++;
                    $words[$i] = "";
                }
                $words[$i] .= $word;
            } else {
                if ($words[$i] != '') {
                    $i++;
                    $words[$i] = "";
                }
            }
        }
        return $words;
    }
    
    public function classifyWords($words)
    {
        $class_results = [];
        foreach ($words as $each_word) {
            if ($each_word != '') {
                $class = $this->classify($each_word);
                $confidence = $class['classes'][0]['confidence'];
                $class_code = explode('-', $class['classes'][0]['class_name']);
                if (isset($class_results[$class_code[0]])) {
                    if ($class_results[$class_code[0]]['confidence'] < $confidence) {
                        $class_results[$class_code[0]]['text'] = $class_code[1];
                        $class_results[$class_code[0]]['confidence'] = $confidence;
                    }
                } elseif ($confidence > 0.4) {
                    $class_results[$class_code[0]]['text'] = $each_word;
                    $class_results[$class_code[0]]['code'] = $class_code[1];
                    $class_results[$class_code[0]]['confidence'] = $confidence;
                }
            }
        }

        return $class_results;
    }

    private function classify($data)
    {
        $ch = curl_init();
        $url = $this->url_base . "?text=" . urlencode($data);
        $params = array(
            CURLOPT_URL => $url,
            CURLOPT_USERPWD => $this->username . ":" . $this->password,
            CURLOPT_RETURNTRANSFER => true
        );

        curl_setopt_array($ch, $params);
        $result = curl_exec($ch);
        curl_close($ch);
        $decoded = json_decode($result, true);
        
        return $decoded;
    }
}
