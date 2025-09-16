<?php

namespace Database\Factories;

use App\Enums\ChannelEnum;
use App\Enums\StatusEnum;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'channel' => ChannelEnum::TGLM,
            'category_slug' => Category::all()->random()->slug,
            'subject' => fake()->sentence(),
            'status' => StatusEnum::cases()[array_rand(StatusEnum::cases())],
        ];
    }
}
