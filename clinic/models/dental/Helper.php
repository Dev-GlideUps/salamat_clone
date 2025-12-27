<?php

namespace clinic\models\dental;

class Helper
{
    public static function chartClasses(Array $records)
    {
        $result = [
            1 => [],
            2 => [],
            3 => [],
            4 => [],
            5 => [],
            6 => [],
            7 => [],
            8 => [],
            9 => [],
            10 => [],
            11 => [],
            12 => [],
            13 => [],
            14 => [],
            15 => [],
            16 => [],
            17 => [],
            18 => [],
            19 => [],
            20 => [],
            21 => [],
            22 => [],
            23 => [],
            24 => [],
            25 => [],
            26 => [],
            27 => [],
            28 => [],
            29 => [],
            30 => [],
            31 => [],
            32 => [],
        ];

        $total = count($records);

        for ($i = $total - 1; $i >= 0; $i--) {
            $item = $records[$i];

            if (!$item instanceof Record) {
                throw new \yii\base\UnknownClassException('Record item must be an instance of clinic\models\dental\Record');
            }

            if (strtotime($item->procedure_date) > strtotime(date('Y-m-d'))) {
                continue;
            }

            $tooth = $item->teeth;
            switch ($item->cssClass) {
                case 'clean':
                case 'filled':
                case 'root-canal':
                case 'braces':
                case 'artificial':
                case 'crown':
                case 'removed':
                    if (!in_array($item->cssClass, $result[$tooth])) {
                        $result[$tooth][] = $item->cssClass;
                    }
                break;
                case 'implant':
                    if (!in_array($item->cssClass, $result[$tooth])) {
                        $toRemove = ['root-canal', 'filled', 'crown', 'removed'];
                        for ($x = 0; $x < 4; $x++) {
                            $key = array_search($toRemove[$x], $result[$tooth]);
                            if ($key !== false) {
                                unset($result[$tooth][$key]);
                            }
                        }

                        $result[$tooth][] = $item->cssClass;
                    }
                break;
            }
        }

        return $result;
    }
}