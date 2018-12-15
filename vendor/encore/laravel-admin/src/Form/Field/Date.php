<?php

namespace Encore\Admin\Form\Field;

use Carbon\Carbon;

class Date extends Text
{
    protected static $css = [
        '/packages/admin/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css',
    ];

    protected static $js = [
        '/packages/admin/moment/min/moment-with-locales.min.js',
        '/packages/admin/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js',
    ];

    protected $format = 'YYYY-MM-DD';
    protected $dataType = 'timestamp' ;

    public function format($format)
    {
        $this->format = $format;

        return $this;
    }
    
    public function dataType ( $type = 'timestamp' ) {
    	$this->dataType = $type  ;
    }

    public function prepare($value)
    {
        if ( !$value ) {
        	if( $this->dataType == 'timestamp') {
        		$value = "0000-00-00 00:00:00" ;
        		$value = null ;
        	} elseif( $this->dataType == 'int' ) {
        		$value = 0 ;
        	} else if( $this->dataType == 'text' ) {
        		$value = '' ;
        	}
        }
		
        return $value;
    }

    public function render()
    {
        $this->options['format'] = $this->format;
        $this->options['locale'] = config('app.locale');

        $this->script = "$('{$this->getElementClassSelector()}').datetimepicker(".json_encode($this->options).');';

        $this->prepend('<i class="fa fa-calendar"></i>')
            ->defaultAttribute('style', 'width: 160px');

        return parent::render();
    }
}
