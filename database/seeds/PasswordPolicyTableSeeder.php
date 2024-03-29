<?php

use Illuminate\Database\Seeder;
use App\Radan\Policy\Password\Models\PasswordPolicy;

class PasswordPolicyTableSeeder extends Seeder 
{

	/**
     * Run the database seeds.
     *
     * @return void
     */
	public function run()
	{
		//DB::table('profiles')->delete();
		//
		PasswordPolicy::create(array(
			'name' => 'default',
			'description' => 'پیش فرض',
			'min_length' => 6,
			'max_length' => 191,
			'digits' => 0,
		));	
	}
}