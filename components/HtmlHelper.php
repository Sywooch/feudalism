<?php

namespace app\components;

use yii\helpers\Html;

class HtmlHelper extends Html {

    public static function customIcon($file, $title = '', $style = '', $attrs = [])
    {
        $attrs_str = '';
        if (is_array($attrs)) {
            foreach ($attrs as $key => $value) {
                $value = stripslashes(htmlspecialchars($value));
                $attrs_str .= " {$key}='{$value}' ";
            }
        } else {
            $attrs_str .= $attrs;
        }
        return "<img src='/img/{$file}.png' alt='{$title}' title='{$title}' style='{$style}' {$attrs_str} />";
    }

    /**
     * Функция которая возвращает правильное русское форматирование слов, стоящие после чисел
     * Например 0 комментариев, 1 комментарий, 2 комментария
     * 
     * @param integer $n число
     * @param string $s0 вариант для 0
     * @param type $s1 вариант для 1
     * @param type $s2 вариант для 2
     * @return string строка в правильного вида.
     */
    public static function formateNumberword($n, $s0, $s1 = false, $s2 = false)
    {
        if (!$s1) {
            $s1 = $s0;
        }
        if (!$s2) {
            $s2 = $s0;
        }

        $pref = ($n < 0) ? '-' : '';
        $n = abs($n);
        if ($n === 0) {
            return '0 ' . $s0;
        } else if ($n === 1 || ($n % 10 === 1 && $n % 100 != 11 && $n != 11)) {
            return $pref . number_format($n, 0, '', ' ') . ' ' . $s1;
        } else if ($n > 100 && $n % 100 >= 12 && $n % 100 <= 14) {
            return $pref . number_format($n, 0, '', ' ') . ' ' . $s0;
        } else if (($n % 10 >= 2 && $n % 10 <= 4 && $n > 20) || ($n >= 2 && $n <= 4)) {
            return $pref . number_format($n, 0, '', ' ') . ' ' . $s2;
        } else {
            return $pref . number_format($n, 0, '', ' ') . ' ' . $s0;
        }
    }

    // число в 16-ричный вид и строку с ведущим нулем
    public static function string16($n)
    {
        return ($n < 16 ? '0' : '') . dechex($n);
    }

    // Транслитерация строк.
    public static function transliterate($st)
    {
        $cyr = array('а', 'б', 'в', 'г', 'д', 'e', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ь', 'ю', 'я', 'А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ь', 'Ю', 'Я', 'ў', 'ґ', 'ї', 'Ў', 'Ґ', 'Ї', 'ы', 'Ы', 'ё', 'Ё', 'э', 'Э');
        $lat = array('a', 'b', 'v', 'g', 'd', 'e', 'zh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'ts', 'ch', 'sh', 'sht', 'a', 'y', 'yu', 'ya', 'A', 'B', 'V', 'G', 'D', 'E', 'Zh', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'Ts', 'Ch', 'Sh', 'Sht', 'A', 'Y', 'Yu', 'Ya', 'w', 'g', 'yi', 'W', 'G', 'Yi', 'y', 'Y', 'yo', 'Yo', 'e', 'E');
        return str_replace($cyr, $lat, $st);
    }

    /**
     * 
     * @param string $link
     * @return boolean
     */
    public static function isImageLink($link)
    {
        $ar = explode('.', trim($link));
        
        return (is_array($ar) && count($ar)>1 && in_array(end($ar),['jpg','png','gif','jpeg']));
    }
    
    /**
     * 
     * @param integer $time
     * @return string
     */
    public static function timeFormatFuture($time)
    {
        $current = time();
        $time = intval($time);
        $d = $time - $current;
        
        if ($d < 60) {
            return "Осталось ".static::formateNumberword($d, "секунд", "секунда", "секунды");
        } elseif ($d < 3600) {
            return "Осталось ".static::formateNumberword(round($d/60), "минут", "минута", "минуты");
        } else {
            return "Осталось ".static::formateNumberword(round($d/3600), "часов", "час", "часа");
        }
    }

}
