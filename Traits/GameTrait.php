<?php

namespace Traits;

trait GameTrait {

    protected function decidePositions() {

        $heroStats = $this->hero->getStats();
        $bestStats = $this->beast->getStats();
        $checkSpeedGrater = $heroStats['speed'] > $bestStats['speed'];
        $checkLuckGrater = $heroStats['luck'] > $bestStats['luck'];

        if ($checkSpeedGrater) {
            $this->attacker = 'hero';
            $this->defender = 'beast';
        } elseif (!$checkSpeedGrater) { //TODO de verificat in cazul in care viteza este egala
            $this->attacker = 'beast';
            $this->defender = 'hero';
        } else {
            if ($checkLuckGrater) {
                $this->attacker = 'hero';
                $this->defender = 'beast';
            } elseif (!$checkLuckGrater) {
                $this->attacker = 'beast';
                $this->defender = 'hero';
            } else {
                $this->attacker = 'hero';
                $this->defender = 'beast';
            }
        }

        $this->firstPosition = true;

    }

    /**
     * @return string
     */
    protected function attack() {

        $attacker = $this->attacker;
        $defender = $this->defender;
        $attackerBasicStrength = $this->$attacker->getStats('strength');
        $defenderBasicDefence = $this->$defender->getStats('defence');

        // Prepare attacker skill
        if ($this->$attacker->skills !== null) {
            // Select skill
            $attackerSkill = $this->$attacker->skillEffect($this->$attacker->skills, 'attack');

            // Use skill if we find one
            if ($attackerSkill !== null) {
                // Set skill strength
                $attackerSkillStrength = $this->setSkill($attackerSkill, $attackerBasicStrength);
            }

        }

        // Prepare defender skill
        if ($this->$defender->skills !== null) {
            $defenderSkill = $this->$defender->skillEffect($this->$defender->skills, 'defend');

            if ($defenderSkill !== null) {
                $attackerLessStrength = $this->setSkill($defenderSkill, $attackerBasicStrength);
            }
        }

        if (isset($attackerLessStrength) || isset($attackerSkillStrength)) {
            $attackerSkillStrength = isset($attackerSkillStrength) ? $attackerSkillStrength : 0;

            // calculate total strength with or without skill
            if (isset($attackerLessStrength)) {
                $attackerTotalStrength = ($attackerBasicStrength + $attackerSkillStrength) - $attackerLessStrength;
            } elseif (isset($attackerSkillStrength)) {
                $attackerTotalStrength = $attackerBasicStrength + $attackerSkillStrength;
            }

            $attackerTotalStrength = $attackerTotalStrength < 0 ? 0 : $attackerTotalStrength;

            $damageLog = ucfirst($defender) . " a folosit \e[31m" . $defenderSkill['name'] . "\e[0m \n";
        } else {
            $attackerTotalStrength = $attackerBasicStrength;
        }

        $damage = $this->calculateDamage($attackerTotalStrength, $defenderBasicDefence);
        $this->$defender->receiveDamage($damage);
        $damageLog .= ucfirst($attacker) . " a dat \e[31m" . $damage . "\e[0m damage in " . ucfirst($defender) . "\n";

        // Log for skill
        if (isset($attackerSkillStrength) && $attackerSkillStrength !== 0) {
            $damageLog .= ucfirst($attacker) . " a folosit  \e[31m" . $attackerSkill['name'] ."\e[0m \n";
        }

        if ($this->$defender->getStats('health') > 0) {
            $damageLog .= ucfirst($defender) . ' a mai are doar ' . $this->$defender->getStats('health') . " de puncte de viata ramase. \n";
        } else {
            $damageLog .= ucfirst($attacker) . " a dat lovitura decisiva. :D \n";
        }

        $this->attacker = $defender;
        $this->defender = $attacker;

        return $damageLog;

    }

    /**
     * @param $attacker
     * @param $defender
     * @return float
     */
    protected function calculateDamage($attacker, $defender) {
        $damage = round(max($attacker, $defender) - min($attacker, $defender));
        return $damage;
    }

    /**
     * @param $skill
     * @param $basicStat
     * @return float
     */
    protected function setSkill($skill, $basicStat) {
        $skillAdditional = 0;
        switch ($skill['type']):
            case 'multiplied':
                $skillAdditional = $basicStat * $skill['additional'];
                break;
            case 'plus':
                $skillAdditional = $skill['additional'];
                break;
            case 'percent':
                $skillAdditional = ($skill['additional'] / 100) * $basicStat;
                break;
        endswitch;

        return round($skillAdditional);
    }

}
