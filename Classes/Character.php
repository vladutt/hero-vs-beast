<?php

namespace Classes;

use Exception;

abstract class Character {

    abstract function getStats();

    // Base types
    private $skillTypes = [
                'plus',
                'multiplied',
                'percent'
            ];

    /**
     * @param $stats
     */
    public function configClass(&$stats) {

        foreach ($stats as $key => $value) {
            $stats[$key] = rand($value[0], $value[1]);
        }

    }

    /**
     * @param $skills
     * @param $type // attack OR defend
     * @return array
     */
    public function skillEffect($skills, $type) {

        $selectedSkill = $this->selectSkill($skills, $type);

        if (rand(1, 100) >= $selectedSkill['chance']) {
            return null;
        }

        return [
            'name' => $selectedSkill['name'],
            'additional' => $selectedSkill['additional'],
            'type' => $selectedSkill['type'],
        ];

    }

    /**
     * @param $skills
     * @param $type // attack OR defend
     * @return mixed
     */
    private function selectSkill($skills, $type) {
        if (!isset($skills[$type])) {
           return null;
        }

        $skills = $skills[$type];
        $skill = $skills[rand(0, count($skills)-1)]; // select a random skill
        return $skill;
    }


}
