<?php
/**
 * Created by Nguyen Tien Dat.
 * Date: 10/19/13
 */
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Sr_private_billing_address_m extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->_table = 'sr_private_billing_address';
    }
}