<?php

namespace Thinkstudeo\Textlocal\Tests\Integration;

use Thinkstudeo\Textlocal\Tests\TestCase;
use Thinkstudeo\Textlocal\Facades\Account;
use Thinkstudeo\Textlocal\Exceptions\ApiRequestFailure;

class ManagesContactsTest extends TestCase
{
    /** @test */
    public function it_throws_an_exception_when_creating_a_group_with_a_name_that_already_exists()
    {
        $this->expectException(ApiRequestFailure::class);

        //Arrange
        Account::transactional()->createGroup('Customers')->group->id;
        $this->assertTrue(Account::transactional()->groupExists('Customers'));
        $this->assertCount(1, Account::transactional()->groups());

        //Act
        Account::transactional()->createGroup('Customers');

        //Assert
        $this->assertCount(1, Account::transactional()->groups());

        //Cleanup
        //TODO: Remember to manually delete the Customers group or in the next test start
    }

    /** @test */
    public function it_can_determine_whether_a_group_exists()
    {
        //Cleanup from previous test
        Account::transactional()->deleteGroup('Customers');

        $this->assertFalse(Account::transactional()->groupExists('Customers'));

        $groupId = Account::transactional()->createGroup('Customers')->group->id;

        $this->assertTrue(Account::transactional()->groupExists('Customers'));

        //Cleanup
        Account::transactional()->deleteGroup($groupId);
    }

    /** @test */
    public function it_can_get_all_groups()
    {
        //Arrange
        $customersId = Account::transactional()->createGroup('Customers')->group->id;
        $referralsId = Account::transactional()->createGroup('Referrals')->group->id;

        //Act and Assert
        $this->assertCount(2, Account::transactional()->groups());

        //Cleanup
        Account::transactional()->deleteGroup($customersId);
        Account::transactional()->deleteGroup($referralsId);
    }

    /** @test */
    public function it_can_create_new_contact_group()
    {
        //Arrange
        $this->assertCount(0, Account::transactional()->groups());

        //Act
        $groupId = Account::transactional()->createGroup('Tech')->group->id;

        //Assert
        $this->assertCount(1, Account::transactional()->groups());

        //Cleanup
        Account::transactional()->deleteGroup($groupId);
    }

    /** @test */
    public function it_can_delete_a_group_via_a_provided_group_name()
    {
        //Arrange
        Account::transactional()->createGroup('Promo');
        $this->assertTrue(Account::transactional()->groupExists('Promo'));

        //Act
        Account::transactional()->deleteGroup('Promo');

        //Assert
        $this->assertFalse(Account::transactional()->groupExists('Promo'));
    }

    /** @test */
    public function it_can_get_list_of_all_members_in_the_default_MyContacts_group()
    {
        //Act
        $response = Account::transactional()->members();

        //Assert
        $this->assertCount(2, $response);
    }

    /** @test */
    public function it_can_get_list_of_all_members_for_a_group()
    {
        //Arrange
        $groupId = Account::transactional()->createGroup('Promo')->group->id;
        $this->assertTrue(Account::transactional()->groupExists('Promo'));

        //Act
        $response = Account::transactional()->members($groupId);

        //Assert
        $this->assertCount(0, $response);

        //Cleanup
        Account::transactional()->deleteGroup($groupId);
    }

    /** @test */
    public function it_can_add_numbers_to_a_group()
    {
        //Arrange
        $groupId  = Account::transactional()->createGroup('Promo')->group->id;
        $response = Account::transactional()->members($groupId);
        $this->assertCount(0, $response);
        $numbers = '919033100026,919879612326';

        //Act
        Account::transactional()->addNumbers($numbers, $groupId);

        //Assert
        $response = Account::transactional()->members($groupId);
        $this->assertCount(2, $response);

        //Cleanup
        Account::transactional()->deleteGroup($groupId);
    }

    /** @test */
    public function it_can_add_members_to_a_group()
    {
        //Arrange
        $groupId  = Account::transactional()->createGroup('Promo')->group->id;
        $response = Account::transactional()->members($groupId);
        $this->assertCount(0, $response);
        $members = [
            ['number' => '1234567894', 'first_name' => 'John', 'last_name' => 'Doe'],
            ['number' => '3355667798', 'first_name' => 'Jane', 'last_name' => 'Mclane']
        ];

        //Act
        Account::transactional()->addMembers($members, $groupId);
        $response = Account::transactional()->members($groupId);
        $this->assertCount(2, $response);

        //Cleanup
        Account::transactional()->deleteGroup($groupId);
    }

    /** @test */
    public function it_can_delete_a_member()
    {
        //Arrange
        $groupId = Account::transactional()->createGroup('Promo')->group->id;

        $members = [
            ['number' => '1234567894', 'first_name' => 'John', 'last_name' => 'Doe'],
            ['number' => '3355667798', 'first_name' => 'Jane', 'last_name' => 'Mclane']
        ];
        $r = Account::transactional()->addMembers($members, $groupId);

        $this->assertEquals(2, $r->num_contacts);

        //Act
        $response = Account::transactional()->removeMember('913355667798', $groupId);
//        var_dump($response);

        //Assert
        $this->assertEquals('success', $response->status);
        $this->assertEquals($groupId, $response->group_id);
        $this->assertEquals('913355667798', $response->number);

        //Cleanup
        Account::transactional()->deleteGroup($groupId);
    }

    /** @test */
    public function it_can_get_a_list_of_members_who_have_opted_out()
    {
        //Arrange
        $response = Account::transactional()->optOuts();

        $this->assertEquals('success', $response->status);
    }
}
