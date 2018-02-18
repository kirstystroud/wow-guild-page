<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class Filter {
    
    protected $_request, $_builder, $_filters;
    
    public function __construct(Request $request) {
        $this->_request = $request;
    }
    
    public function apply(Builder $builder) {
        $this->_builder = $builder;

        if (method_exists($this, 'init')) {
            $this->init();
        }

        foreach ($this->filters() as $name => $value) {
            if(method_exists($this, $name)) {
                if (is_array($value) || trim($value) || (trim($value) === "0") || (trim($value) === 0)) {
                    $this->$name($value);
                } else {
                    $this->$name();
                }
            }
        }

        if(!array_key_exists('sort', $this->filters()) && method_exists($this, 'defaultSort')) {
            $this->defaultSort();
        }
        return $this->_builder;
    }
    
    public function setFilters($filters) {
        $this->_filters = $filters;
    }
    
    public function filters() {
        return $this->_filters ? $this->_filters : $this->_request->all();
    }
}