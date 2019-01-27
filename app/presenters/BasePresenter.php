<?php

namespace App\Presenters;

use Nette;

/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter {

    
    /**
     * @example array('hello','hi')
     * @param type array $arr
     * @return true/false
     */
    public function isQueryunsec($arr) {
        $n = 0;
        foreach ($arr as $item => $value) {
            if (preg_match('/([";\'´`\/]|delete)+/mi', $value)) {
                $n = 1;
            }
        }
        return ($n == 1)? true:false;
    }
}
