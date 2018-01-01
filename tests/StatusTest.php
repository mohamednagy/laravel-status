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

    /** @test */
    public function it_can_update_status_from_pending_to_approved_using_query_builder()
    {
        User::truncate();
        
        $this->seedUsers(5, 0); // pendiging status
        $this->seedUsers(5, 1); // approved status
        $this->seedUsers(5, 2); // rejected status

        $this->assertCount(5, User::onlyPending()->get());
        $this->assertCount(5, User::onlyApproved()->get());
        $this->assertCount(5, User::onlyRejected()->get());

        User::where('status', 0)->setApproved();

        $this->assertCount(0, User::onlyPending()->get());
        $this->assertCount(10, User::onlyApproved()->get());
        $this->assertCount(5, User::onlyRejected()->get());
    }

    /** @test */
    public function it_can_update_status_from_approved_to_rejected_using_query_builder()
    {
        User::truncate();

        $this->seedUsers(5, 0); // pendiging status
        $this->seedUsers(5, 1); // approved status
        $this->seedUsers(5, 2); // rejected status

        $this->assertCount(5, User::onlyPending()->get());
        $this->assertCount(5, User::onlyApproved()->get());
        $this->assertCount(5, User::onlyRejected()->get());

        User::where('status', 0)->setRejected();

        $this->assertCount(0, User::onlyPending()->get());
        $this->assertCount(5, User::onlyApproved()->get());
        $this->assertCount(10, User::onlyRejected()->get());
    }

    /** @test */
    public function it_can_get_only_approved_using_using_query_builder()
    {
        User::truncate();

        $this->seedUsers(5, 0); // pendiging status
        $this->seedUsers(5, 1); // approved status
        $this->seedUsers(5, 2); // rejected status
        
        $approved = User::where('id', '>', 10)->onlyApproved()->get();
        $this->assertCount(0, $approved);
    }

    /** @test */
    public function it_can_handle_snake_case_status()
    {
        User::truncate();

        $this->seedUsers(5, 0); // pendiging status
        $this->seedUsers(5, 1); // approved status
        $this->seedUsers(5, 2); // rejected status
        $this->seedUsers(5, 3); // snake_case status
        
        $users = User::onlySnakeCase()->get();
        $this->assertCount(5, $users);
    }


}