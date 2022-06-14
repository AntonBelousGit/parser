<?php

namespace Database\Seeders;

use App\Models\Size;
use App\Models\Topping;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ToppingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Topping::create([
            'id' => '0d0b0510-9520-44a8-9947-2efd2c0f0504',
            'name' => 'Моцарела'
        ]);
        Topping::create([
            'id' => '0e893053-b527-45ad-8d04-3b4ab78756f2',
            'name' => 'Оливки'
        ]);
        Topping::create([
            'id' => '1d97d36e-6e8e-42e3-90ad-f2d7ed266411',
            'name' => 'Соус барбекю'
        ]);
        Topping::create([
            'id' => '271e12ee-3c51-4fcf-946e-f7069001c5eb',
            'name' => 'Бекон'
        ]);
        Topping::create([
            'id' => '311f1e0e-6ecc-4d36-8340-87bcdcb4c4e1',
            'name' => 'Пепероні'
        ]);
        Topping::create([
            'id' => '32a78f01-cccf-47a6-9dbe-e0de4645139d',
            'name' => 'Халапеньо'
        ]);
        Topping::create([
            'id' => '3df78562-ee93-497f-99c6-82e210e908dd',
            'name' => 'Шпинат'
        ]);
        Topping::create([
            'id' => '41738309-072a-4d47-8012-265df15176ce',
            'name' => 'Помідори чері'
        ]);
        Topping::create([
            'id' => '4d511adf-20e1-4602-ae8a-dbacbbd1cc2c',
            'name' => 'Гірчиця'
        ]);
        Topping::create([
            'id' => '5020e6aa-0b73-48bc-b376-7ac892761f2c',
            'name' => 'Помідори'
        ]);
        Topping::create([
            'id' => '50c6005c-fecd-4165-b816-68631ead6d5b',
            'name' => 'Огірки мариновані'
        ]);
        Topping::create([
            'id' => '57b9883e-7652-4590-9316-f45f2da2cad4',
            'name' => "Cоус Domino's"
        ]);
        Topping::create([
            'id' => '70ad199a-057e-4212-8999-05bf9ef76627',
            'name' => "Кукурудза"
        ]);
        Topping::create([
            'id' => '70c884bf-d995-4d61-a9a8-12621fffaeac',
            'name' => "Ковбаски баварські"
        ]);
        Topping::create([
            'id' => '721c2670-556e-4673-8bc1-5be0309d6397',
            'name' => "Сосиски білі"
        ]);
        Topping::create([
            'id' => '74201352-b23c-4ef4-8147-14e4c5173b3d',
            'name' => "Фета"
        ]);
        Topping::create([
            'id' => '81169549-df43-4518-8e52-1f0fea1ebb73',
            'name' => "Бергадер Блю"
        ]);
        Topping::create([
            'id' => '841ed46e-fae5-4df5-aabc-6a98155afe2f',
            'name' => "Соус Альфредо"
        ]);
        Topping::create([
            'id' => '8a54aaf1-1d5d-475f-ac8b-868e45f7df5c',
            'name' => "Соус Часниковий"
        ]);
        Topping::create([
            'id' => '95eb76e3-4678-4ca1-94c7-aed89bb72579',
            'name' => "Цибуля"
        ]);
        Topping::create([
            'id' => 'a99d826a-4b2b-4049-b755-133504162279',
            'name' => "Фрикадельки"
        ]);
        Topping::create([
            'id' => 'aea741b2-1847-4519-9f26-d598b69f1bb9',
            'name' => "Гриби"
        ]);
        Topping::create([
            'id' => 'bfe3e9cd-f25e-4a89-83db-2a2ea0498e6f',
            'name' => "Пармезан"
        ]);
        Topping::create([
            'id' => 'c435dd6d-5aab-407b-9c1c-6ca1f09347b0',
            'name' => "Тунець"
        ]);
        Topping::create([
            'id' => 'c66ded9f-5cc2-4df6-9aec-73e3adb8c1f8',
            'name' => "Курка"
        ]);
        Topping::create([
            'id' => 'df7e581c-a1aa-4497-a660-699f27043e26',
            'name' => "Ананас"
        ]);
        Topping::create([
            'id' => 'e162b0e4-868d-4aac-aa35-6950f52a8841',
            'name' => "Чеддер"
        ]);
        Topping::create([
            'id' => 'fa4b63ee-d0d0-4a6f-b2ff-4f39dbeb0342',
            'name' => "Шинка"
        ]);
        Topping::create([
            'id' => 'fce178eb-6e6c-44b7-9c00-cdbe6275bdb6',
            'name' => "Болгарський перець"
        ]);
    }
}
