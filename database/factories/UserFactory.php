<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $firstname = fake()->firstName();
        $lastname = fake()->lastName();

        return [
            'username' => fake()->unique()->userName(),
            'oidc_sub' => fake()->unique()->uuid(),
            'name' => $firstname.' '.$lastname,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => fake()->unique()->safeEmail(),
            'avatar' => null,
            'groups' => json_encode([]),
            'oidc_token' => Str::random(40),
            'oidc_refresh_token' => Str::random(40),
            'id_token' => Str::random(40),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the user belongs to the given OIDC groups.
     *
     * @param  list<string>  $groups
     */
    public function groups(array $groups): static
    {
        return $this->state(fn (array $attributes): array => [
            'groups' => json_encode($groups),
        ]);
    }

    /**
     * Indicate that the user belongs to the admin group.
     */
    public function admin(): static
    {
        return $this->groups([config('app.group_admin')]);
    }
}
