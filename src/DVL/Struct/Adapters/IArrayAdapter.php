<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DVL;

/**
 * Description of ArrayAdapter
 *
 * @author User
 */
interface IArrayAdapter {
    
    public function getByKey( $key );
    public function hasKey( $key );
    public function getAllKeys();
    
}
