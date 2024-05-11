<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        // a square around Brazil
        // -59 -> 29
        // -26 -> 5
        $lat = $this->faker->randomFloat(6, -59, -29);
        $lon = $this->faker->randomFloat(6, -26, 5);

        return [
            'uid' => Str::uuid()->toString(),
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'cpf' => $this->faker->unique()->numerify('###########'),
            'phoneNumber' => $this->faker->unique()->phoneNumber(),
            'image' => null,
            'lat' => $lat,
            'lon' => $lon,
            'bairro' => null,
            'cep' => null,
            'localidade' => null,
            'uf' => null,
            'logradouro' => null,
            'complemento' => null,
            'numero' => null,
            'ibge' => null,
            'gia' => null,
            'ddd' => null,
            'siafi' => null,
            'familySize' => null,
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
