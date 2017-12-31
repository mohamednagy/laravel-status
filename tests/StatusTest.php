<?php

namespace Nagy\LaravelStatus\Tests;

use Nagy\LaravelStatus\Tests\TestCase;
use Nagy\LaravelStatus\Tests\Models\User;
use Nagy\LaravelStatus\Exceptions\StatusNotExists;

class StatusTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    /** @test */
    public function model_has_the_default_status()
    {
        $user = $this->seedUser(0); // 0 is the default status

        $this->assertTrue($user->isPending());
    }

    /** @test */
    public function it_returns_all_pending_models()
    {
        $user = $this->seedUsers(5, 0); // pendiging status
        $user = $this->seedUsers(5, 1); // approved status

        $this->assertCount(5, User::onlyPending()->get());
    }

    /** @test */
    public function it_should_not_return_models_with_status_approved()
    {
        $user = $this->seedUsers(5, 0); // pendiging status
        $user = $this->seedUsers(5, 1); // approved status

        $this->assertCount(5, User::onlyApproved()->get());
    }

    /** @test */
    public function it_can_update_model_status()
    {
        $user = $this->seedUser(0); // 0 is the default status
        $this->assertTrue($user->isPending());

        $user->setApproved();
        $this->assertTrue($user->isApproved());

    }


}