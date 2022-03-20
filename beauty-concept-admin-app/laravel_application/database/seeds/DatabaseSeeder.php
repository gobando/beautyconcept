<?php
/*
 * File name: DatabaseSeeder.php
 * Last modified: 2022.02.18 at 15:24:51
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $this->call(UsersTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(PermissionsTableSeeder::class);
        $this->call(ModelHasPermissionsTableSeeder::class);
        $this->call(ModelHasRolesTableSeeder::class);
        $this->call(RoleHasPermissionsTableSeeder::class);

        $this->call(CustomFieldsTableSeeder::class);
        $this->call(CustomFieldValuesTableSeeder::class);
        $this->call(AppSettingsTableSeeder::class);
        $this->call(SalonLevelsTableSeeder::class);
        $this->call(CategoriesTableSeeder::class);
        $this->call(FaqCategoriesTableSeeder::class);
        $this->call(BookingStatusesTableSeeder::class);
        $this->call(CurrenciesTableSeeder::class);
        $this->call(OptionGroupsTableSeeder::class);
        $this->call(AddressesTableSeeder::class);

        $this->call(SalonsTableSeeder::class);
        $this->call(EServicesTableSeeder::class);
        $this->call(GalleriesTableSeeder::class);
        $this->call(OptionsTableSeeder::class);
        $this->call(NotificationsTableSeeder::class);
        $this->call(FaqsTableSeeder::class);
        $this->call(AwardsTableSeeder::class);
        $this->call(AvailabilityHoursTableSeeder::class);
        $this->call(ExperiencesTableSeeder::class);

        $this->call(MediaTableSeeder::class);
        $this->call(EServiceCategoriesTableSeeder::class);
        $this->call(SlidesTableSeeder::class);
        $this->call(CustomPagesTableSeeder::class);
        $this->call(WalletsTableSeeder::class);
        $this->call(WalletTransactionsTableSeeder::class);
        $this->call(PaymentMethodsTableSeeder::class);
        $this->call(PaymentStatusesTableSeeder::class);
        $this->call(TaxesTableSeeder::class);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
