<?php
// database/seeders/PujaSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Puja;

class PujaSeeder extends Seeder
{
    public function run()
    {
        $pujas = [
            [
                'name' => 'Ganesh Puja',
                'slug' => 'ganesh-puja',
                'description' => 'Worship of Lord Ganesha, the remover of obstacles and patron of arts and sciences',
                'significance' => 'Lord Ganesha is worshipped before beginning any new venture or important work. He is known as Vighna Harta (remover of obstacles) and brings good fortune, wisdom, and success to devotees.',
                'procedure' => "1. Clean the puja area and place Ganesha idol/photo\n2. Light a diya and incense sticks\n3. Offer fresh flowers, especially hibiscus\n4. Present modaks, ladoos, and fruits\n5. Chant Ganesha mantras\n6. Perform aarti with camphor\n7. Seek blessings for obstacle-free endeavors",
                'auspicious_days' => json_encode([
                    '2024-09-07', // Ganesh Chaturthi
                    '2024-08-22', // Sankashti Chaturthi
                    '2024-09-21', // Sankashti Chaturthi
                ]),
                'required_items' => json_encode([
                    'Ganesha Idol or Photo',
                    'Modaks or Ladoos',
                    'Red Hibiscus Flowers',
                    'Coconut',
                    'Banana',
                    'Incense Sticks',
                    'Camphor',
                    'Red Thread (Kalava)',
                    'Turmeric Powder',
                    'Kumkum',
                ]),
                'is_active' => true,
            ],
            [
                'name' => 'Lakshmi Puja',
                'slug' => 'lakshmi-puja',
                'description' => 'Worship of Goddess Lakshmi for prosperity, wealth, and abundance',
                'significance' => 'Goddess Lakshmi is the deity of wealth, fortune, and prosperity. Worshipping her brings financial stability, business success, and material well-being to the household.',
                'procedure' => "1. Clean the house thoroughly, especially the entrance\n2. Draw rangoli patterns at the entrance\n3. Place Lakshmi idol/photo facing east\n4. Light diyas around the house\n5. Offer lotus flowers, gold coins, and sweets\n6. Chant Lakshmi mantras and stotrams\n7. Keep the house illuminated throughout the night",
                'auspicious_days' => json_encode([
                    '2024-11-01', // Dhanteras
                    '2024-11-04', // Lakshmi Puja (Diwali)
                    '2024-10-04', // Sharad Purnima
                ]),
                'required_items' => json_encode([
                    'Lakshmi Idol or Photo',
                    'Lotus Flowers',
                    'Gold or Silver Coins',
                    'Sweets (Kheer, Halwa)',
                    'Rice',
                    'Betel Leaves',
                    'Diyas/Oil Lamps',
                    'Rangoli Colors',
                    'Red Cloth',
                    'Panchamrit',
                ]),
                'is_active' => true,
            ],
            [
                'name' => 'Saraswati Puja',
                'slug' => 'saraswati-puja',
                'description' => 'Worship of Goddess Saraswati for knowledge, wisdom, and learning',
                'significance' => 'Goddess Saraswati is the deity of knowledge, music, arts, and wisdom. Students and scholars especially worship her for academic success and creative inspiration.',
                'procedure' => "1. Place books and musical instruments near the goddess\n2. Dress the idol in white or yellow clothes\n3. Offer white flowers, especially jasmine\n4. Light a ghee diya and incense\n5. Offer fruits, sweets, and panchamrit\n6. Chant Saraswati Vandana\n7. Seek blessings for knowledge and wisdom",
                'auspicious_days' => json_encode([
                    '2024-02-14', // Vasant Panchami
                    '2024-10-03', // Navratri Saraswati Puja
                ]),
                'required_items' => json_encode([
                    'Saraswati Idol or Photo',
                    'White Jasmine Flowers',
                    'Books and Pen',
                    'White or Yellow Cloth',
                    'Honey',
                    'Curd',
                    'Ghee',
                    'Yellow Rice',
                    'Musical Instruments (if available)',
                    'White Sweets',
                ]),
                'is_active' => true,
            ],
            [
                'name' => 'Shiva Puja',
                'slug' => 'shiva-puja',
                'description' => 'Worship of Lord Shiva for spiritual growth and inner peace',
                'significance' => 'Lord Shiva represents destruction of evil and regeneration. Worshipping Shiva brings peace, spiritual growth, and liberation from worldly attachments.',
                'procedure' => "1. Place Shiva lingam or photo on a clean platform\n2. Pour water/milk over the lingam (Abhishekam)\n3. Offer bilva leaves (essential for Shiva)\n4. Apply tilaka with bhasma (sacred ash)\n5. Light camphor and incense\n6. Chant 'Om Namah Shivaya' mantra\n7. Perform aarti with devotion",
                'auspicious_days' => json_encode([
                    '2024-03-08', // Maha Shivratri
                    '2024-07-22', // Shravan Monday
                    '2024-07-29', // Shravan Monday
                ]),
                'required_items' => json_encode([
                    'Shiva Lingam or Photo',
                    'Bilva Leaves',
                    'Water or Milk for Abhishekam',
                    'Sacred Ash (Bhasma)',
                    'Camphor',
                    'Dhatura Flowers',
                    'Rudraksha Beads',
                    'Honey',
                    'Gangajal (Holy Water)',
                    'White Flowers',
                ]),
                'is_active' => true,
            ],
            [
                'name' => 'Durga Puja',
                'slug' => 'durga-puja',
                'description' => 'Worship of Goddess Durga for protection and strength',
                'significance' => 'Goddess Durga represents divine feminine power and protection. She destroys evil forces and grants courage, strength, and victory over adversities.',
                'procedure' => "1. Install Durga idol with all her weapons\n2. Decorate with red cloth and flowers\n3. Light multiple diyas and incense\n4. Offer red hibiscus flowers\n5. Present bhog (food offerings)\n6. Chant Durga Chalisa or mantras\n7. Perform sindoor khela (vermillion play) on Dashami",
                'auspicious_days' => json_encode([
                    '2024-10-03', // Durga Puja Saptami
                    '2024-10-04', // Durga Ashtami
                    '2024-10-05', // Durga Navami
                    '2024-10-06', // Durga Dashami
                ]),
                'required_items' => json_encode([
                    'Durga Idol or Photo',
                    'Red Hibiscus Flowers',
                    'Red Cloth',
                    'Vermillion (Sindoor)',
                    'Coconut',
                    'Banana',
                    'Sweets and Fruits',
                    'Incense Sticks',
                    'Camphor',
                    'Panchamrit',
                ]),
                'is_active' => true,
            ],
        ];

        foreach ($pujas as $pujaData) {
            Puja::updateOrCreate(
                ['slug' => $pujaData['slug']],
                $pujaData
            );
        }
    }
}
