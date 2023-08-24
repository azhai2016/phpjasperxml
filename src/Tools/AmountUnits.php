<?php

class AmountUtils {
    private static $UNITS = array('零', '壹', '贰', '叁', '肆', '伍', '陆', '柒', '捌', '玖');
    private static $TENS = array('', '拾', '佰', '仟');
    private static $THOUSANDS = array('', '万', '亿');

    public static function convertToChinese($amount) {
        $amount = number_format(round($amount, 2), 2, '.', '');
        
        list($integer, $decimal) = explode('.', $amount);
        
        if (intval($integer) === 0 && intval($decimal) === 0) {
            return '零元整';
        }
        
        $integerPart = self::convertIntegerToChinese($integer);
        $decimalPart = self::convertDecimalToChinese($decimal);
        
        $chineseAmount = $integerPart . '元';
        
        if ($decimalPart != '') {
            $chineseAmount .= $decimalPart;
        } else {
            $chineseAmount .= '整';
        }
        
        return $chineseAmount;
    }

    private static function convertIntegerToChinese($integer) {
        $integer = strrev($integer);
        $length = strlen($integer);
        $chineseAmount = '';
        
        for ($i = 0; $i < $length; $i += 4) {
            $chunk = strrev(substr($integer, $i, 4));
            
            if (!intval($chunk)) {
                continue;
            }
            
            $chunkAmount = '';
            
            for ($j = 0; $j < strlen($chunk); $j++) {
                $digit = intval($chunk[$j]);
                
                if ($digit === 0) {
                    if ($j === 0 || ($j < 3 && $chunk[$j + 1] !== '0')) {
                        $chunkAmount .= self::$UNITS[$digit];
                    }
                } else {
                    $chunkAmount .= self::$UNITS[$digit] . self::$TENS[$j];
                }
            }
            
            $chineseAmount = $chunkAmount . self::$THOUSANDS[$i / 4] . $chineseAmount;
        }
        
        return $chineseAmount;
    }
    
    private static function convertDecimalToChinese($decimal) {
        $decimalAmount = '';
        
        for ($i = 0; $i < strlen($decimal); $i++) {
            $digit = intval($decimal[$i]);
            
            if ($digit != 0) {
                $decimalAmount .= self::$UNITS[$digit] . '角';
            }
            
            if ($i === 1 && $digit === 0) {
                 break;
            }
        }
        
        return $decimalAmount;
    }
}
