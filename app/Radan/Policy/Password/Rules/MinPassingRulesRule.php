<?php

namespace App\Radan\Policy\Password\Rules;

use Closure;
use App\Radan\Policy\Password\Policy;
use App\Radan\Policy\Password\PolicyBuilder;
use App\Radan\Policy\Password\Rule;

/**
 * Class MinPassingRulesRule
 *
 * @package PasswordPolicy\Rules
 */
class MinPassingRulesRule implements Rule
{
    /**
     * @var Closure
     */
    protected $test;

    /**
     * Number of passing rules required
     *
     * @var int
     */
    protected $passesRequired;

    /**
     * MinPassingRulesRule constructor.
     *
     * @param $passesRequired
     */
    public function __construct($passesRequired)
    {
        $this->passesRequired = $passesRequired;
    }

    /**
     * Set of rules to apply the passes required check to
     *
     * @param Closure $test
     *
     * @return $this
     */
    public function using(Closure $test)
    {
        $this->test = $test;

        return $this;
    }

    /**
     * Test a rule
     *
     * @param $subject
     *
     * @return bool
     */
    public function test($subject)
    {
        call_user_func($this->test, $builder = $this->createBuilder());

        $passedRules = 0;

        /** @var Rule $rule */
        foreach ($builder->getPolicy()->rules() as $rule) {
            if ($rule->test($subject) === true) {
                $passedRules++;
            }
        }

        return $passedRules >= $this->passesRequired;
    }

    /**
     * Create the policy builder to nest
     *
     * @return PolicyBuilder
     */
    private function createBuilder()
    {
        return new PolicyBuilder(new Policy);
    }
}
