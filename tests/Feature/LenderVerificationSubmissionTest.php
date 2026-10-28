<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LenderVerificationSubmissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_lender_can_submit_required_verification_details_and_documents(): void
    {
        Storage::fake('local');
        $lender = User::factory()->create([
            'role' => 'lender',
            'seller_verification_status' => 'Pending',
        ]);

        $this->actingAs($lender)
            ->post(route('seller.verify.submit'), [
                'official_name' => 'Juan Dela Cruz',
                'province' => 'Bulacan',
                'city' => 'Malolos',
                'barangay' => 'Longos',
                'street_address' => '123 Example Street',
                'government_id' => UploadedFile::fake()->create('government-id.pdf', 500, 'application/pdf'),
                'residence_proof' => UploadedFile::fake()->create('proof-of-address.pdf', 500, 'application/pdf'),
                'barangay_certification' => UploadedFile::fake()->create('barangay-certification.pdf', 500, 'application/pdf'),
            ])
            ->assertRedirect(route('verification.message'))
            ->assertSessionHas('status', 'Your verification requirements were submitted successfully.');

        $this->get(route('verification.message'))
            ->assertOk()
            ->assertSee('Your verification requirements were submitted successfully.')
            ->assertSee('Your lender account is awaiting verification');

        $lender->refresh();

        $this->assertSame('Juan Dela Cruz', $lender->verification_official_name);
        $this->assertSame('Bulacan', $lender->verification_province);
        $this->assertSame('Malolos', $lender->verification_city);
        $this->assertSame('Longos', $lender->verification_barangay);
        $this->assertSame('123 Example Street', $lender->verification_street_address);
        $this->assertSame('Pending', $lender->seller_verification_status);
        $this->assertNotNull($lender->seller_verification_submitted_at);
        $this->assertNotNull($lender->government_id_path);
        $this->assertNotNull($lender->residence_proof_path);
        $this->assertNotNull($lender->barangay_certification_path);

        Storage::disk('local')->assertExists($lender->government_id_path);
        Storage::disk('local')->assertExists($lender->residence_proof_path);
        Storage::disk('local')->assertExists($lender->barangay_certification_path);
    }

    public function test_lender_must_submit_required_documents_before_the_application_is_saved(): void
    {
        Storage::fake('local');
        $lender = User::factory()->create([
            'role' => 'lender',
            'seller_verification_status' => 'Pending',
        ]);

        $this->actingAs($lender)
            ->post(route('seller.verify.submit'), [
                'official_name' => 'Juan Dela Cruz',
                'province' => 'Bulacan',
                'city' => 'Malolos',
                'barangay' => 'Longos',
                'street_address' => '123 Example Street',
            ])
            ->assertSessionHasErrors(['government_id', 'residence_proof']);

        $lender->refresh();

        $this->assertNull($lender->seller_verification_submitted_at);
        $this->assertNull($lender->government_id_path);
        $this->assertNull($lender->residence_proof_path);
    }

    public function test_non_lender_cannot_access_or_submit_lender_verification(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)
            ->get(route('seller.verify'))
            ->assertForbidden();

        $this->actingAs($user)
            ->post(route('seller.verify.submit'), [])
            ->assertForbidden();
    }

    public function test_only_an_admin_can_download_submitted_verification_documents(): void
    {
        Storage::fake('local');
        Storage::disk('local')->put('lender-verification/id.pdf', 'private-id-document');
        $lender = User::factory()->create([
            'role' => 'lender',
            'seller_verification_status' => 'Pending',
            'seller_verification_submitted_at' => now(),
            'government_id_path' => 'lender-verification/id.pdf',
        ]);
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($admin)
            ->get(route('admin.stores.documents', [$lender, 'government-id']))
            ->assertOk()
            ->assertHeader('content-disposition', 'attachment; filename=government-id-'.$lender->id.'.pdf');

        $this->actingAs($user)
            ->get(route('admin.stores.documents', [$lender, 'government-id']))
            ->assertForbidden();
    }
}
