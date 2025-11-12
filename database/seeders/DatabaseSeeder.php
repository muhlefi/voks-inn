<?php

namespace Database\Seeders;

use App\Models\HousekeepingCheck;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $superAdmin = User::updateOrCreate(
            ['username' => 'superadmin'],
            [
                'name' => 'Super Admin Voks Inn',
                'username' => 'superadmin',
                'email' => 'superadmin@voks-inn.test',
                'role' => 'superadmin',
                'password' => Hash::make('password123'),
            ]
        );

        $admin = User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Admin Kasir',
                'username' => 'admin',
                'email' => 'admin@voks-inn.test',
                'role' => 'admin',
                'password' => Hash::make('password123'),
            ]
        );

        $housekeeper = User::updateOrCreate(
            ['username' => 'housekeeper'],
            [
                'name' => 'Housekeeper Utama',
                'username' => 'housekeeper',
                'email' => 'housekeeper@voks-inn.test',
                'role' => 'housekeeper',
                'password' => Hash::make('password123'),
            ]
        );

        $roomTypes = collect([
            [
                'nama_tipe' => 'Standard',
                'fasilitas' => 'Kamar single, AC, Wi-Fi, TV LCD, Sarapan',
            ],
            [
                'nama_tipe' => 'Deluxe',
                'fasilitas' => 'Kamar queen, AC, Wi-Fi, Smart TV, Mini bar, Sarapan',
            ],
            [
                'nama_tipe' => 'Suite',
                'fasilitas' => 'Kamar king, Ruang tamu, AC, Wi-Fi, Smart TV, Bathtub, Sarapan & Dinner',
            ],
        ])->map(fn ($data) => RoomType::updateOrCreate(
            ['nama_tipe' => $data['nama_tipe']],
            $data
        ));

        $roomTypeMap = $roomTypes->keyBy('nama_tipe');

        $roomsData = [
            [
                'kode_kamar' => 'ST-101',
                'nama_kamar' => 'Standard 101',
                'room_type_id' => $roomTypeMap['Standard']->id,
                'harga_per_malam' => 350_000,
                'status' => 'kosong',
            ],
            [
                'kode_kamar' => 'DL-201',
                'nama_kamar' => 'Deluxe 201',
                'room_type_id' => $roomTypeMap['Deluxe']->id,
                'harga_per_malam' => 550_000,
                'status' => 'terisi',
            ],
            [
                'kode_kamar' => 'ST-102',
                'nama_kamar' => 'Standard 102',
                'room_type_id' => $roomTypeMap['Standard']->id,
                'harga_per_malam' => 350_000,
                'status' => 'kosong',
            ],
            [
                'kode_kamar' => 'SU-301',
                'nama_kamar' => 'Suite 301',
                'room_type_id' => $roomTypeMap['Suite']->id,
                'harga_per_malam' => 850_000,
                'status' => 'maintenance',
            ],
        ];

        collect($roomsData)->each(fn ($data) => Room::updateOrCreate(
            ['kode_kamar' => $data['kode_kamar']],
            $data
        ));

        $deluxeRoom = Room::firstWhere('kode_kamar', 'DL-201');
        $standardRoom = Room::firstWhere('kode_kamar', 'ST-102');

        if ($deluxeRoom) {
            $reservationActive = Reservation::updateOrCreate(
                [
                    'room_id' => $deluxeRoom->id,
                    'status' => 'menginap',
                ],
                [
                    'user_id' => $admin->id,
                    'nama_tamu' => 'Budi Santoso',
                    'no_identitas' => 'ID1234567890',
                    'check_in' => Carbon::now()->subDay(),
                    'check_out' => Carbon::now()->addDays(2),
                    'jumlah_tamu' => 2,
                    'total_harga' => 0,
                    'denda' => 0,
                    'status' => 'menginap',
                ]
            );

            if ($reservationActive) {
                $reservationActive->total_harga = $reservationActive->subtotal;
                $reservationActive->save();

                $deluxeRoom->update(['status' => 'terisi']);
            }
        }

        if ($standardRoom) {
            $reservationPending = Reservation::updateOrCreate(
                [
                    'room_id' => $standardRoom->id,
                    'status' => 'menunggu_pengecekan',
                ],
                [
                    'user_id' => $admin->id,
                    'nama_tamu' => 'Siti Aminah',
                    'no_identitas' => 'ID0987654321',
                    'check_in' => Carbon::now()->subDays(3),
                    'check_out' => Carbon::now()->subDay(),
                    'jumlah_tamu' => 1,
                    'total_harga' => $standardRoom->harga_per_malam * 2,
                    'denda' => 50_000,
                    'status' => 'menunggu_pengecekan',
                ]
            );

            if ($reservationPending) {
                HousekeepingCheck::updateOrCreate(
                    ['reservation_id' => $reservationPending->id],
                    [
                        'reservation_id' => $reservationPending->id,
                        'housekeeper_id' => $housekeeper->id,
                        'status' => 'butuh_perbaikan',
                        'catatan' => 'Ditemukan handuk kotor, perlu dibersihkan.',
                    ]
                );

                $standardRoom->update(['status' => 'maintenance']);
            }
        }

        if (isset($reservationPending)) {
            Transaction::updateOrCreate(
                ['reservation_id' => $reservationPending->id, 'tipe' => 'pemasukan'],
                [
                    'reservation_id' => $reservationPending->id,
                    'tipe' => 'pemasukan',
                    'nominal' => $reservationPending->grandTotal,
                    'keterangan' => 'Pembayaran Siti Aminah',
                    'tanggal' => $reservationPending->check_out,
                ]
            );
        }

        Transaction::create([
            'reservation_id' => null,
            'tipe' => 'pengeluaran',
            'nominal' => 150_000,
            'keterangan' => 'Pembelian perlengkapan kebersihan',
            'tanggal' => now()->subDays(2),
        ]);
    }
}
