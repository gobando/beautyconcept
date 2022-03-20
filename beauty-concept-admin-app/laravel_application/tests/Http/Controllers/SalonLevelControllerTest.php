<?php
/*
 * File name: SalonLevelControllerTest.php
 * Last modified: 2022.02.02 at 21:21:31
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

namespace Tests\Http\Controllers;

use App\Models\SalonLevel;
use Tests\Helpers\TestHelper;
use Tests\TestCase;

class SalonLevelControllerTest extends TestCase
{

    /**
     * @return void
     */
    public function testIndex()
    {
        $user = TestHelper::getAdmin();
        $response = $this->actingAs($user)
            ->get(route('salonTypes.index'));
        $response->assertStatus(200);
        $response->assertSeeTextInOrder([__('lang.salon_level_desc'), __('lang.salon_level_table'), __('lang.salon_level_create')]);
    }

    /**
     * @return void
     */
    public function testCreate()
    {
        $user = TestHelper::getAdmin();
        $response = $this->actingAs($user)
            ->get(route('salonTypes.create'));
        $response->assertStatus(200);
        $response->assertSeeTextInOrder([__('lang.salon_level_desc'), __('lang.salon_level_name'), __('lang.salon_level_commission')]);
    }

    /**
     * @return void
     */
    public function testEdit()
    {
        $user = TestHelper::getAdmin();
        $salonTypeId = SalonLevel::all()->random()->id;
        $response = $this->actingAs($user)
            ->get(route('salonTypes.edit', $salonTypeId));
        $response->assertStatus(200);
        $response->assertSeeTextInOrder([__('lang.salon_level_desc'), __('lang.salon_level_name'), __('lang.salon_level_commission')]);
    }

    /**
     * @return void
     */
    public function testStore()
    {
        $user = TestHelper::getAdmin();
        $salonType = factory(SalonLevel::class)->make();
        $count = SalonLevel::count();

        $response = $this->actingAs($user)
            ->post(route('salonTypes.store'), $salonType->toArray());
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseCount(SalonLevel::getModel()->table, $count + 1);
        $this->assertDatabaseHas(SalonLevel::getModel()->table, [
            'name' => $salonType->name,
            'commission' => $salonType->commission,
            'disabled' => $salonType->disabled
        ]);
        $response->assertSessionHas('flash_notification.0.level', 'success');
        $response->assertSessionHas('flash_notification.0.message', __('lang.saved_successfully', ['operator' => __('lang.salon_level')]));
    }

    /**
     * Test Update SalonLevel
     * @return void
     */
    public function testUpdate()
    {
        $user = TestHelper::getAdmin();
        $salonType = factory(SalonLevel::class)->make();
        $salonTypeId = SalonLevel::all()->random()->id;


        $response = $this->actingAs($user)
            ->put(route('salonTypes.update', $salonTypeId), $salonType->toArray());
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas(SalonLevel::getModel()->table, [
            'name' => $salonType->name,
            'commission' => $salonType->commission,
            'disabled' => $salonType->disabled
        ]);
        $response->assertSessionHas('flash_notification.0.level', 'success');
        $response->assertSessionHas('flash_notification.0.message', __('lang.updated_successfully', ['operator' => __('lang.salon_level')]));
    }

    /**
     * @return void
     */
    public function testDestroy()
    {
        $user = TestHelper::getAdmin();
        $salonTypeId = SalonLevel::all()->random()->id;
        $response = $this->actingAs($user)
            ->delete(route('salonTypes.destroy', $salonTypeId));
        $response->assertRedirect(route('salonTypes.index'));
        $this->assertDatabaseMissing(SalonLevel::getModel()->table, [
            'id' => $salonTypeId,
        ]);
        $response->assertSessionHas('flash_notification.0.level', 'success');
        $response->assertSessionHas('flash_notification.0.message', __('lang.deleted_successfully', ['operator' => __('lang.salon_level')]));
    }

    /**
     * @return void
     */
    public function testDestroyElementNotExist()
    {
        $user = TestHelper::getAdmin();
        $salonTypeId = 50000; // not exist id
        $response = $this->actingAs($user)
            ->delete(route('salonTypes.destroy', $salonTypeId));
        $response->assertRedirect(route('salonTypes.index'));
        $response->assertSessionHas('flash_notification.0.level', 'danger');
        $response->assertSessionHas('flash_notification.0.message', 'E Provider Type not found');
    }

    /**
     * @return void
     */
    public function testRequiredFieldsWhenStore()
    {
        $user = TestHelper::getAdmin();
        $salonType = factory(SalonLevel::class)->make();

        $salonType['name'] = null;
        $salonType['commission'] = null;

        $response = $this->actingAs($user)
            ->post(route('salonTypes.store'), $salonType->toArray());
        $response->assertSessionHasErrors("name", __('validation.required', ['attribute' => 'name']));
        $response->assertSessionHasErrors("commission", __('validation.numeric', ['attribute' => 'commission']));
    }

    /**
     * @return void
     */
    public function testRequiredFieldsWhenUpdate()
    {
        $user = TestHelper::getAdmin();
        $salonType = factory(SalonLevel::class)->make();
        $salonTypeId = SalonLevel::all()->random()->id;

        $salonType['name'] = null;
        $salonType['commission'] = null;


        $response = $this->actingAs($user)
            ->put(route('salonTypes.update', $salonTypeId), $salonType->toArray());
        $response->assertSessionHasErrors("name", __('validation.required', ['attribute' => 'name']));
        $response->assertSessionHasErrors("commission", __('validation.numeric', ['attribute' => 'commission']));
    }

    /**
     * @return void
     */
    public function testMaxCharactersFields()
    {
        $user = TestHelper::getAdmin();
        $salonType = factory(SalonLevel::class)->states(['name_more_127_char', 'commission_more_100'])->make();
        $response = $this->actingAs($user)
            ->post(route('salonTypes.store'), $salonType->toArray());
        $response->assertSessionHasErrors("name", __('validation.max.string', ['attribute' => 'name', 'max' => '127']));
        $response->assertSessionHasErrors("commission", __('validation.max.numeric', ['attribute' => 'commission']));
    }

    /**
     * @return void
     */
    public function testMinCommissionField()
    {
        $user = TestHelper::getAdmin();
        $salonType = factory(SalonLevel::class)->states(['commission_less_0'])->make();
        $response = $this->actingAs($user)
            ->post(route('salonTypes.store'), $salonType->toArray());
        $response->assertSessionHasErrors("commission", __('validation.min.numeric', ['attribute' => 'commission']));
    }

}
