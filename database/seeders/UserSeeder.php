<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\ClassSection;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // === THE DEFINITIVE FIX: ADD ALL STAFF FROM YOUR CSV ===
        $staff = [
            ['name' => 'KALUSA ALEX ZACHARIA', 'email' => 'kalusa.zacharia@example.com', 'role' => 'admin', 'password' => 'kalusa12345L'],
            ['name' => 'BBALO CHIBULE AGNITA', 'email' => 'bbalo.agnita@example.com', 'role' => 'admin', 'password' => 'bbalo12345L'],
            ['name' => 'CHIBOMBAMILIMO LAMECK', 'email' => 'chibombamilimo.lameck@example.com', 'role' => 'teacher', 'password' => 'chibombamilimo12345L'],
            ['name' => 'CHIKUSI ROYD', 'email' => 'chikusi.royd@example.com', 'role' => 'teacher', 'password' => 'chikusi12345L'],
            ['name' => 'CHILOKOTA BLESS', 'email' => 'chilokota.bless@example.com', 'role' => 'teacher', 'password' => 'chilokota12345L'],
            ['name' => 'HALUMBA MARTIN', 'email' => 'halumba.martin@example.com', 'role' => 'teacher', 'password' => 'halumba12345L'],
            ['name' => 'HAMPEYO CAROLINE', 'email' => 'hampeyo.caroline@example.com', 'role' => 'admin', 'password' => 'hampeyo12345L'],
            ['name' => 'HIMWEETE BEATRICE', 'email' => 'himweete.beatrice@example.com', 'role' => 'teacher', 'password' => 'himweete12345L'],
            ['name' => 'KACHOTA MAX', 'email' => 'kachota.max@example.com', 'role' => 'teacher', 'password' => 'kachota12345L'],
            ['name' => 'KAGO GACHIRI', 'email' => 'kago.gachiri@example.com', 'role' => 'teacher', 'password' => 'kago12345L'],
            ['name' => 'KALUBA GIFT', 'email' => 'kaluba.gift@example.com', 'role' => 'teacher', 'password' => 'kaluba12345L'],
            ['name' => 'KAPEPE CONSTANCE', 'email' => 'kapepe.constance@example.com', 'role' => 'teacher', 'password' => 'kapepe12345L'],
            ['name' => 'LWEENDO MUCHIMBA', 'email' => 'lweendo.muchimba@example.com', 'role' => 'teacher', 'password' => 'lweendo12345L'],
            ['name' => 'MAINZA IVAN MULAMBO', 'email' => 'mainza.mulambo@example.com', 'role' => 'admin', 'password' => 'mainza12345L'],
            ['name' => 'MAKAYI BERNADATE', 'email' => 'makayi.bernadate@example.com', 'role' => 'teacher', 'password' => 'makayi12345L'],
            ['name' => 'MIYOBA TALENT', 'email' => 'miyoba.talent@example.com', 'role' => 'teacher', 'password' => 'miyoba12345L'],
            ['name' => 'MUKOSHA MERCY', 'email' => 'mukosha.mercy@example.com', 'role' => 'teacher', 'password' => 'mukosha12345L'],
            ['name' => 'MUKUMBWALI MILIMO', 'email' => 'mukumbwali.milimo@example.com', 'role' => 'teacher', 'password' => 'mukumbwali12345L'],
            ['name' => 'MULEMBA WAMUNDILA', 'email' => 'mulemba.wamundila@example.com', 'role' => 'teacher', 'password' => 'mulemba12345L'],
            ['name' => 'MULINGA REGISTER', 'email' => 'mulinga.register@example.com', 'role' => 'teacher', 'password' => 'mulinga12345L'],
            ['name' => 'NAKUSHOWA ANGELA', 'email' => 'nakushowa.angela@example.com', 'role' => 'teacher', 'password' => 'nakushowa12345L'],
            ['name' => 'NCHIMUNYA CHILALA', 'email' => 'nchimunya.chilala@example.com', 'role' => 'teacher', 'password' => 'nchimunya12345L'],
            ['name' => 'NJEKWA MISOZI', 'email' => 'njekwa.misozi@example.com', 'role' => 'teacher', 'password' => 'njekwa12345L'],
            ['name' => 'PHIRI MEKIWE', 'email' => 'phiri.mekiwe@example.com', 'role' => 'teacher', 'password' => 'phiri12345L'],
            ['name' => 'SIBWAALU EDITH', 'email' => 'sibwaalu.edith@example.com', 'role' => 'teacher', 'password' => 'sibwaalu12345L'],
            ['name' => 'SIYAUYA KAMWI', 'email' => 'siyauya.kamwi@example.com', 'role' => 'admin', 'password' => 'siyauya12345L'],
            ['name' => 'SYAMWENYA BINGA', 'email' => 'syamwenya.binga@example.com', 'role' => 'teacher', 'password' => 'syamwenya12345L'],
            ['name' => 'SIKANWE TEDDIE', 'email' => 'sikanwe.teddie@example.com', 'role' => 'teacher', 'password' => 'sikanwe12345L'],
            ['name' => 'MAYANGWA MARY', 'email' => 'mayangwa.mary@example.com', 'role' => 'teacher', 'password' => 'mayangwa12345L'],
            ['name' => 'KELLY MICHELO', 'email' => 'kelly.michelo@example.com', 'role' => 'teacher', 'password' => 'kelly12345L'],
            ['name' => 'SIKWA MALAMBO', 'email' => 'sikwa.malambo@example.com', 'role' => 'teacher', 'password' => 'sikwa12345L'],
            ['name' => 'NGOMA SAM', 'email' => 'ngoma.sam@example.com', 'role' => 'teacher', 'password' => 'ngoma12345L'],
            ['name' => 'MAZUBA MWANZA', 'email' => 'mazuba.mwanza@example.com', 'role' => 'teacher', 'password' => 'mazuba12345L'],
            ['name' => 'MAKAMO DORIS', 'email' => 'makamo.doris@example.com', 'role' => 'admin', 'password' => 'makamo12345L'],
            ['name' => 'SAMENDE GODWIN', 'email' => 'samende.godwin@example.com', 'role' => 'teacher', 'password' => 'samende12345L'],
            ['name' => 'THABO CHOCHO', 'email' => 'thabo.chocho@example.com', 'role' => 'teacher', 'password' => 'thabo12345L'],
            ['name' => 'SUSAN MAKALICHI', 'email' => 'susan.makalichi@example.com', 'role' => 'teacher', 'password' => 'susan12345L'],
            ['name' => 'MOONO KELVIN', 'email' => 'moono.kelvin@example.com', 'role' => 'teacher', 'password' => 'moono12345L'],
            ['name' => 'KAMBUNGA SAVIOURS', 'email' => 'kambunga.saviours@example.com', 'role' => 'teacher', 'password' => 'kambunga12345L'],
            ['name' => 'LAMBA LEEVAN', 'email' => 'lamba.leevan@example.com', 'role' => 'teacher', 'password' => 'lamba12345L'],
            ['name' => 'HUMPHREY MUJIMANZOVU', 'email' => 'humphrey.mujimanzovu@example.com', 'role' => 'teacher', 'password' => 'humphrey12345L'],
            ['name' => 'KANYUNGE CLAYDON MUMBA', 'email' => 'kanyunge.mumba@example.com', 'role' => 'admin', 'password' => 'kanyunge12345L'],
            ['name' => 'BANDA MORGAN', 'email' => 'banda.morgan@example.com', 'role' => 'teacher', 'password' => 'banda12345L'],
            ['name' => 'MWANGALA EDWARD', 'email' => 'mwangala.edward@example.com', 'role' => 'admin', 'password' => 'mwangala12345L'],
            ['name' => 'MWIINDWE NAMUCHEME', 'email' => 'mwiindwe.namucheme@example.com', 'role' => 'teacher', 'password' => 'mwiindwe12345L'],
            ['name' => 'NAWA LIAYO', 'email' => 'nawa.liayo@example.com', 'role' => 'teacher', 'password' => 'nawa12345L'],
            ['name' => 'TEMBO STELLA', 'email' => 'tembo.stella@example.com', 'role' => 'teacher', 'password' => 'tembo12345L'],
            ['name' => 'KABBUDULA MELODY', 'email' => 'kabbudula.melody@example.com', 'role' => 'teacher', 'password' => 'kabbudula12345L'],
            ['name' => 'LUBINDA MANYANDO', 'email' => 'lubinda.manyando@example.com', 'role' => 'admin', 'password' => 'lubinda12345L'],
            ['name' => 'BRIAN SINGOMA', 'email' => 'brian.singoma@example.com', 'role' => 'teacher', 'password' => 'brian12345L'],
            ['name' => 'SYAMALYATA MELODY', 'email' => 'syamalyata.melody@example.com', 'role' => 'admin', 'password' => 'syamalyata12345L'],
            ['name' => 'KASWEKA NJAMBA', 'email' => 'kasweka.njamba@example.com', 'role' => 'teacher', 'password' => 'kasweka12345L'],
            ['name' => 'SOKO DIANA', 'email' => 'soko.diana@example.com', 'role' => 'teacher', 'password' => 'soko12345L'],
            ['name' => 'HIMWIITA WINTER', 'email' => 'himwiita.winter@example.com', 'role' => 'teacher', 'password' => 'himwiita12345L'],
            ['name' => 'DOREEN MUKUBESA', 'email' => 'doreen.mukubesa@example.com', 'role' => 'teacher', 'password' => 'doreen12345L'],
            ['name' => 'ANNIE MUYUNDA', 'email' => 'annie.muyunda@example.com', 'role' => 'teacher', 'password' => 'annie12345L'],
            ['name' => 'EDNA CHILESHE', 'email' => 'edna.chileshe@example.com', 'role' => 'teacher', 'password' => 'edna12345L'],
            ['name' => 'MULENGA CASTRIDAH', 'email' => 'mulenga.castridah@example.com', 'role' => 'teacher', 'password' => 'mulenga12345L'],
            ['name' => 'MUCHIMBA BRENDA', 'email' => 'muchimba.brenda@example.com', 'role' => 'teacher', 'password' => 'muchimba12345L'],
            ['name' => 'MWEEMBA MIRRIAM', 'email' => 'mweemba.mirriam@example.com', 'role' => 'teacher', 'password' => 'mweemba12345L'],
            ['name' => 'DINDI JAMES CHILUNDU', 'email' => 'dindi.chilundu@example.com', 'role' => 'teacher', 'password' => 'dindi12345L'],
            ['name' => 'MOONO CHILALA', 'email' => 'moono.chilala@example.com', 'role' => 'teacher', 'password' => 'moono12345L'],
            ['name' => 'SAKAPANGA SANDRA', 'email' => 'sakapanga.sandra@example.com', 'role' => 'teacher', 'password' => 'sakapanga12345L'],
            ['name' => 'ZULU MASAUSO', 'email' => 'zulu.masauso@example.com', 'role' => 'teacher', 'password' => 'zulu12345L'],
            ['name' => 'KANGAI BOYD', 'email' => 'kangai.boyd@example.com', 'role' => 'teacher', 'password' => 'kangai12345L'],
            ['name' => 'SIBANYAMA MIYANDA VENIA', 'email' => 'sibanyama.venia@example.com', 'role' => 'teacher', 'password' => 'sibanyama12345L'],
            ['name' => 'SINAMWENDA MICHEAL', 'email' => 'sinamwenda.micheal@example.com', 'role' => 'teacher', 'password' => 'sinamwenda12345L'],
        ];

        // Create the main admin user
        User::updateOrCreate(
            ['email' => 'admin@app.com'],
            ['name' => 'Admin', 'role' => 'admin', 'password' => Hash::make('password')]
        );

        foreach ($staff as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'role' => $user['role'],
                    'password' => Hash::make($user['password'])
                ]
            );
        }

        // --- Create Test Students and Enroll Them ---
        $class10A = ClassSection::where('name', '10A')->first();
        if ($class10A) {
            $student1 = User::updateOrCreate(
                ['email' => 'john.doe@example.com'],
                ['name' => 'John Doe', 'role' => 'student', 'password' => Hash::make('password')]
            );
            $student1->enrollments()->updateOrCreate(
                ['class_section_id' => $class10A->id, 'user_id' => $student1->id]
            );
        }

        $class11B = ClassSection::where('name', '11B')->first();
        if ($class11B) {
            $student2 = User::updateOrCreate(
                ['email' => 'jane.smith@example.com'],
                ['name' => 'Jane Smith', 'role' => 'student', 'password' => Hash::make('password')]
            );
            $student2->enrollments()->updateOrCreate(
                ['class_section_id' => $class11B->id, 'user_id' => $student2->id]
            );
        }
    }
}