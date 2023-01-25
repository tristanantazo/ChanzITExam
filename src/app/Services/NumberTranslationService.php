<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class NumberTranslationService
{
    public $orderOfMagnitute = [
        '_',
        'thousand',
        'million',
        'billion',
        'trillion',
        'quadrillion'
    ];

    public $ones = [
        0 => '',
        1 => 'one',
        2 => 'two',
        3 => 'three',
        4 => 'four',
        5 => 'five',
        6 => 'six',
        7 => 'seven',
        8 => 'eight',
        9 => 'nine',
        10 => 'ten',
        11 => 'eleven',
        12 => 'twelve',
        13 => 'thirteen',
        14 => 'fourteen',
        15 => 'fifteen',
        16 => 'sixteen',
        17 => 'seventeen',
        18 => 'eighteen',
        19 => 'nineteen',
    ];

    public $tens = [
        00 =>'',
        10 => 'ten',
        20 => 'twenty',
        30 => 'thirty',
        40 => 'forty',
        50 => 'fifty',
        60 => 'sixty',
        70 => 'seventy',
        80 => 'eightty',
        90 => 'ninety',
    ];

    public $translatedNumberToWord;

    public function numberTranslation($request)
    {
        $number = $request['number'];
        $formattedNumber = number_format($number,2,".",",");
        return self::numberPreparation($formattedNumber);
    }   
    
    public function numberPreparation($number)
    {
        $translatedArrayNumber = [];

        //catch the decimal number array with decimal $number[0] for whole, $number[1] for decimal
        $numberOfArray = explode(".",$number);
        //for whole number groub by coma
        $explodedWholeNumber = explode(",",$numberOfArray[0]);
        // count the generated array based on comma
        $numberComaCount = count($explodedWholeNumber);
        
        // for each array value it will call the number of set that will trigger the converstion
        foreach ($explodedWholeNumber as $key => $numberValue) {
            $numberComaCount -= 1;
            $word = self::findNumberOfSet($numberValue);
            if($numberComaCount > 0) {
                $word = $word . ' ' . $this->orderOfMagnitute[$numberComaCount];
            };
            array_push($translatedArrayNumber, $word);
        }
        
        $translatedWord = implode(" ", $translatedArrayNumber) . self::catchDecimal($numberOfArray[1]);

        return $translatedWord;
    }

    public function findNumberOfSet($numberValue)
    {
        $arrayOfNumber = array_map('intval', str_split($numberValue));

        switch (count($arrayOfNumber)) {
            case 3:
                # code...
                return self::setHundreds($arrayOfNumber);
                break;
            case 2:
                # code...
                return self::setTens($arrayOfNumber);
                break;
            default:
                return self::setOnes($arrayOfNumber);
                # code...
                break;
        }
    }

    public function catchDecimal($number)
    {
        return $number[1] == "00" ? '' : ' and ' . self::findNumberOfSet($number) . ' cents';
    }

    public function setHundreds($number)
    {
        //set hundred
        $hundred = $this->ones[$number[0]] . ' ';
        $tens = $hundred . 'hundred' . ' ';
        return $tens . self::setSpecialTens([$number[1],$number[2]]);
    }

    public function setTens($number)
    {
        return self::setSpecialTens([$number[0],$number[1]]);
    }
    
    public function setOnes($number)
    {
        return $this->ones[$number[0]];
    }

    public function setSpecialTens($number)
    {
        if($number[0] == 1) {
            return $this->ones[intval(implode("",$number))];
        }
        $space = $number[0] == 0 ? '' : ' ';
        $ones = $this->tens[$number[0] . 0] . $space;
        return $ones . $this->ones[$number[1]];
    }

    public function separateString($string)
    {
        $arrayString = [];
        $explodeNumberFlag = 1;

        foreach ($this->orderOfMagnitute as $key => $value) {
            $stringExplode = explode($value,$string);
            if(array_key_exists($key+1, $this->orderOfMagnitute)){
                if(array_key_exists($explodeNumberFlag,$stringExplode)){
                    array_push($arrayString, $stringExplode[1]);
                }
            }else {
                array_push($arrayString, $stringExplode[0]);
            }
            $string = $stringExplode[0];
        }
        return $arrayString;
    }

    public function wordTranslation($request)
    {
        $string = $request['word'];
        
        $reversedArray = array_reverse(self::separateString($string));
        $convertedNumberArray = [];
        foreach ($reversedArray as $key => $value) {
            $wordArray = [];

            $position = strpos($value, 'hundred');

            if($position !== false) {
                //ones
                foreach ($this->ones as $oneskey => $onesword) {
                    $position = strpos($value, $onesword);
                    if($position !== false && $onesword !== "") array_push($wordArray, ['position'=>$position, 'number'=>$oneskey, 'word'=>$onesword]);
                }
                //tens
                foreach ($this->tens as $tenskey => $tensword) {
                    $position = strpos($value, $tensword);
                    if($position !== false && $tensword !== "") array_push($wordArray, ['position'=>$position, 'number'=>$tenskey, 'word'=>$tensword]);
                }
                
                //ones
                $offset = $wordArray[1]['position']+strlen($wordArray[1]['word']);
                foreach ($this->ones as $oneskey => $onesword) {
                    $position = strpos($value, $onesword, $offset);
                    if($position !== false && $onesword !== "") array_push($wordArray, ['position'=>$position, 'number'=>$oneskey, 'word'=>$onesword]);
                }
            }else{
                foreach ($this->tens as $tenskey => $tensword) {
                    $position = strpos($value, $tensword);
                    if($position !== false && $tensword !== "") array_push($wordArray, ['position'=>$position, 'number'=>$tenskey, 'word'=>$tensword]);
                }
                
                //ones
                if(array_key_exists('0', $wordArray)){
                    $offset = $wordArray[0]['position']+strlen($wordArray[0]['word']);  
                }
                else {
                    $offset = 0;
                }
                foreach ($this->ones as $oneskey => $onesword) {
                    $position = strpos($value, $onesword, $offset);
                    if($position !== false && $onesword !== "") array_push($wordArray, ['position'=>$position, 'number'=>$oneskey, 'word'=>$onesword]);
                }
            }

            array_push($convertedNumberArray, self::positionOfWordtoNumbers($wordArray));
        }

        $formattedNumber = number_format(implode("", $convertedNumberArray),2,".",",");

        return $formattedNumber;
    }

    public function positionOfWordtoNumbers($wordArray)
    {
        $key_values = array_column($wordArray, 'position'); 
        
        array_multisort($key_values, SORT_NUMERIC, $wordArray);
        
        $wordArray = self::wordToNumberTensSpecialCase($wordArray);
        $format = [];
        foreach ($wordArray as $key => $d) {
            $number = $d['number'];
            if(array_key_exists($d['number'], $this->tens) && array_key_exists($key+1, $wordArray)) {
                $number = $wordArray[$key+1]['number'] + $d['number'];
                array_push($format, $number);
                break;
            }
            array_push($format, $number);
        }
        return implode("", $format);
    }

    public function wordToNumberTensSpecialCase($array)
    {
        foreach ($array as $key => $value) {
            if($value['number'] > 9 && $value['number'] < 20) {
                array_splice($array, 1, $key-1);
                return $array;
                break;
            }
        }
        return $array;
    }

    public function converToUSD($request)
    {
        $money = 12322;

        $response = Http::get('https://api.freecurrencyapi.com/v1/latest?apikey=gubMnHN5dbMtYBZ2K4R5Y4SSlCFRaEk6Ro0FOVpx&currencies=EUR%2CUSD%2CCAD&base_currency=PHP');

        $usd = $response['data']['USD'];

        return number_format($money * $usd,2,".",",");;
    }


}