<?php

namespace Classes;

class Hero extends Character {

    /**
     * @property $stats
     */
    public $stats = [
        'health' => [70, 100],
        'strength' => [70, 80],
        'defence' => [45, 55],
        'speed' => [40, 50],
        'luck' => [10, 30],
    ];

    public $skills = [
        'attack' => [
            [
                'name' => 'Rapid Strike', // Name of spell
                'additional' => 1, // value
                'type' => 'multiplied', // how will be calculated the additional damage - types: plus, multiplied, percent
                'chance' => 10 // changes to use this spell
            ],
        ],
        // defend skills can only reduce enemy strength, can be used just for basic strength
        'defend' => [
            [
                'name' => 'Magic Shield',
                'additional' => 50,
                'type' => 'percent',
                'chance' => 20
            ]
        ]
    ];

    public function __construct() {
        $this->configClass($this->stats);
    }

    /**
     * @param null $specific
     * @return array | int
     */
    public function getStats($specific = null) {
        return $specific === null ? $this->stats : $this->stats[$specific];
    }

    /**
     * @param $specific
     * @param $value
     */
    public function setStats($specific, $value) {
        $this->stats[$specific] = $value;
    }

    /**
     * @param $damage
     */
    public function receiveDamage($damage) {
        $this->stats['health'] -= $damage;
    }

}
