<?php
/**
 * Date: 2019/11/22
 * Time: 13:38
 * 策略模式
 * 将一组特么的行为或算法封装成类，以适应特定的上下文环境
 *
 * 比如当实例化控制器的时候，我们想要执行某个模块下的所有控制器的前和后，以前是用__construct 和__destruct
 * 我们可以自己定义方法，把两个行为独立封装成类，执行模块下的控制前，都会执行此类行为和方法
 */

namespace core;

interface Init
{
    public function init();

    public function before();

    public function after();

}