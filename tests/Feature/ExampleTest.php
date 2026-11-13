<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * Halaman utama mengarahkan pengguna ke halaman login.
     */
    public function test_the_application_redirects_root_to_login_page(): void
    {
        $response = $this->get('/');

        $response->assertRedirect(route('login'));
    }

    /**
     * Halaman login dapat diakses dengan sukses (HTTP 200).
     */
    public function test_login_page_returns_a_successful_response(): void
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
        $response->assertSee('Klinik Utama Medika Antapani');
    }
}
