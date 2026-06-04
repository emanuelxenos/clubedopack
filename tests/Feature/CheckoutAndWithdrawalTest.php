<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Pack;
use App\Models\Purchase;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CheckoutAndWithdrawalTest extends TestCase
{
    use RefreshDatabase;

    protected User $customer;
    protected User $creator;
    protected User $admin;
    protected Category $category;
    protected Pack $pack;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Create Category
        $this->category = Category::create([
            'name' => 'Ensaio Sensual',
            'slug' => 'ensaio-sensual',
            'icon' => '🔥',
            'sort_order' => 1
        ]);

        // 2. Create Users
        $this->customer = User::create([
            'name' => 'João Cliente',
            'username' => 'joaocliente',
            'email' => 'cliente@demo.com',
            'password' => Hash::make('demo123'),
            'role' => 'customer',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $this->creator = User::create([
            'name' => 'Isabella Santos',
            'username' => 'isabellasantos',
            'email' => 'isabella@demo.com',
            'password' => Hash::make('demo123'),
            'role' => 'creator',
            'bio' => 'Criadora de conteúdo.',
            'subscription_price' => 29.90,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $this->admin = User::create([
            'name' => 'Administrador',
            'username' => 'admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // 3. Create Pack
        $this->pack = Pack::create([
            'user_id' => $this->creator->id,
            'category_id' => $this->category->id,
            'title' => 'Ensaio Verão 2024',
            'slug' => 'ensaio-verao-2024-abc',
            'description' => 'Ensaio de verão.',
            'price' => 50.00,
            'cover_image_path' => 'packs/covers/sensual_beach.png',
            'is_active' => true,
        ]);
    }

    public function test_customer_can_purchase_pack_via_pix_and_simulate_webhook(): void
    {
        $this->actingAs($this->customer);

        // Call purchase endpoint
        $response = $this->post(route('pack.purchase', $this->pack->slug), [
            'payment_method' => 'pix',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('show_pix_modal', true);
        $response->assertSessionHas('pix_copy_paste');
        $response->assertSessionHas('pix_qr_base64');
        $response->assertSessionHas('purchase_id');

        $purchaseId = session('purchase_id');
        $purchase = Purchase::find($purchaseId);

        $this->assertNotNull($purchase);
        $this->assertEquals('pending', $purchase->status);
        $this->assertEquals('pix', $purchase->payment_method);

        // Check purchase status endpoint returns pending
        $statusResponse = $this->get(route('purchase.status', $purchase->id));
        $statusResponse->assertStatus(200);
        $statusResponse->assertJson(['status' => 'pending']);

        // Simulate Asaas Webhook call
        $webhookData = [
            'event' => 'PAYMENT_RECEIVED',
            'payment' => [
                'externalReference' => 'purchase_' . $purchase->id,
                'id' => 'mock_payment_12345',
                'billingType' => 'PIX'
            ]
        ];

        $webhookResponse = $this->post('/webhooks/asaas', $webhookData);
        $webhookResponse->assertStatus(200);
        $webhookResponse->assertJson(['status' => 'success']);

        // Assert purchase is confirmed
        $purchase->refresh();
        $this->assertEquals('confirmed', $purchase->status);

        // Check status endpoint returns confirmed
        $statusResponse2 = $this->get(route('purchase.status', $purchase->id));
        $statusResponse2->assertStatus(200);
        $statusResponse2->assertJson(['status' => 'confirmed']);

        // Check Transaction was created
        $transaction = Transaction::where('transactionable_type', Purchase::class)
            ->where('transactionable_id', $purchase->id)
            ->first();

        $this->assertNotNull($transaction);
        $this->assertEquals(50.00, $transaction->amount);
        $this->assertEquals(7.50, $transaction->platform_fee); // 15%
        $this->assertEquals(42.50, $transaction->creator_amount); // 85%

        // Creator balance should be updated immediately because it is PIX
        $this->creator->refresh();
        $this->assertEquals(42.50, $this->creator->balance_available);
        $this->assertEquals(0.00, $this->creator->balance_pending);
    }

    public function test_customer_can_purchase_pack_via_credit_card_and_release_pending_balance(): void
    {
        $this->actingAs($this->customer);

        // Purchase with Credit Card (MockGateway immediately confirms credit card)
        $response = $this->post(route('pack.purchase', $this->pack->slug), [
            'payment_method' => 'credit_card',
            'card_number' => '1234 5678 1234 5678',
            'card_name' => 'João Cliente',
            'card_expiry' => '12/28',
            'card_cvv' => '123',
            'holder_cpf' => '123.456.789-00',
            'holder_phone' => '(11) 99999-9999',
            'holder_zip' => '01234-567',
            'holder_address_num' => '100',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success');

        $purchase = Purchase::where('user_id', $this->customer->id)
            ->where('pack_id', $this->pack->id)
            ->first();

        $this->assertNotNull($purchase);
        $this->assertEquals('confirmed', $purchase->status);
        $this->assertEquals('credit_card', $purchase->payment_method);

        // Creator balance should go to pending
        $this->creator->refresh();
        $this->assertEquals(0.00, $this->creator->balance_available);
        $this->assertEquals(42.50, $this->creator->balance_pending);

        // Create transaction check
        $transaction = Transaction::where('transactionable_type', Purchase::class)
            ->where('transactionable_id', $purchase->id)
            ->first();
        $this->assertNotNull($transaction);

        // Run the balance release command
        // To simulate custody release, we manually update the transaction created_at to 31 days ago
        $transaction->created_at = now()->subDays(31);
        $transaction->save();

        $this->artisan('app:release-pending-balances')
            ->assertExitCode(0);

        // Creator balance should be released
        $this->creator->refresh();
        $this->assertEquals(42.50, $this->creator->balance_available);
        $this->assertEquals(0.00, $this->creator->balance_pending);
    }

    public function test_creator_withdrawal_flow_with_admin_approval(): void
    {
        // 1. Give creator some available balance
        $this->creator->update(['balance_available' => 100.00]);

        $this->actingAs($this->creator);

        // 2. Try to withdraw without PIX key
        $response1 = $this->post(route('dashboard.withdraw'), [
            'amount' => 50.00,
        ]);
        $response1->assertStatus(302);
        $response1->assertSessionHas('error', 'Por favor, configure sua chave PIX no seu perfil antes de solicitar um saque.');

        // 3. Set PIX key via profile update
        $profileResponse = $this->put(route('dashboard.profile.update'), [
            'name' => 'Isabella Santos',
            'username' => 'isabellasantos',
            'bio' => 'Bio updated',
            'subscription_price' => 29.90,
            'pix_key_type' => 'email',
            'pix_key' => 'isabella@demo.com',
        ]);
        $profileResponse->assertStatus(302);
        $this->creator->refresh();
        $this->assertEquals('email', $this->creator->pix_key_type);
        $this->assertEquals('isabella@demo.com', $this->creator->pix_key);

        // 4. Request withdrawal
        $response2 = $this->post(route('dashboard.withdraw'), [
            'amount' => 50.00,
        ]);
        $response2->assertStatus(302);
        $response2->assertSessionHas('success');

        // Check withdrawal created and balance decremented
        $this->creator->refresh();
        $this->assertEquals(50.00, $this->creator->balance_available);

        $withdrawal = Withdrawal::where('user_id', $this->creator->id)->first();
        $this->assertNotNull($withdrawal);
        $this->assertEquals(50.00, $withdrawal->amount);
        $this->assertEquals('pending', $withdrawal->status);

        // 5. Try to approve as admin
        $this->actingAs($this->admin);

        $approveResponse = $this->post(route('admin.withdrawals.approve', $withdrawal->id));
        $approveResponse->assertStatus(302);
        $approveResponse->assertSessionHas('success');

        $withdrawal->refresh();
        $this->assertEquals('completed', $withdrawal->status);

        // Creator should have a completed withdrawal transaction
        $transaction = Transaction::where('transactionable_type', Withdrawal::class)
            ->where('transactionable_id', $withdrawal->id)
            ->first();
        $this->assertNotNull($transaction);
        $this->assertEquals(-50.00, $transaction->creator_amount);
    }

    public function test_creator_withdrawal_rejection(): void
    {
        // 1. Give creator balance and PIX key
        $this->creator->update([
            'balance_available' => 100.00,
            'pix_key_type' => 'random',
            'pix_key' => 'abc-123-key',
        ]);

        $this->actingAs($this->creator);

        // 2. Request withdrawal
        $this->post(route('dashboard.withdraw'), [
            'amount' => 40.00,
        ])->assertStatus(302);

        $this->creator->refresh();
        $this->assertEquals(60.00, $this->creator->balance_available);

        $withdrawal = Withdrawal::where('user_id', $this->creator->id)->first();
        $this->assertNotNull($withdrawal);

        // 3. Reject as admin
        $this->actingAs($this->admin);

        $rejectResponse = $this->post(route('admin.withdrawals.reject', $withdrawal->id), [
            'reason' => 'Dados bancários inválidos.',
        ]);
        $rejectResponse->assertStatus(302);
        $rejectResponse->assertSessionHas('success');

        $withdrawal->refresh();
        $this->assertEquals('failed', $withdrawal->status);
        $this->assertEquals('Dados bancários inválidos.', $withdrawal->status_message);

        // Funds should be returned to creator available balance
        $this->creator->refresh();
        $this->assertEquals(100.00, $this->creator->balance_available);
    }
}
