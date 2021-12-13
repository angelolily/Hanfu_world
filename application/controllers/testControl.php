<?php


class testControl extends CI_Controller
{

	function __construct()
	{
		parent::__construct();	
        $this->load->helper('qrcode');
        $this->load->helper('tool');
	}

    public function testQR()
    {

       $ssf['path']= getCode("pages/detail/match","sid","HTY60efdd806cef18.68669983,42");//报名
       http_data(200, $ssf, $this);

    }







}
