<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class Filter {

    protected $_request, $_builder, $_filters;

    /**
     * Construct a new filter instance
     *
     * @param  {Request} $request
     * @return {void}
     */
    public function __construct(Request $request) {
        $this->_request = $request;
    }

    /**
     * Apply filters to a query builder
     *
     * @param  {Builder} $builder
     * @return {Buulder}
     */
    public function apply(Builder $builder) {
        $this->_builder = $builder;

        // Call init function if available
        if (method_exists($this, 'init')) {
            $this->init();
        }

        // For each filter
        foreach ($this->filters() as $name => $value) {
            // Check method exists
            if (method_exists($this, $name)) {
                if (is_array($value) || trim($value) || (trim($value) === "0") || (trim($value) === 0)) {
                    // Method takes a param and this has been provided
                    $this->$name($value);
                } else {
                    // No params, just call method
                    $this->$name();
                }
            }
        }

        // Apply default sorting if no sorting requested
        if (!array_key_exists('sort', $this->filters()) && method_exists($this, 'defaultSort')) {
            $this->defaultSort();
        }
        return $this->_builder;
    }

    /**
     * Set internal filters
     *
     * @param  {array} $filters
     * @return {void}
     */
    public function setFilters($filters) {
        $this->_filters = $filters;
    }

    /**
     * Get internal filters if available, request data otherwise
     *
     * @return {void}
     */
    public function filters() {
        return $this->_filters ? $this->_filters : $this->_request->all();
    }

    /**
     * Is a specific filter option set
     *
     * @param  {string} $filter
     * @return {bool}
     */
    public function isFilterSet($filter) {
        return (bool) ( isset($this->filters()[$filter]) && $this->filters()[$filter] && ($this->filters()[$filter] !== 'false') );
    }
}
