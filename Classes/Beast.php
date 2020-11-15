<?php

namespace Classes;

class Beast extends Character {

    /**
     * Hero stats range
     */
    public $stats = [
        'health' => [60, 90],
        'strength' => [60, 90],
        'defence' => [40, 60],
        'speed' => [40, 60],
        'luck' => [25, 40],
    ];

    public $skills = null;

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

    public function setStats($specific, $value) {
        $this->stats[$specific] = $value;
    }

    public function receiveDamage($damage) {
        $this->stats['health'] -= $damage;
    }
}
