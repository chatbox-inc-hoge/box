<?php

/*
 * This file is part of Pimple.
 *
 * Copyright (c) 2009 Fabien Potencier
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is furnished
 * to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace Chatbox\Box;

use Chatbox\Box\Basic;
use Chatbox\Box\ServiceProviderInterface;

/**
 * Box - Clean Container Rapper
 * You Need to Extend this class Since there're no public setter
 *
 * @author  Fabien Potencier
 */

class Box{
    /**
     * @var Basic
     */
    private $box;

    /**
     * you need to call this in constractor
     */
    protected function configure(){
        $this->box = new Basic();
    }

    public function get($name)
    {
        return $this->box[$name];
    }

    protected function register($setKey,$getKey,$provider){
        $this->box[$setKey] = function()use($getKey,$provider){
            $args = [];
            // pimple3.0.0 系からはオンデマンドのファクトリー
            // register時点ではサービスの名前解決はされていない事が多いので
            // この時点でのコールでないと困る
            foreach($getKey as $key){
                $args[$key] = $this->box[$key];
            }
            if(is_callable($provider)){
                return call_user_func_array($provider,$args);
            }else{
                return $provider;
            }
        };
    }
}