<?php

namespace Database\Seeders;

use App\Models\GoodsCategory;
use App\Models\GoodsType;
use Illuminate\Database\Seeder;

class GoodsTypeSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'A' => [
                'name' => 'Elektronik',
                'items' => [
                    'CPU',
                    'MOTHERBOARD',
                    'HDD',
                    'KABEL DATA',
                    'RAM',
                    'VGA',
                    'SOUNDCARD',
                    'ETHERNET CARD',
                    'CD ROOM',
                    'MONITOR',
                    'KEYBOARD',
                    'MOUSE',
                    'KABEL POWER',
                    'CASING',
                    'POWER SUPPLY',
                    'HEADSET',
                    'PROYEKTOR',
                    'SWITCH',
                    'STOP KONTAK',
                    'PRINTER',
                    'KABEL UTP',
                    'WALL SCREEN',
                    'SPEAKER',
                    'PC / KOMPUTER',
                    'LAPTOP',
                    'ROUTER',
                    'SWITCH',
                    'PEN TABLET',
                    'Acces Poin',
                    'UPS',
                    'KONVERTER HDMI TO VGA',
                    'Lite Beam',
                    'WALL SCREEN stand',
                    'MIKROTIK',
                    'COULD SWITCH',
                    'MIC',
                ],
            ],
            'B' => [
                'name' => 'Mebel & Peralatan',
                'items' => [
                    'MEJA KOMPUTER',
                    'MEJA KOMPUTER',
                    'MEJA KAYU',
                    'MEJA GURU',
                    'KURSI untuk guru',
                    'KURSI KAYU',
                    'KURSI untuk siswa',
                    'ALMARI ALUMUNIUM',
                    'ALMARI KAYU',
                    'KARPET',
                    'BOX PLASTIK',
                    'JAM DINDING',
                    'PAPAN TULIS',
                    'TERMOMETER',
                    'PENGHANCUR KERTAS',
                    'AC',
                    'AMPLI',
                    'BOR',
                    'KUAS CAT',
                    'DRILL SET',
                    'Obeng +',
                    'Obeng -',
                    'Obeng + Panjang',
                    'Tang Cucut',
                    'Tang Potong',
                    'Tang Biasa',
                    'Obeng Set',
                    'Digital Multimeter',
                    'Tespen',
                    'Kunci Pas',
                    'Clamp Meter',
                    'Palu Besar',
                    'Obeng Elektrik',
                    'Vacum Cleaner',
                    'kursi busa',
                    'rak pot besi',
                ],
            ],
            'C' => [
                'name' => 'Alat Kebersihan',
                'items' => [
                    'SAPU LIDI',
                    'SAPU LANTAI',
                    'KEMOCENG',
                    'CANEBO',
                    'PEL BIASA',
                    'ENGKRAK',
                    'SAPU SAWANG',
                    'PEL BOLDE',
                    'TONG BESAR',
                    'SIKAT KAWAT',
                    'SCRUB PEL',
                    'SIKAT CUCI',
                    'EMBER SAMPAH',
                    'SIKAT BAK',
                    'GERGAJI',
                    'PALU',
                    'BOTOL SPRAY KACA',
                    'SABIT',
                    'GOLOK',
                    'WUNGKAL',
                    'KIKIR',
                    'Nampan Besi',
                    'Tongkat',
                    'Tangga',
                ],
            ],
            'D' => [
                'name' => 'Alat Jahit & Produksi',
                'items' => [
                    'MESIN JAHIT BASIC',
                    'MESIN JAHIT OBRAS',
                    'MESIN JAHIT KAOS',
                    'MESIN JAHIT BORDIR',
                    'MEJA POTONG BESAR',
                    'MEJA POTONG LIPAT',
                    'MEJA SETRIKA',
                    'MESIN PRES',
                    'SETRIKA BASIC',
                    'SETRIKA PRODUKSI',
                    'SETRIKA UAP',
                    'MANEQUEEN',
                    'MESIN FORTABLE',
                    'ALMARI',
                    'MESIN (INDUSTRI) WOLSOM',
                    'MESIN (INDUSTRI) RANTAI',
                    'MESIN (INDUSTRI) KAOS',
                    'GUNTING POTONG LISTRIK',
                    'MESIN LUBANG KANCING',
                    'Mesin overdeck',
                ],
            ],
        ];

        foreach ($data as $catCode => $catData) {
            $category = GoodsCategory::updateOrCreate(
                ['code' => $catCode],
                ['name' => $catData['name']]
            );

            foreach ($catData['items'] as $index => $itemName) {
                $no = $index + 1;

                GoodsType::updateOrCreate(
                    ['code' => $catCode . $no],
                    [
                        'goods_category_id' => $category->id,
                        'name'              => $itemName,
                    ]
                );
            }
        }
    }
}
