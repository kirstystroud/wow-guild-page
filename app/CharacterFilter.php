<?php

namespace App;

use Filter;
use Auction;

class CharacterFilter extends Filter {

    /**
     * Handles sorting based on requirements
     *
     * @param  {array} $sorting contains column and direction
     * @return {Builder}
     */
    public function sort($sorting) {

        // Check which keys are set
        $sortKeys = array_keys($sorting);

        // Assuming only one key set here
        switch($sortKeys[0]) {
            case 'class' :
                $this->_builder = $this->_builder->select(['characters.*', 'classes.name AS class_name'])->join('classes', 'classes.id', 'characters.class_id')->orderBy('classes.name', $sorting[$sortKeys[0]]);
                break;
            case 'race' :
                $this->_builder = $this->_builder->select(['characters.*', 'races.name AS race_name'])->join('races', 'races.id', 'characters.race_id')->orderBy('races.name', $sorting[$sortKeys[0]]);
                break;
            case 'spec' :
                $this->_builder = $this->_builder->select(['characters.*', 'specs.name AS spec_name'])->leftJoin('specs', 'specs.id', 'characters.spec_id')->orderBy('specs.name', $sorting[$sortKeys[0]]);
                break;
            default :
                $this->_builder->orderBy($sortKeys[0], $sorting[$sortKeys[0]]);
        }

        // Add sorting by name if not already been asked for
        if ($sortKeys[0] !== 'name') {
            $this->_builder->orderBy('characters.name', 'asc');
        }

        $this->setFilters([ 'sort' => [$sortKeys[0] => $sorting[$sortKeys[0]]] ]);

        return $this->_builder;
    }

    /**
     * Default sorting method
     * Orders by level ascending
     *
     * @return {void}
     */
    public function defaultSort() {
        $this->_builder->orderBy('level', 'ASC')->orderBy('name', 'ASC');
        $this->setFilters([ 'sort' => ['level' => 'asc'] ]);
    }
}
